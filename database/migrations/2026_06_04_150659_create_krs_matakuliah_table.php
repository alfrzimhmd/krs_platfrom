<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained()->onDelete('cascade');
            $table->foreignId('matakuliah_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs_matakuliah');
    }
};