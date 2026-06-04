<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DosenController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * Dashboard dosen - menampilkan semua mahasiswa bimbingan dan KRS mereka
     */
    public function dashboard()
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        
        // Ambil semua mahasiswa yang memilih dosen ini sebagai PA
        $mahasiswas = Mahasiswa::with(['krs.matakuliahs'])
            ->where('dosen_id', $dosen->id)
            ->get();
        
        return view('dosen.dashboard', compact('mahasiswas', 'dosen'));
    }

    /**
     * Setujui KRS
     */
    public function approveKrs($id)
    {
        $krs = Krs::findOrFail($id);
        
        // Cek apakah mahasiswa ini adalah bimbingannya
        $user = Auth::user();
        $dosenId = $user->dosen->id;
        
        if ($krs->mahasiswa->dosen_id !== $dosenId) {
            abort(403, 'Anda tidak berhak menyetujui KRS ini.');
        }
        
        $krs->update(['status' => 'disetujui']);
        
        return redirect()->back()->with('success', 'KRS berhasil disetujui.');
    }

    /**
     * Tolak KRS
     */
    public function rejectKrs($id)
    {
        $krs = Krs::findOrFail($id);
        
        $user = Auth::user();
        $dosenId = $user->dosen->id;
        
        if ($krs->mahasiswa->dosen_id !== $dosenId) {
            abort(403, 'Anda tidak berhak menolak KRS ini.');
        }
        
        $krs->update(['status' => 'ditolak']);
        
        return redirect()->back()->with('success', 'KRS berhasil ditolak.');
    }

    /**
     * Daftar semua mahasiswa bimbingan
     */
    public function mahasiswaList()
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        $mahasiswas = Mahasiswa::where('dosen_id', $dosen->id)->get();
        
        return view('dosen.mahasiswa-index', compact('mahasiswas'));
    }

    /**
     * Form edit mahasiswa bimbingan
     */
    public function editMahasiswa($id)
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        $mahasiswa = Mahasiswa::where('id', $id)->where('dosen_id', $dosen->id)->firstOrFail();
        
        return view('dosen.mahasiswa-edit', compact('mahasiswa'));
    }

    /**
     * Update data mahasiswa bimbingan
     */
    public function updateMahasiswa(Request $request, $id)
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        $mahasiswa = Mahasiswa::where('id', $id)->where('dosen_id', $dosen->id)->firstOrFail();
        
        $request->validate([
            'nama' => 'required|string',
            'nim' => 'required|string|unique:mahasiswas,nim,' . $id,
            'semester_saat_ini' => 'required|in:Ganjil,Genap',
        ]);
        
        $mahasiswa->update([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'semester_saat_ini' => $request->semester_saat_ini,
        ]);
        
        return redirect()->route('dosen.mahasiswa.list')
            ->with('success', 'Data mahasiswa berhasil diupdate.');
    }

    /**
     * Hapus mahasiswa bimbingan
     */
    public function destroyMahasiswa($id)
    {
        $user = Auth::user();
        $dosen = $user->dosen;
        $mahasiswa = Mahasiswa::where('id', $id)->where('dosen_id', $dosen->id)->firstOrFail();
        
        // Hapus KRS yang terkait beserta pivot nya
        foreach ($mahasiswa->krs as $krs) {
            $krs->matakuliahs()->detach();
            $krs->delete();
        }
        
        $mahasiswa->delete();
        
        return redirect()->route('dosen.mahasiswa.list')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}