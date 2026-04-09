<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Supervisor;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

       // Coordinator
        User::create([
            'username' => 'coordinator',
            'email'    => 'coordinator@internshiptrack.com',
            'password' => Hash::make('password'),
            'role'     => 'coordinator',
            'coordinator_code'=>'COORD-2026',
        ]);
 
        // Academic supervisor
        $acUser = User::create([
            'username' => 'academic_sup',
            'email'    => 'academic@s.com',
            'password' => Hash::make('password'),
            'role'     => 'academic_supervisor',
        ]);
        Supervisor::create([
            'user_id'    => $acUser->id,
            'full_name'  => 'Mr. Fouthe Wembe',
            'type'       => 'academic',
            'department' => 'Informatique',
            'phone'      => '+237 6 00 00 00 01',
        ]);
 
        // Professional supervisor
        $proUser = User::create([
            'username' => 'professional_sup',
            'email'    => 'pro@internshiptrack.com',
            'password' => Hash::make('password'),
            'role'     => 'professional_supervisor',
        ]);
        Supervisor::create([
            'user_id' => $proUser->id,
            'full_name' => 'Mr. Lando Saa',
            'type'      => 'professional',
            'company'   => 'VISIBILITY_CAM SARL',
            'phone'     => '+237 6 00 00 00 02',
        ]);
 
        // Student
        $stUser = User::create([
            'username' => 'Lemogo',
            'email'    => 'Lemogo@internshiptrack.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);
        Student::create([
            'user_id'        => $stUser->id,
            'full_name'      => 'Lemogo tervel',
            'student_number' => 'INF2024001',
            'program'        => 'software engineering',
            'level'          => 'l1',
            'phone'          => '+237 6 99 99 99 01',
        ]);
    }
}

