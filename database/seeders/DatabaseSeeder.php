<?php

namespace Database\Seeders;

use App\Enums\PatientCategory;
use App\Enums\Role;
use App\Enums\VisitStatus;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@clinic.kz'],
            ['name' => 'Администратор', 'password' => Hash::make('password')],
        );
        $admin->syncRoles([Role::Admin->value]);

        if (! app()->environment('local')) {
            return;
        }

        $this->seedLocalData();
    }

    private function seedLocalData(): void
    {
        $operator = User::firstOrCreate(
            ['email' => 'operator@clinic.kz'],
            ['name' => 'Оператор Оператова', 'password' => Hash::make('password')],
        );
        $operator->syncRoles([Role::Operator->value]);

        $reception = User::firstOrCreate(
            ['email' => 'reception@clinic.kz'],
            ['name' => 'Ресепшн Ресепшнова', 'password' => Hash::make('password')],
        );
        $reception->syncRoles([Role::Reception->value]);

        $lor = Department::where('name_ru', 'ЛОР')->first();
        $prokto = Department::where('name_ru', 'Проктология')->first();

        $doctor = User::firstOrCreate(
            ['email' => 'doctor@clinic.kz'],
            [
                'name' => 'Дәрігер Дәрігеров',
                'name_kk' => 'Дәрігер Дәрігеров',
                'department_id' => $lor?->id,
                'password' => Hash::make('password'),
            ],
        );
        $doctor->syncRoles([Role::Doctor->value]);

        $patients = [
            ['name_kk' => 'Айгүл Нұрланова', 'name_zh' => '艾古丽', 'iin' => '900101400123', 'phone' => '+7 (701) 111-2233', 'city' => 'Алматы', 'category' => PatientCategory::Regular->value],
            ['name_kk' => 'Бақыт Серіков', 'name_zh' => '巴克特', 'iin' => '850615300456', 'phone' => '+7 (702) 222-3344', 'city' => 'Алматы', 'category' => PatientCategory::Vip->value],
            ['name_kk' => 'Дәулет Қасымов', 'name_zh' => null, 'iin' => '780320500789', 'phone' => '+7 (705) 333-4455', 'city' => 'Астана', 'category' => PatientCategory::Regular->value],
        ];

        foreach ($patients as $data) {
            $patient = Patient::firstOrCreate(['iin' => $data['iin']], $data);

            Appointment::firstOrCreate([
                'patient_id' => $patient->id,
                'scheduled_at' => now()->setTime(10, 0)->addHours($patient->id),
            ], [
                'department_id' => $lor?->id ?? $prokto?->id,
                'doctor_id' => $doctor->id,
                'visit_status' => VisitStatus::Waiting->value,
                'created_by' => $operator->id,
            ]);
        }
    }
}
