<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function view(User $user, Department $department): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function update(User $user, Department $department): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function delete(User $user, Department $department): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function restore(User $user, Department $department): bool
    {
        return $user->hasRole(Role::Admin->value);
    }

    public function forceDelete(User $user, Department $department): bool
    {
        return $user->hasRole(Role::Admin->value);
    }
}
