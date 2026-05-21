<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KrsSubmissionFactory extends Factory
{
    public function definition(): array
    {
        // Contoh mata kuliah
        $courses = [
            'Pemrograman Web', 'Basis Data', 'Jaringan Komputer',
            'Rekayasa Perangkat Lunak', 'Sistem Operasi', 'Matematika Diskrit'
        ];
        
        // Ambil 3-5 mata kuliah acak
        $selected = $this->faker->randomElements($courses, rand(3, 5));
        $coursesString = implode(', ', $selected);
        
        // Hitung total SKS (asumsi 3 SKS per mata kuliah)
        $totalCredits = count($selected) * 3;
        
        return [
            'student_name' => $this->faker->name(),
            'nim' => $this->faker->unique()->numerify('##########'),
            'semester' => $this->faker->numberBetween(1, 8),
            'courses_list' => $coursesString,
            'total_credits' => $totalCredits,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}