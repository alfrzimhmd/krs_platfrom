<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Matakuliah extends Model
{
    protected $fillable = ['kode_mk', 'nama_mk', 'sks', 'semester'];

    public function krs(): BelongsToMany
    {
        return $this->belongsToMany(Krs::class, 'krs_matakuliah');
    }

    /**
     * Ambil mata kuliah berdasarkan semester (Ganjil/Genap)
     */
    public static function getMatakuliahBySemester($semester)
    {
        return self::where('semester', $semester)->get();
    }

    /**
     * Ambil mata kuliah berdasarkan nomor semester (1-14)
     * Ganjil -> matakuliah Ganjil, Genap -> matakuliah Genap
     */
    public static function getMatakuliahByNomorSemester($nomorSemester)
    {
        $jenisSemester = ($nomorSemester % 2 == 1) ? 'Ganjil' : 'Genap';
        return self::where('semester', $jenisSemester)->get();
    }
}