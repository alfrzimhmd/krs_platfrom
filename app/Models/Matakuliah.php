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
}