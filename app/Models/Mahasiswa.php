<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nama', 
        'nim', 
        'email', 
        'password', 
        'foto_profil', 
        'semester_saat_ini',
        'nomor_semester', 
        'dosen_id'
    ];

    protected $hidden = [
        'password',
    ];

    // Auto hash password saat set
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class);
    }

    public function hasActiveKrs()
    {
        return $this->krs()->whereIn('status', ['menunggu', 'disetujui'])->exists();
    }

    public function getPendingKrs()
    {
        return $this->krs()->where('status', 'menunggu')->first();
    }

    // Ambil KRS yang sudah disetujui untuk riwayat akademik
    public function getApprovedKrs()
    {
        return $this->krs()->where('status', 'disetujui')->with('matakuliahs')->get();
    }

    // Hitung total SKS yang sudah ditempuh
    public function getTotalSksTempuh()
    {
        return $this->getApprovedKrs()->sum('total_sks');
    }

    // Hitung jumlah mata kuliah yang sudah ditempuh
    public function getTotalMatkulTempuh()
    {
        $total = 0;
        foreach ($this->getApprovedKrs() as $krs) {
            $total += $krs->matakuliahs->count();
        }
        return $total;
    }
}