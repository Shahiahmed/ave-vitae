<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function view(User $user, Patient $patient): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function update(User $user, Patient $patient): bool
    {
        return $user->hasAnyRole([Role::Admin->value, Role::Operator->value]);
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function restore(User $user, Patient $patient): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function forceDelete(User $user, Patient $patient): bool
    {
        return $user->hasRole(Role::Admin->value);
    }
}
