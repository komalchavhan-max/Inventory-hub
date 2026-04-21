<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService{
    /**
     * Send notification to user
     * 
     * @param int $userId
     * @param string $type
     * @param int $requestId
     * @param string $message
     * @param string $status
     * @return void
     */

    public static function send($userId, $type, $requestId, $message, $status){
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'request_id' => $requestId,
            'message' => $message,
            'status' => $status,
            'is_read' => false
        ]);
    }

    public static function equipmentRequest($userId, $requestId, $equipmentName, $action, $reason = null){    //Send equipment request notification
        if ($action === 'approved'){
            $message = "Your equipment request for {$equipmentName} has been approved.";
            $status = 'Approved';
        } else{
            $message = "Your equipment request for {$equipmentName} was rejected. Reason: " . ($reason ?? 'Not specified');
            $status = 'Rejected';
        }
        
        self::send($userId, 'equipment_request', $requestId, $message, $status);
    }

    public static function exchangeRequest($userId, $requestId, $equipmentName, $action, $reason = null){       //Send exchange request notification
        if ($action === 'approved'){
            $message = "Your exchange request for {$equipmentName} has been approved and processed.";
            $status = 'Approved';
        } else{
            $message = "Your exchange request for {$equipmentName} was rejected. Reason: " . ($reason ?? 'Not specified');
            $status = 'Rejected';
        }
        
        self::send($userId, 'exchange_request', $requestId, $message, $status);
    }

    public static function repairRequest($userId, $requestId, $equipmentName, $action, $reason = null){         //Send repair request notification
        if ($action === 'approved'){
            $message = "Your repair request for {$equipmentName} has been approved. Equipment will be repaired soon.";
            $status = 'Approved';
        } elseif ($action === 'completed'){
            $message = "Your equipment repair for {$equipmentName} is complete. You can now request the equipment again.";
            $status = 'Completed';
        } else{
            $message = "Your repair request for {$equipmentName} was rejected. Reason: " . ($reason ?? 'Not specified');
            $status = 'Rejected';
        }
        
        self::send($userId, 'repair_request', $requestId, $message, $status);
    }

    public static function returnRequest($userId, $requestId, $equipmentName, $action, $reason = null){           //Send return request notification
        if ($action === 'approved'){
            $message = "Your return request for {$equipmentName} has been approved. Please return the equipment to admin.";
            $status = 'Approved';
        } elseif ($action === 'completed'){
            $message = "Your equipment return for {$equipmentName} has been completed. Thank you.";
            $status = 'Completed';
        } else{
            $message = "Your return request for {$equipmentName} was rejected. Reason: " . ($reason ?? 'Not specified');
            $status = 'Rejected';
        }
        
        self::send($userId, 'return_request', $requestId, $message, $status);
    }
}