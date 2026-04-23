<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EquipmentRequest;
use App\Models\ExchangeRequest;
use App\Models\RepairRequest;
use App\Models\ReturnRequest;

class RequestPolicy
{
    public function isOwnRequest(User $user, $request): bool{
        return $request->user_id === $user->id;
    }
 
    public function before(User $user, string $ability): bool|null{
        if ($user->isAdmin()){
            return true;
        }
        return null;
    }
}
