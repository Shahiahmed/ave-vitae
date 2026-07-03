<?php

use App\Enums\Role;
use App\Filament\Resources\Departments\DepartmentResource;
use App\Filament\Resources\Patients\PatientResource;
use App\Filament\Resources\Users\UserResource;

it('lets admin open every management resource', function () {
    $admin = userWithRole(Role::Admin);

    $this->actingAs($admin)->get(PatientResource::getUrl('index'))->assertOk();
    $this->actingAs($admin)->get(PatientResource::getUrl('create'))->assertOk();
    $this->actingAs($admin)->get(UserResource::getUrl('index'))->assertOk();
    $this->actingAs($admin)->get(DepartmentResource::getUrl('index'))->assertOk();
});

it('lets operator manage patients but not users or departments', function () {
    $operator = userWithRole(Role::Operator);

    $this->actingAs($operator)->get(PatientResource::getUrl('index'))->assertOk();
    $this->actingAs($operator)->get(PatientResource::getUrl('create'))->assertOk();
    $this->actingAs($operator)->get(UserResource::getUrl('index'))->assertForbidden();
    $this->actingAs($operator)->get(DepartmentResource::getUrl('index'))->assertForbidden();
});

it('forbids reception and doctor from the patient registry', function () {
    $this->actingAs(userWithRole(Role::Reception))
        ->get(PatientResource::getUrl('index'))->assertForbidden();

    $this->actingAs(userWithRole(Role::Doctor))
        ->get(PatientResource::getUrl('index'))->assertForbidden();
});
