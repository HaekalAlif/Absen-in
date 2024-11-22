<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Absensi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat data User
        User::factory(10)->create();

        // Membuat data Classroom
        Classroom::factory(5)->create();

        // Membuat data Subject
        Subject::factory(15)->create();

        // Membuat data Absensi
        Absensi::factory(30)->create();
    }
}
