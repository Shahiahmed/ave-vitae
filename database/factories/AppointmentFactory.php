<?php

namespace Database\Factories;

use App\Enums\VisitStatus;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'department_id' => Department::factory(),
            'doctor_id' => null,
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 week'),
            'visit_status' => VisitStatus::Waiting->value,
            'treatment_status' => null,
            'notes_kk' => null,
            'notes_zh' => null,
            'created_by' => null,
        ];
    }
}
