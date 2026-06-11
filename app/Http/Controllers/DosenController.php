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
        
        return view('dosen.detail_krs', compact('krs'));
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
        
        // Jika ada update status (untuk KRS yang sudah tidak menunggu)
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