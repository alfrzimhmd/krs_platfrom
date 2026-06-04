<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Krs extends Model
{
    protected $fillable = ['mahasiswa_id', 'semester', 'total_sks', 'status', 'bukti_ukt_path'];

    protected $casts = [
        'total_sks' => 'integer',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function matakuliahs(): BelongsToMany
    {
        return $this->belongsToMany(Matakuliah::class, 'krs_matakuliah')->withTimestamps();
    }

    public function updateTotalSks()
    {
        $total = $this->matakuliahs()->sum('sks');
        $this->update(['total_sks' => $total]);
    }

    public function isEditable()
    {
        return $this->status === 'menunggu';
    }
}