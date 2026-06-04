<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matakuliah;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class MatakuliahController extends Controller implements HasMiddleware
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
     * Tampilkan daftar semua mata kuliah (per semester)
     */
    public function index()
    {
        $matakuliahGanjil = Matakuliah::where('semester', 'Ganjil')->orderBy('kode_mk')->get();
        $matakuliahGenap = Matakuliah::where('semester', 'Genap')->orderBy('kode_mk')->get();
        
        return view('matakuliah.index', compact('matakuliahGanjil', 'matakuliahGenap'));
    }

    /**
     * Tampilkan form tambah mata kuliah
     */
    public function create()
    {
        return view('matakuliah.create');
    }

    /**
     * Simpan mata kuliah baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:10|unique:matakuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|in:Ganjil,Genap',
        ]);
        
        Matakuliah::create($request->all());
        
        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit mata kuliah
     */
    public function edit($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        return view('matakuliah.edit', compact('matakuliah'));
    }

    /**
     * Update mata kuliah
     */
    public function update(Request $request, $id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        
        $request->validate([
            'kode_mk' => 'required|string|max:10|unique:matakuliahs,kode_mk,' . $id,
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|in:Ganjil,Genap',
        ]);
        
        $matakuliah->update($request->all());
        
        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil diupdate.');
    }

    /**
     * Hapus mata kuliah
     */
    public function destroy($id)
    {
        $matakuliah = Matakuliah::findOrFail($id);
        
        // Cek apakah mata kuliah sedang digunakan di KRS
        if ($matakuliah->krs()->count() > 0) {
            return redirect()->route('matakuliah.index')
                ->with('error', 'Mata kuliah tidak bisa dihapus karena sudah digunakan di KRS.');
        }
        
        $matakuliah->delete();
        
        return redirect()->route('matakuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}