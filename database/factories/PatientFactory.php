<?php

namespace Database\Factories;

use App\Enums\PatientCategory;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'name_kk' => fake()->name(),
            'name_zh' => null,
            'iin' => fake()->unique()->numerify('############'),
            'phone' => fake()->numerify('+7 (7##) ###-####'),
            'birth_date' => fake()->date(),
            'city' => fake()->city(),
            'category' => PatientCategory::Regular->value,
            'notes' => null,
        ];
    }

    public function vip(): static
    {
        return $this->state(fn () => ['category' => PatientCategory::Vip->value]);
    }
}
