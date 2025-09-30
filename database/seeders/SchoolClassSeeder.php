<?php
// Seeder: insère quelques classes d'école de base.
namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
                     ['name' => '1A', 'level' => 'Primary'],
                     ['name' => '2B', 'level' => 'Primary'],
                     ['name' => '3C', 'level' => 'Middle'],
                     ['name' => 'Terminale S', 'level' => 'Secondary'],
                 ] as $c) {
            SchoolClass::updateOrCreate(['name' => $c['name']], ['level' => $c['level']]);
        }
    }
}
