<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Matakuliah;
use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;

class KrsController extends Controller
{
    /**
     * Halaman dashboard mahasiswa (form KRS + status + riwayat + statistik)
     */
    public function dashboard()
    {
        $mahasiswaSession = Session::get('mahasiswa');
        $mahasiswa = Mahasiswa::with(['dosen'])->find($mahasiswaSession['id']);
        
        // Ambil KRS terbaru mahasiswa
        $krs = $mahasiswa->krs()->with('matakuliahs')->latest()->first();
        
        // Cek apakah ada KRS yang sedang menunggu
        $pendingKrs = $mahasiswa->krs()->where('status', 'menunggu')->first();
        
        // Jika ada KRS yang menunggu, gunakan data dari KRS tersebut
        if ($pendingKrs) {
            $krs = $pendingKrs;
            $selectedSemester = $krs->semester;
            $selectedNomorSemester = $mahasiswa->nomor_semester;
            $selectedMatakuliahs = $krs->matakuliahs->pluck('id')->toArray();
            $existingKrs = $krs;
        } else {
            $selectedSemester = $mahasiswaSession['semester'] ?? null;
            $selectedNomorSemester = $mahasiswaSession['nomor_semester'] ?? null;
            $selectedMatakuliahs = [];
            $existingKrs = null;
        }
        
        // Ambil mata kuliah berdasarkan semester yang dipilih
        $matakuliahs = [];
        if ($selectedSemester) {
            $matakuliahs = Matakuliah::where('semester', $selectedSemester)->get();
        }
        
        // Ambil semua dosen untuk dropdown
        $dosens = Dosen::all();
        
        // ==== GENERATE RIWAYAT AKADEMIK ====
        // Ambil semua KRS dari database (SEMUA STATUS)
        $riwayatKrs = $mahasiswa->krs()
            ->with('matakuliahs')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Generate riwayat untuk semester sebelumnya (jika ada)
        $riwayatGenerated = [];
        if ($selectedNomorSemester && $selectedNomorSemester > 1) {
            $riwayatGenerated = $this->generateRiwayatAkademik($selectedNomorSemester, $mahasiswa);
        }
        
        // Gabungkan riwayat dari database + generated
        $riwayatFinal = $this->gabungkanRiwayat($riwayatKrs, $riwayatGenerated);
        
        // Hitung statistik
        $totalSksDitempuh = 0;
        $totalMatkulDitempuh = 0;
        
        foreach ($riwayatFinal as $r) {
            $totalSksDitempuh += $r['total_sks'];
            $totalMatkulDitempuh += count($r['matakuliahs']);
        }
        
        $totalPengajuan = $mahasiswa->krs()->count();
        $ipkSementara = $this->hitungIpk($mahasiswa->id);
        
        return view('mahasiswa.dashboard', compact(
            'matakuliahs',
            'dosens',
            'selectedSemester',
            'selectedNomorSemester',
            'selectedMatakuliahs',
            'krs',
            'existingKrs',
            'mahasiswa',
            'riwayatFinal',
            'totalSksDitempuh',
            'totalMatkulDitempuh',
            'totalPengajuan',
            'ipkSementara'
        ));
    }

