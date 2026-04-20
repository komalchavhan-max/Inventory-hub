<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRequest extends Model
{
    protected $table = 'exchange_requests';
    protected $fillable = [
        'user_id',                  
        'old_equipment_id',         
        'requested_equipment_id',   
        'exchange_reason',         
        'old_equipment_condition',  
        'has_damage',               
        'damage_description',     
        'status',                   
        'request_date',             
        'admin_approval_date',
        'admin_message'            
    ];
    protected $casts = [
        'has_damage' => 'boolean',
        'request_date' => 'datetime',
        'admin_approval_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function oldEquipment(): BelongsTo{
        return $this->belongsTo(Equipment::class, 'old_equipment_id');
    }

    public function requestedEquipment(): BelongsTo{
        return $this->belongsTo(Equipment::class, 'requested_equipment_id');
    }

    public function hasDamage(): bool{
        return $this->has_damage;
    }

    public function isPending(): bool{
        return $this->status === 'Pending';
    }

    public function approve(): void{
        $this->status = 'Approved';
        $this->admin_approval_date = now();
        $this->save();
        $this->processExchange(); 
    }

    public function processExchange(): void  { 
        $oldEquipment = $this->oldEquipment;  
        $oldEquipment->assigned_to = null;
        $oldEquipment->status = 'Available';
        $oldEquipment->save();
        $newEquipment = $this->requestedEquipment; 
        $newEquipment->assigned_to = $this->user_id;
        $newEquipment->status = 'Assigned';
        $newEquipment->save();
        $this->status = 'Completed'; 
        $this->save();
    }

    public function reject(string $reason = null): void  {
        $this->status = 'Rejected';
        $this->save();
    }

    public function getStatusColor(): string  {
        return match($this->status) {
            'Pending' => 'warning',
            'Approved' => 'info',
            'Rejected' => 'danger',
            'Completed' => 'success',
            default => 'secondary'
        };
    }
}