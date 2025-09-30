<?php
// Seeder: crée les rôles/permissions (admin/teacher/student) et quelques utilisateurs.
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'teacher',
            'student',
        ];
        foreach ($roles as $r) {
            Role::findOrCreate($r);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'profile' => ['role' => 'admin']]
        );
        $admin->assignRole('admin');

        // Quelques enseignants
        foreach (['teacher1@example.com','teacher2@example.com'] as $i => $email) {
            $u = User::updateOrCreate(
                ['email' => $email],
                ['name' => 'Teacher '.($i+1), 'password' => Hash::make('password'), 'profile' => ['role' => 'teacher']]
            );
            $u->assignRole('teacher');
        }

        // Un étudiant test
        $student = User::updateOrCreate(
            ['email' => 'student1@example.com'],
            ['name' => 'Student 1', 'password' => Hash::make('password'), 'profile' => ['role' => 'student']]
        );
        $student->assignRole('student');
    }
}
