<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nim', 15)->unique();
            $table->string('email')->unique();  // field baru untuk email mahasiswa 
            $table->string('password');       // field baru untuk password mahasiswa
            $table->string('foto_profil')->nullable(); // field baru untuk menyimpan path foto profil mahasiswa
            $table->enum('semester_saat_ini', ['Ganjil', 'Genap'])->nullable(); // field baru untuk semester saat ini
            $table->integer('nomor_semester')->nullable(); // field baru untuk nomor semester
            $table->foreignId('dosen_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};