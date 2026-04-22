<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Role;

class NotificationHelper
{
    public static function notifyAdmins($type, $requestId, $message, $status = 'Pending'){
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            return; // No admin role found
        }
        $admins = User::where('role_id', $adminRole->id)->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'request_id' => $requestId,
                'message' => $message,
                'status' => $status,
                'is_read' => false
            ]);
        }
    }

    public static function notifyUser($userId, $type, $requestId, $message, $status){
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'request_id' => $requestId,
            'message' => $message,
            'status' => $status,
            'is_read' => false
        ]);
    }

    public static function notifyCurrentAdmin($type, $requestId, $message, $status){
        Notification::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'request_id' => $requestId,
            'message' => $message,
            'status' => $status,
            'is_read' => false
        ]);
    }
}