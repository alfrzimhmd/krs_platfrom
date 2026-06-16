<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Matakuliah;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DosenController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    public function dashboard()
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        
        $mahasiswas = Mahasiswa::with(['krs.matakuliahs', 'dosen'])
            ->where('dosen_id', $dosen->id)
            ->get();
        
        return view('dosen.dashboard', compact('mahasiswas', 'dosen'));
    }

    public function detailKrs($id)
    {
        $krs = Krs::with(['mahasiswa.dosen', 'matakuliahs'])->findOrFail($id);
        
        $user = Auth::user();
        $dosenId = $user->dosen->id;
        
        if ($krs->mahasiswa->dosen_id !== $dosenId) {
            abort(403, 'Anda tidak berhak mengakses KRS ini.');
        }
        
        // Generate riwayat akademik untuk mahasiswa ini
        $mahasiswa = $krs->mahasiswa;
        $nomorSemester = $mahasiswa->nomor_semester;
        $riwayatGenerated = [];
        
        // Ambil riwayat dari database (SEMUA STATUS)
        $riwayatDb = $mahasiswa->krs()
            ->with('matakuliahs')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Generate riwayat untuk semester sebelumnya
        if ($nomorSemester && $nomorSemester > 1) {
            $riwayatGenerated = $this->generateRiwayatAkademik($nomorSemester, $mahasiswa);
        }
        
        // Gabungkan riwayat
        $riwayatFinal = $this->gabungkanRiwayat($riwayatDb, $riwayatGenerated);
        
        return view('dosen.detail_krs', compact('krs', 'riwayatFinal'));
    }

    /**
     * Generate riwayat akademik untuk semester sebelumnya
     */
    private function generateRiwayatAkademik($nomorSemesterAktif, $mahasiswa)
    {
        $riwayat = [];
        $tahunAwal = date('Y') - ceil($nomorSemesterAktif / 2);
        
        for ($i = 1; $i < $nomorSemesterAktif; $i++) {
            $jenisSemester = ($i % 2 == 1) ? 'Ganjil' : 'Genap';
            $tahun = $tahunAwal + floor(($i - 1) / 2);
            
            $matakuliahs = Matakuliah::where('semester', $jenisSemester)->get();
            
            $matakuliahDenganNilai = [];
            
            // LOGIKA YANG SAMA DENGAN KRS CONTROLLER
            $isSemesterAktif = ($i == $nomorSemesterAktif); // TIDAK ADA NILAI
            $isSemesterSebelumnya = ($i == $nomorSemesterAktif - 1); // TIDAK ADA NILAI
            $isSemesterSelesai = ($i < $nomorSemesterAktif - 1); // ADA NILAI
            
            foreach ($matakuliahs as $mk) {
                $dataMatkul = [
                    'kode_mk' => $mk->kode_mk,
                    'nama_mk' => $mk->nama_mk,
                    'sks' => $mk->sks,
                ];
                
                if ($isSemesterSelesai) {
                    $nilai = $this->generateNilaiRandom();
                    $dataMatkul['nilai'] = $nilai;
                    $dataMatkul['bobot'] = $this->konversiNilaiKeBobot($nilai);
                }
                
                $matakuliahDenganNilai[] = $dataMatkul;
            }
            
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
                'is_semester_aktif' => $isSemesterAktif,
                'is_semester_sebelumnya' => $isSemesterSebelumnya,
                'is_semester_selesai' => $isSemesterSelesai
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
        
        $nomorSemesterDb = [];
        foreach ($riwayatDb as $r) {
            $nomor = $r->mahasiswa->nomor_semester ?? null;
            if ($nomor) {
                $nomorSemesterDb[] = $nomor;
            }
        }
        
        foreach ($riwayatDb as $r) {
            $status = $r->status;
            $nomorSemester = $r->mahasiswa->nomor_semester ?? 0;
            $isSelesai = ($status == 'disetujui');
            
            $result[] = [
                'semester' => $r->semester,
                'nomor_semester' => $nomorSemester,
                'tahun' => $r->created_at->year,
                'matakuliahs' => $r->matakuliahs->map(function ($mk) use ($status, $isSelesai) {
                    return [
                        'kode_mk' => $mk->kode_mk,
                        'nama_mk' => $mk->nama_mk,
                        'sks' => $mk->sks,
                        'nilai' => ($isSelesai) ? $this->generateNilaiRandom() : null,
                        'bobot' => ($isSelesai) ? 3.50 : 0
                    ];
                })->toArray(),
                'total_sks' => $r->total_sks,
                'ipk' => ($isSelesai) ? 3.50 : 0,
                'is_generated' => false,
                'is_semester_aktif' => ($status == 'menunggu'),
                'is_semester_sebelumnya' => false,
                'is_semester_selesai' => $isSelesai,
                'status' => $status
            ];
        }
        
        foreach ($riwayatGenerated as $r) {
            if (!in_array($r['nomor_semester'], $nomorSemesterDb)) {
                $result[] = $r;
            }
        }
        
        usort($result, function ($a, $b) {
            return $b['nomor_semester'] - $a['nomor_semester'];
        });
        
        return $result;
    }

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

    public function approveKrs($id)
    {
        $krs = Krs::findOrFail($id);
        
        $user = Auth::user();
        $dosenId = $user->dosen->id;
        
        if ($krs->mahasiswa->dosen_id !== $dosenId) {
            abort(403, 'Anda tidak berhak menyetujui KRS ini.');
        }
        
        $krs->update(['status' => 'disetujui']);
        
        return redirect()->route('dosen.dashboard')
            ->with('success', 'KRS berhasil disetujui.');
    }

    public function rejectKrs($id)
    {
        $krs = Krs::findOrFail($id);
        
        $user = Auth::user();
        $dosenId = $user->dosen->id;
        
        if ($krs->mahasiswa->dosen_id !== $dosenId) {
            abort(403, 'Anda tidak berhak menolak KRS ini.');
        }
        
        $krs->update(['status' => 'ditolak']);
        
        return redirect()->route('dosen.dashboard')
            ->with('success', 'KRS berhasil ditolak.');
    }

    public function updateMahasiswa(Request $request, $id)
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        $mahasiswa = Mahasiswa::with('krs')->where('id', $id)->where('dosen_id', $dosen->id)->firstOrFail();
        
        $request->validate([
            'nama' => 'required|string',
            'nim' => 'required|string|unique:mahasiswas,nim,' . $id,
        ]);
        
        $mahasiswa->update([
            'nama' => $request->nama,
            'nim' => $request->nim,
        ]);
        
        if ($request->has('status') && $request->status) {
            $krs = $mahasiswa->krs->first();
            if ($krs) {
                $krs->update(['status' => $request->status]);
            }
        }
        
        return redirect()->route('dosen.krs.detail', $mahasiswa->krs->first()->id)
            ->with('success', 'Data mahasiswa berhasil diupdate.');
    }
}