    /**
     * Generate riwayat akademik untuk semester sebelumnya
     */
    private function generateRiwayatAkademik($nomorSemesterAktif, $mahasiswa)
    {
        $riwayat = [];
        $tahunAwal = date('Y') - ceil($nomorSemesterAktif / 2);
        
        // Loop dari semester 1 sampai (nomorSemesterAktif - 1)
        for ($i = 1; $i < $nomorSemesterAktif; $i++) {
            $jenisSemester = ($i % 2 == 1) ? 'Ganjil' : 'Genap';
            $tahun = $tahunAwal + floor(($i - 1) / 2);
            
            $matakuliahs = Matakuliah::where('semester', $jenisSemester)->get();
            
            // *** LOGIKA YANG BENAR ***
            // - Semester yang diinput (nomorSemesterAktif) = TIDAK ADA NILAI (aktif)
            // - Semester sebelumnya (nomorSemesterAktif - 1) = TIDAK ADA NILAI (belum selesai)
            // - Semester di bawahnya = ADA NILAI (sudah selesai)
            //
            // Contoh input semester 5:
            // - Semester 1 = ADA NILAI (selesai)
            // - Semester 2 = ADA NILAI (selesai)
            // - Semester 3 = ADA NILAI (selesai)  <-- PERUBAHAN: seharusnya ADA karena sudah lewat
            // - Semester 4 = TIDAK ADA NILAI (belum selesai, semester sebelumnya)
            // - Semester 5 = TIDAK ADA NILAI (yang diinput)
            
            $isSemesterAktif = ($i == $nomorSemesterAktif); // Semester yang diinput = TIDAK ADA NILAI
            $isSemesterSebelumnya = ($i == $nomorSemesterAktif - 1); // Semester sebelumnya = TIDAK ADA NILAI
            $isSemesterSelesai = ($i < $nomorSemesterAktif - 1); // Semester di bawah sebelumnya = ADA NILAI
            
            $matakuliahDenganNilai = [];
            
            foreach ($matakuliahs as $mk) {
                $dataMatkul = [
                    'kode_mk' => $mk->kode_mk,
                    'nama_mk' => $mk->nama_mk,
                    'sks' => $mk->sks,
                ];
                
                // Tampilkan nilai HANYA jika semester sudah selesai (i < nomorSemesterAktif - 1)
                if ($isSemesterSelesai) {
                    $nilai = $this->generateNilaiRandom();
                    $dataMatkul['nilai'] = $nilai;
                    $dataMatkul['bobot'] = $this->konversiNilaiKeBobot($nilai);
                }
                
                $matakuliahDenganNilai[] = $dataMatkul;
            }
            
            // Hitung IPK semester hanya jika ada nilai
            $totalBobot = 0;
            $totalSks = 0;
            $ipkSemester = 0;
            
            foreach ($matakuliahDenganNilai as $mk) {
                $totalSks += $mk['sks'];
                if (isset($mk['bobot'])) {
                    $totalBobot += $mk['sks'] * $mk['bobot'];
                }
            }
            
            if ($totalSks > 0 && $totalBobot > 0) {
                $ipkSemester = round($totalBobot / $totalSks, 2);
            }
            
            $riwayat[] = [
                'semester' => $jenisSemester,
                'nomor_semester' => $i,
                'tahun' => $tahun,
                'matakuliahs' => $matakuliahDenganNilai,
                'total_sks' => $totalSks,
                'ipk' => $ipkSemester,
                'is_generated' => true,
                'is_semester_aktif' => $isSemesterAktif, // true = tidak ada nilai
                'is_semester_sebelumnya' => $isSemesterSebelumnya, // true = tidak ada nilai
                'is_semester_selesai' => $isSemesterSelesai // true = ada nilai
            ];
        }
        
        return $riwayat;
    }

