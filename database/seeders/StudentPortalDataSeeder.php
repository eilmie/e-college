<?php

namespace Database\Seeders;

use App\Models\StudentPortalData;
use Illuminate\Database\Seeder;

class StudentPortalDataSeeder extends Seeder
{
    public function run(): void
    {
        StudentPortalData::updateOrCreate(
            ['student_id' => '2025160255'],
            [
                'full_name' => 'Eilmie',
                'faculty' => 'Faculty of Computer and Mathematical Sciences',
                'gender' => 'male',
                'program_level' => 'degree',
                'current_semester' => 3,
                'cgpa' => 3.75,
            ]
        );
    }
}