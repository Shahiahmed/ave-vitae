<?php
use App\Enums\Role;
use App\Filament\Resources\Patients\Pages\CreatePatient;
use App\Models\Patient;
use Livewire\Livewire;

it('creates a patient with masked phone and 12-digit iin without validation errors', function () {
    $this->actingAs(userWithRole(Role::Admin));

    Livewire::test(CreatePatient::class)
        ->fillForm([
            'name_kk' => 'Тест Пациент',
            'phone' => '+7 (701) 111-2233',
            'iin' => '900101400123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Patient::where('iin', '900101400123')->exists())->toBeTrue();
});
