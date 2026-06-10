<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->tinyInteger('nomor_semester')->unsigned()->nullable()->after('semester_saat_ini');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn('nomor_semester');
        });
    }
};