    /**
     * Gabungkan riwayat dari database dengan generated
     */
    private function gabungkanRiwayat($riwayatDb, $riwayatGenerated)
    {
        $result = [];
        
        // Ambil nomor semester yang sudah ada di database
        $nomorSemesterDb = [];
        foreach ($riwayatDb as $r) {
            $nomor = $r->mahasiswa->nomor_semester ?? null;
            if ($nomor) {
                $nomorSemesterDb[] = $nomor;
            }
        }
        
        // Tambahkan riwayat dari database (SEMUA STATUS)
        foreach ($riwayatDb as $r) {
            $status = $r->status;
            $nomorSemester = $r->mahasiswa->nomor_semester ?? 0;
            
            // Cek apakah semester ini sudah lewat dari semester aktif
            // Jika ya, beri nilai default
            $isSelesai = ($status != 'menunggu' && $status != 'ditolak');
            
            $result[] = [
                'semester' => $r->semester,
                'nomor_semester' => $nomorSemester,
                'tahun' => $r->created_at->year,
                'matakuliahs' => $r->matakuliahs->map(function ($mk) use ($status, $isSelesai) {
                    return [
                        'kode_mk' => $mk->kode_mk,
                        'nama_mk' => $mk->nama_mk,
                        'sks' => $mk->sks,
                        'nilai' => ($isSelesai && $status == 'disetujui') ? $this->generateNilaiRandom() : null,
                        'bobot' => ($isSelesai && $status == 'disetujui') ? 3.50 : 0
                    ];
                })->toArray(),
                'total_sks' => $r->total_sks,
                'ipk' => ($isSelesai && $status == 'disetujui') ? 3.50 : 0,
                'is_generated' => false,
                'is_semester_aktif' => ($status == 'menunggu'),
                'is_semester_sebelumnya' => false,
                'is_semester_selesai' => ($status == 'disetujui'),
                'status' => $status
            ];
        }
        
        // Tambahkan riwayat generated yang tidak ada di database
        foreach ($riwayatGenerated as $r) {
            if (!in_array($r['nomor_semester'], $nomorSemesterDb)) {
                $result[] = $r;
            }
        }
        
        // Urutkan berdasarkan nomor semester (descending - terbesar ke terkecil)
        usort($result, function ($a, $b) {
            return $b['nomor_semester'] - $a['nomor_semester'];
        });
        
        return $result;
    }

