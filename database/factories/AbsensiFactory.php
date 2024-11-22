<?php

namespace Database\Factories;

use App\Models\Absensi;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AbsensiFactory extends Factory
{
    protected $model = Absensi::class;

    public function definition()
    {
        return [
            'classroom_id' => Classroom::factory(), // Relasi ke classroom
            'subject_id' => Subject::factory(), // Relasi ke subject
            'user_id' => User::factory(), // Menambahkan relasi ke user (dosen)
            'date' => $this->faker->date(), // Tanggal kehadiran
            'start_time' => $this->faker->time(), // Waktu mulai
            'end_time' => $this->faker->time(), // Waktu selesai
            'attendance_status' => $this->faker->randomElement(['pending', 'hadir', 'tidak hadir', 'izin']), // Status absensi
        ];
    }
}
