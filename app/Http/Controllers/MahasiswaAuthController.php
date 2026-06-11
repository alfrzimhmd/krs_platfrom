<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
     * Tampilkan form registrasi mahasiswa
     */
    public function showRegisterForm()
    {
        return view('mahasiswa.register');
    }

    /**
     * Proses registrasi mahasiswa baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|max:15|unique:mahasiswas,nim',
            'email' => 'required|email|unique:mahasiswas,email',
            'password' => 'required|min:6|confirmed',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nim.unique' => 'NIM :input sudah terdaftar. Silakan gunakan NIM yang berbeda.',
            'email.unique' => 'Email :input sudah terdaftar. Silakan gunakan email yang berbeda.',
        ]);

        // Upload foto profil jika ada
        $fotoPath = null;
        if ($request->hasFile('foto_profil')) {
            $fotoPath = $request->file('foto_profil')->store('foto_mahasiswa', 'public');
        }

        // Buat mahasiswa baru (dosen_id akan diisi di halaman KRS)
        Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'password' => $request->password,
            'foto_profil' => $fotoPath,
            'dosen_id' => null,  // <--- HAPUS ATAU SET NULL
            'semester_saat_ini' => null,
            'nomor_semester' => null,
        ]);

        return redirect()->route('mahasiswa.login.form')
            ->with('success', 'Registrasi berhasil! Silakan login dengan email dan password Anda.');
    }

    /**
     * Proses login mahasiswa (Email + Password)
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari mahasiswa berdasarkan email
        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if (!$mahasiswa) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Email tidak terdaftar. Silakan registrasi terlebih dahulu.']);
        }

        // Verifikasi password
        if (!Hash::check($request->password, $mahasiswa->password)) {
            return back()
                ->withInput()
                ->withErrors(['password' => 'Password yang Anda masukkan salah.']);
        }

        // Simpan data ke session
        Session::put('mahasiswa', [
            'id' => $mahasiswa->id,
            'nama' => $mahasiswa->nama,
            'nim' => $mahasiswa->nim,
            'email' => $mahasiswa->email,
            'foto_profil' => $mahasiswa->foto_profil,
            'dosen_id' => $mahasiswa->dosen_id,
            'semester' => $mahasiswa->semester_saat_ini,
            'nomor_semester' => $mahasiswa->nomor_semester,
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