<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name_ru' => 'ЛОР', 'name_kk' => 'ЛОР', 'name_zh' => '耳鼻喉科'],
            ['name_ru' => 'Проктология', 'name_kk' => 'Проктология', 'name_zh' => '肛肠科'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name_ru' => $department['name_ru']],
                [
                    'name_kk' => $department['name_kk'],
                    'name_zh' => $department['name_zh'],
                    'is_active' => true,
                ],
            );
        }
    }
}
