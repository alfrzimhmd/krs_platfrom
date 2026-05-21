<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KrsSubmission;

class KrsSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 15 data dummy
        KrsSubmission::factory()->count(15)->create();
    }
}