<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');          // Nama Mahasiswa
            $table->string('nim')->unique();         // NIM (unik)
            $table->integer('semester');             // Semester
            $table->text('courses_list');            // Daftar Mata Kuliah (textarea, pakai koma)
            $table->integer('total_credits');        // Total SKS
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status Persetujuan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs_submissions');
    }
};