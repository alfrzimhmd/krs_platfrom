<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Mahasiswa;

class MahasiswaAuthController extends Controller
{
    /**
     * Tampilkan form login mahasiswa
     */
    public function showLoginForm()
    {
        return view('mahasiswa.login');
    }

    /**
     * Proses login mahasiswa
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string',
            'nim' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        // Cari mahasiswa berdasarkan NIM
        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();

        if ($mahasiswa) {
            // Jika mahasiswa sudah ada, update nama jika berbeda
            if ($mahasiswa->nama !== $request->nama) {
                $mahasiswa->update(['nama' => $request->nama]);
            }
            // Update semester jika berbeda
            if ($mahasiswa->semester_saat_ini !== $request->semester) {
                $mahasiswa->update(['semester_saat_ini' => $request->semester]);
            }
        } else {
            // Jika belum ada, buat mahasiswa baru
            $mahasiswa = Mahasiswa::create([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'semester_saat_ini' => $request->semester,
                'dosen_id' => null,
            ]);
        }

        // Simpan data ke session
        Session::put('mahasiswa', [
            'id' => $mahasiswa->id,
            'nama' => $mahasiswa->nama,
            'nim' => $mahasiswa->nim,
            'semester' => $request->semester,
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Selamat datang, ' . $mahasiswa->nama . '!');
    }

    /**
     * Logout mahasiswa
     */
    public function logout()
    {
        Session::forget('mahasiswa');
        return redirect()->route('mahasiswa.login.form')
            ->with('success', 'Anda telah logout.');
    }
}