<?php

namespace App\Helpers;

use App\Models\Equipment;
use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function canExchangeEquipment($equipmentId): bool{
        $equipment = Equipment::find($equipmentId);
        return $equipment && $equipment->assigned_to === Auth::id();
    }
    
    public static function canReturnEquipment($equipmentId): bool{
        $equipment = Equipment::find($equipmentId);
        return $equipment && $equipment->assigned_to === Auth::id();
    }
    
    public static function canReportRepair($equipmentId): bool{
        $equipment = Equipment::find($equipmentId);
        return $equipment && $equipment->assigned_to === Auth::id();
    }
}