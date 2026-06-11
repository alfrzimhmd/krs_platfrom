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
        
        // Ambil riwayat akademik (KRS yang sudah disetujui)
        $riwayatKrs = $mahasiswa->krs()
            ->where('status', 'disetujui')
            ->with('matakuliahs')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Hitung statistik
        $totalSksDitempuh = $riwayatKrs->sum('total_sks');
        $totalMatkulDitempuh = 0;
        foreach ($riwayatKrs as $rk) {
            $totalMatkulDitempuh += $rk->matakuliahs->count();
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
            'riwayatKrs',
            'totalSksDitempuh',
            'totalMatkulDitempuh',
            'totalPengajuan',
            'ipkSementara'
        ));
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
            return response()->json(['error' => 'Semester Ganjil hanya bisa memilih nomor semester ganjil (1, 3, 5, 7, 9, 11, 13).'], 422);
        }
        if ($request->semester === 'Genap' && $request->nomor_semester % 2 !== 0) {
            return response()->json(['error' => 'Semester Genap hanya bisa memilih nomor semester genap (2, 4, 6, 8, 10, 12, 14).'], 422);
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
        
        // Cek apakah ini update atau create baru
        $existingKrs = $mahasiswa->krs()->where('status', 'menunggu')->first();
        
        // Validasi dasar
        $rules = [
            'dosen_id' => 'required|exists:dosens,id',
            'semester' => 'required|in:Ganjil,Genap',
            'nomor_semester' => 'required|integer|min:1|max:14',
            'matakuliah_ids' => 'required|array|min:1',
            'matakuliah_ids.*' => 'exists:matakuliahs,id',
        ];
        
        // Validasi bukti_ukt: WAJIB jika create baru, OPSIONAL jika update
        if (!$existingKrs) {
            $rules['bukti_ukt'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
        } else {
            $rules['bukti_ukt'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        
        $request->validate($rules);

        // Validasi nomor semester sesuai jenis semester
        if ($request->semester === 'Ganjil' && $request->nomor_semester % 2 === 0) {
            return back()->withErrors(['nomor_semester' => 'Semester Ganjil hanya bisa memilih nomor semester ganjil.'])->withInput();
        }
        if ($request->semester === 'Genap' && $request->nomor_semester % 2 !== 0) {
            return back()->withErrors(['nomor_semester' => 'Semester Genap hanya bisa memilih nomor semester genap.'])->withInput();
        }

        // Update data mahasiswa
        $mahasiswa->update([
            'dosen_id' => $request->dosen_id,
            'semester_saat_ini' => $request->semester,
            'nomor_semester' => $request->nomor_semester,
        ]);
        
        // Update session
        Session::put('mahasiswa', array_merge($mahasiswaSession, [
            'semester' => $request->semester,
            'nomor_semester' => (int) $request->nomor_semester,
        ]));
        
        // Proses upload file (hanya jika ada file baru)
        $filePath = null;
        if ($request->hasFile('bukti_ukt')) {
            $filePath = $request->file('bukti_ukt')->store('bukti_ukt', 'public');
        }
        
        if ($existingKrs) {
            // UPDATE KRS yang sudah ada
            $updateData = ['semester' => $request->semester];
            
            // Jika upload file baru, hapus file lama dan simpan yang baru
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
            // CREATE KRS baru (file WAJIB ada)
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
     * Hitung IPK sementara (contoh sederhana)
     */
    private function hitungIpk($mahasiswaId)
    {
        // Ini bisa dikembangkan dengan tabel nilai nanti
        // Sementara return nilai default
        return 3.50;
    }
}