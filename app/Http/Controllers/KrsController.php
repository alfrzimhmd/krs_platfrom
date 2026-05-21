<?php

namespace App\Http\Controllers;

use App\Models\KrsSubmission;
use Illuminate\Http\Request;

class KrsController extends Controller
{
    // READ: Tampilkan semua data
    public function index()
    {
        $submissions = KrsSubmission::latest()->get();
        return view('krs.index', compact('submissions'));
    }

    // CREATE: Tampilkan form tambah data
    public function create()
    {
        return view('krs.create');
    }

    // CREATE: Simpan data baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:krs_submissions,nim',
            'semester' => 'required|integer|min:1|max:14',
            'courses_list' => 'required|string',
            'total_credits' => 'required|integer|min:1',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        // Simpan ke database
        KrsSubmission::create($validated);

        // Flash message sukses
        return redirect()->route('krs.index')->with('success', 'Data KRS berhasil ditambahkan!');
    }

    // UPDATE: Tampilkan form edit
    public function edit($id)
    {
        $submission = KrsSubmission::findOrFail($id);
        return view('krs.edit', compact('submission'));
    }

    // UPDATE: Proses update data
    public function update(Request $request, $id)
    {
        $submission = KrsSubmission::findOrFail($id);

        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:krs_submissions,nim,' . $id,
            'semester' => 'required|integer|min:1|max:14',
            'courses_list' => 'required|string',
            'total_credits' => 'required|integer|min:1',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $submission->update($validated);

        return redirect()->route('krs.index')->with('success', 'Data KRS berhasil diupdate!');
    }

    // DELETE: Hapus data
    public function destroy($id)
    {
        $submission = KrsSubmission::findOrFail($id);
        $submission->delete();

        return redirect()->route('krs.index')->with('success', 'Data KRS berhasil dihapus!');
    }
}