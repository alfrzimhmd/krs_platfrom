<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $fillable = ['nama', 'nim', 'semester_saat_ini', 'nomor_semester', 'dosen_id'];

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
}