    /**
     * Generate nilai random dengan distribusi normal
     */
    private function generateNilaiRandom()
    {
        $nilaiOptions = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C'];
        $weights = [10, 10, 25, 30, 15, 7, 3];
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $cumulative = 0;
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $nilaiOptions[$index];
            }
        }
        return 'B';
    }

    /**
     * Konversi nilai huruf ke bobot angka
     */
    private function konversiNilaiKeBobot($nilai)
    {
        $bobot = [
            'A' => 4.00,
            'A-' => 3.75,
            'B+' => 3.50,
            'B' => 3.00,
            'B-' => 2.75,
            'C+' => 2.50,
            'C' => 2.00,
        ];
        return $bobot[$nilai] ?? 3.00;
    }

    /**
     * Update pilihan semester di session
     */
    public function updateSemester(Request $request)
    {
        $request->validate([
            'semester' => 'required|in:Ganjil,Genap',
            'nomor_semester' => 'required|integer|min:1|max:14',
        ]);

        if ($request->semester === 'Ganjil' && $request->nomor_semester % 2 === 0) {
            return response()->json(['error' => 'Semester Ganjil hanya bisa memilih nomor semester ganjil.'], 422);
        }
        if ($request->semester === 'Genap' && $request->nomor_semester % 2 !== 0) {
            return response()->json(['error' => 'Semester Genap hanya bisa memilih nomor semester genap.'], 422);
        }

        $mahasiswaSession = Session::get('mahasiswa');
        $mahasiswa = Mahasiswa::find($mahasiswaSession['id']);
        
        $mahasiswa->update([
            'semester_saat_ini' => $request->semester,
            'nomor_semester' => $request->nomor_semester,
        ]);
        
        Session::put('mahasiswa', array_merge($mahasiswaSession, [
            'semester' => $request->semester,
            'nomor_semester' => (int) $request->nomor_semester,
        ]));
        
        $matakuliahs = Matakuliah::where('semester', $request->semester)->get();
        
        return response()->json([
            'success' => true,
            'semester' => $request->semester,
            'nomor_semester' => $request->nomor_semester,
            'matakuliahs' => $matakuliahs
        ]);
    }

    /**
     * Simpan atau update KRS
     */
    public function store(Request $request)
    {
        $mahasiswaSession = Session::get('mahasiswa');
        $mahasiswa = Mahasiswa::find($mahasiswaSession['id']);
        
        // Guard: Cek apakah ada KRS yang sudah final
        $latestKrs = $mahasiswa->krs()->latest()->first();
        if ($latestKrs && in_array($latestKrs->status, ['ditolak', 'disetujui'])) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Pengajuan KRS Anda sudah ' . $latestKrs->status . ' dan tidak dapat diubah lagi.');
        }

        $existingKrs = $mahasiswa->krs()->where('status', 'menunggu')->first();
        
        $rules = [
            'dosen_id' => 'required|exists:dosens,id',
            'semester' => 'required|in:Ganjil,Genap',
            'nomor_semester' => 'required|integer|min:1|max:14',
            'matakuliah_ids' => 'required|array|min:1',
            'matakuliah_ids.*' => 'exists:matakuliahs,id',
        ];
        
        if (!$existingKrs) {
            $rules['bukti_ukt'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } else {
            $rules['bukti_ukt'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        
        $request->validate($rules);

        if ($request->semester === 'Ganjil' && $request->nomor_semester % 2 === 0) {
            return back()->withErrors(['nomor_semester' => 'Semester Ganjil hanya bisa memilih nomor semester ganjil.'])->withInput();
        }
        if ($request->semester === 'Genap' && $request->nomor_semester % 2 !== 0) {
            return back()->withErrors(['nomor_semester' => 'Semester Genap hanya bisa memilih nomor semester genap.'])->withInput();
        }

        $mahasiswa->update([
            'dosen_id' => $request->dosen_id,
            'semester_saat_ini' => $request->semester,
            'nomor_semester' => $request->nomor_semester,
        ]);
        
        Session::put('mahasiswa', array_merge($mahasiswaSession, [
            'semester' => $request->semester,
            'nomor_semester' => (int) $request->nomor_semester,
        ]));
        
        $filePath = null;
        if ($request->hasFile('bukti_ukt')) {
            $filePath = $request->file('bukti_ukt')->store('bukti_ukt', 'public');
        }
        
        if ($existingKrs) {
            $updateData = ['semester' => $request->semester];
            
            if ($filePath) {
                if ($existingKrs->bukti_ukt_path && Storage::disk('public')->exists($existingKrs->bukti_ukt_path)) {
                    Storage::disk('public')->delete($existingKrs->bukti_ukt_path);
                }
                $updateData['bukti_ukt_path'] = $filePath;
            }
            
            $existingKrs->update($updateData);
            $existingKrs->matakuliahs()->sync($request->matakuliah_ids);
            $existingKrs->updateTotalSks();
            
            $message = 'KRS berhasil diperbarui!';
        } else {
            if (!$filePath) {
                return back()->withErrors(['bukti_ukt' => 'Bukti pembayaran UKT wajib diunggah.'])->withInput();
            }
            
            $krs = Krs::create([
                'mahasiswa_id' => $mahasiswa->id,
                'semester' => $request->semester,
                'total_sks' => 0,
                'status' => 'menunggu',
                'bukti_ukt_path' => $filePath,
            ]);
            
            $krs->matakuliahs()->attach($request->matakuliah_ids);
            $krs->updateTotalSks();
            
            $message = 'KRS berhasil diajukan!';
        }
        
        return redirect()->route('mahasiswa.dashboard')->with('success', $message);
    }

    /**
     * Cetak KRS dalam format PDF
     */
    public function cetakKrs()
    {
        $mahasiswaSession = Session::get('mahasiswa');
        $mahasiswa = Mahasiswa::with(['dosen'])->find($mahasiswaSession['id']);
        
        $krs = $mahasiswa->krs()->with('matakuliahs')->where('status', 'disetujui')->latest()->first();
        
        if (!$krs) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Tidak ada KRS yang disetujui untuk dicetak.');
        }
        
        $data = [
            'krs' => $krs,
            'mahasiswa' => $mahasiswa,
            'tanggal_cetak' => now()->translatedFormat('d F Y H:i'),
        ];
        
        $pdf = Pdf::loadView('pdf.krs', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('KRS_' . $mahasiswa->nim . '_' . $krs->semester . '.pdf');
    }
    
    /**
     * Hitung IPK sementara
     */
    private function hitungIpk($mahasiswaId)
    {
        return 3.50;
    }
}