<?php

namespace Database\Factories;

use App\Models\Matakuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatakuliahFactory extends Factory
{
    protected $model = Matakuliah::class;

    public function definition(): array
    {
        return [
            'kode_mk' => $this->faker->unique()->bothify('MK###'),
            'nama_mk' => $this->faker->sentence(3),
            'sks' => $this->faker->numberBetween(2, 4),
            'semester' => $this->faker->randomElement(['Ganjil', 'Genap']),
        ];
    }
}