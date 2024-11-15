<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassroomFactory extends Factory
{
    protected $model = Classroom::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'batch_year' => $this->faker->year, // Sesuaikan dengan struktur tabel Anda
        ];
    }
}
