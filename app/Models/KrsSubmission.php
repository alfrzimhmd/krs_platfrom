<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KrsSubmission extends Model
{
    use HasFactory;

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
        'student_name',
        'nim',
        'semester',
        'courses_list',
        'total_credits',
        'status'
    ];
}