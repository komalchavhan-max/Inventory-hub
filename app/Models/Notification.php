<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',      
        'type',         
        'request_id',  
        'message',      
        'is_read',      
        'status'        
    ];
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function request(){
        return match($this->type) {
            'equipment_request' => $this->belongsTo(EquipmentRequest::class, 'request_id'),
            'exchange_request' => $this->belongsTo(ExchangeRequest::class, 'request_id'),
            'repair_request' => $this->belongsTo(RepairRequest::class, 'request_id'),
            'return_request' => $this->belongsTo(ReturnRequest::class, 'request_id'),
            default => null
        };
    }
    
    public function markAsRead(): void{
        $this->is_read = true;
        $this->save();
    }

    public function markAsUnread(): void{
        $this->is_read = false;
        $this->save();
    }
 
    public function isRead(): bool{
        return $this->is_read;
    }
 
    public static function createNotification($userId, $type, $requestId, $message, $status){
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'request_id' => $requestId,
            'message' => $message,
            'status' => $status,
            'is_read' => false
        ]);
    }
 
    public static function getUnreadForUser($userId){
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getAllForUser($userId, $limit = 20){
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function scopeUnread($query){
        return $query->where('is_read', false);
    }
}