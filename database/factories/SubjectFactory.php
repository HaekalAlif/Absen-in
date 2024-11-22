<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word, // Nama mata kuliah
            'classroom_id' => Classroom::factory(), // Relasi ke classroom
            'user_id' => User::factory(), // Relasi ke user (dosen)
            'semester' => $this->faker->numberBetween(1, 6), // Semester, antara 1 hingga 8
        ];
    }
}
