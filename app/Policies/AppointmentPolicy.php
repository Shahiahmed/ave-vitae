<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    /**
     * Смена статуса визита (страница «Сегодня»): админ, оператор, ресепшн.
     */
    public function updateVisitStatus(User $user, Appointment $appointment): bool
    {
        return $user->hasAnyRole([
            Role::Admin->value,
            Role::Operator->value,
            Role::Reception->value,
        ]);
    }

    /**
     * Завершение приёма и смена treatment_status: админ либо врач своей записи.
     */
    public function complete(User $user, Appointment $appointment): bool
    {
        if ($user->hasRole(Role::Admin->value)) {
            return true;
        }

        return $user->hasRole(Role::Doctor->value)
            && $appointment->doctor_id === $user->id;
    }
}
