<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Equipment;

class EquipmentPolicy
{
    public function isAssignedToUser(User $user, Equipment $equipment): bool{
        return $equipment->assigned_to === $user->id;
    }

    public function isAvailable(User $user, Equipment $equipment): bool{
        return $equipment->status === 'Available';
    }

    public function before(User $user, string $ability): bool|null{
        if ($user->isAdmin()){
            return true;
        }
        return null;
    }
}
