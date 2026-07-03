<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name_ru' => 'ЛОР', 'name_kk' => 'ЛОР'],
            ['name_ru' => 'Проктология', 'name_kk' => 'Проктология'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['name_ru' => $department['name_ru']],
                ['name_kk' => $department['name_kk'], 'is_active' => true],
            );
        }
    }
}
