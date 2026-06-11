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
            'nama' => 'required|string|max:255',
            'nim'  => 'required|string|max:50',
            'semester' => 'required|in:Ganjil,Genap',
            'nomor_semester' => ['required', 'integer', 'min:1', 'max:14', function ($attribute, $value, $fail) use ($request) {
                if ($request->semester === 'Ganjil' && $value % 2 === 0) {
                    $fail('Semester Ganjil hanya bisa memilih nomor semester ganjil (1, 3, 5, 7, 9, 11, 13).');
                }
                if ($request->semester === 'Genap' && $value % 2 !== 0) {
                    $fail('Semester Genap hanya bisa memilih nomor semester genap (2, 4, 6, 8, 10, 12, 14).');
                }
            }],
        ]);

        // ── Cari mahasiswa berdasarkan NIM (NIM = kunci unik) ──────────────────
        $mahasiswaByNim = Mahasiswa::where('nim', $request->nim)->first();

        if ($mahasiswaByNim) {
            // NIM sudah terdaftar — pastikan nama cocok (case-insensitive)
            if (strtolower(trim($mahasiswaByNim->nama)) !== strtolower(trim($request->nama))) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'nim' => 'NIM ' . $request->nim . ' sudah terdaftar dan tidak dapat digunakan. '
                               . 'Jika ini NIM Anda, pastikan nama yang dimasukkan sesuai data yang sudah terdaftar.'
                    ]);
            }

            // NIM & nama cocok → update HANYA info semester, tidak ada field lain yang berubah
            $mahasiswaByNim->update([
                'semester_saat_ini' => $request->semester,
                'nomor_semester'    => (int) $request->nomor_semester,
            ]);

            $mahasiswa = $mahasiswaByNim;

        } else {
            // ── NIM belum terdaftar → buat record baru ──────────────────────────
            $mahasiswa = Mahasiswa::create([
                'nama'             => $request->nama,
                'nim'              => $request->nim,
                'semester_saat_ini'=> $request->semester,
                'nomor_semester'   => (int) $request->nomor_semester,
                'dosen_id'         => null,
            ]);
        }

        // Simpan data ke session
        Session::put('mahasiswa', [
            'id'              => $mahasiswa->id,
            'nama'            => $mahasiswa->nama,
            'nim'             => $mahasiswa->nim,
            'semester'        => $request->semester,
            'nomor_semester'  => (int) $request->nomor_semester,
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