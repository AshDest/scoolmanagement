<?php
// Seeder: insère des cours de base.
namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['code' => 'MAT101', 'title' => 'Mathématiques 1', 'credits' => 4],
            ['code' => 'PHY101', 'title' => 'Physique 1', 'credits' => 3],
            ['code' => 'HIS101', 'title' => 'Histoire 1', 'credits' => 2],
        ];
        foreach ($courses as $c) {
            Course::updateOrCreate(['code' => $c['code']], $c);
        }
    }
}
