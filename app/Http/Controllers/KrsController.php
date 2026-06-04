<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Matakuliah;
use App\Models\Dosen;
use App\Models\Krs;
use App\Models\Mahasiswa;

class KrsController extends Controller
{
    /**
     * Halaman dashboard mahasiswa (gabungan form KRS + status)
     */
    public function dashboard()
    {
        $mahasiswaSession = Session::get('mahasiswa');
        $mahasiswa = Mahasiswa::with(['dosen'])->find($mahasiswaSession['id']);
        
        // Ambil KRS terbaru mahasiswa
        $krs = $mahasiswa->krs()->with('matakuliahs')->latest()->first();
        
        $semester = $mahasiswaSession['semester'];
        
        // Ambil mata kuliah berdasarkan semester
        $matakuliahs = Matakuliah::where('semester', $semester)->get();
        
        // Ambil semua dosen untuk dropdown
        $dosens = Dosen::all();
        
        // Jika sudah punya KRS dan status menunggu, ambil mata kuliah yang sudah dipilih
        $selectedMatakuliahs = [];
        $existingKrs = null;
        
        if ($krs && $krs->status === 'menunggu') {
            $existingKrs = $krs;
            $selectedMatakuliahs = $krs->matakuliahs->pluck('id')->toArray();
        }
        
        return view('mahasiswa.dashboard', compact(
            'matakuliahs', 
            'dosens', 
            'semester', 
            'selectedMatakuliahs', 
            'krs',
            'existingKrs',
            'mahasiswa'
        ));
    }

    /**
     * Simpan atau update KRS
     */
    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'matakuliah_ids' => 'required|array|min:1',
            'matakuliah_ids.*' => 'exists:matakuliahs,id',
            'bukti_ukt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $mahasiswaSession = Session::get('mahasiswa');
        $mahasiswa = Mahasiswa::find($mahasiswaSession['id']);
        
        // Update dosen PA mahasiswa
        $mahasiswa->update(['dosen_id' => $request->dosen_id]);
        
        // Cek apakah sudah ada KRS yang menunggu
        $existingKrs = $mahasiswa->krs()->where('status', 'menunggu')->first();
        
        // Upload file bukti UKT
        $filePath = $request->file('bukti_ukt')->store('bukti_ukt', 'public');
        
        if ($existingKrs) {
            // Hapus file lama jika ada
            if ($existingKrs->bukti_ukt_path && Storage::disk('public')->exists($existingKrs->bukti_ukt_path)) {
                Storage::disk('public')->delete($existingKrs->bukti_ukt_path);
            }
            
            // Update KRS yang sudah ada
            $existingKrs->update([
                'bukti_ukt_path' => $filePath,
            ]);
            
            // Sync mata kuliah
            $existingKrs->matakuliahs()->sync($request->matakuliah_ids);
            $existingKrs->updateTotalSks();
            
            $message = 'KRS berhasil diperbarui!';
        } else {
            // Buat KRS baru
            $krs = Krs::create([
                'mahasiswa_id' => $mahasiswa->id,
                'semester' => $mahasiswaSession['semester'],
                'total_sks' => 0,
                'status' => 'menunggu',
                'bukti_ukt_path' => $filePath,
            ]);
            
            // Attach mata kuliah
            $krs->matakuliahs()->attach($request->matakuliah_ids);
            $krs->updateTotalSks();
            
            $message = 'KRS berhasil diajukan!';
        }
        
        return redirect()->route('mahasiswa.dashboard')->with('success', $message);
    }
}