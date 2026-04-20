<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentRequest extends Model
{
    protected $table = 'equipment_requests';
    protected $fillable = [
        'user_id',           
        'equipment_id',     
        'request_date',      
        'priority',        
        'status',            
        'request_reason',    
        'admin_notes',
        'admin_message',       
        'approved_date'     
    ];
    protected $casts = [
        'request_date' => 'datetime',
        'approved_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo{
        return $this->belongsTo(Equipment::class);
    }

    public function isPending(): bool{
        return $this->status === 'Pending';
    }

    public function isApproved(): bool{
        return $this->status === 'Approved';
    }

    public function approve(): void{
        $this->status = 'Approved';
        $this->approved_date = now();
        $this->save();
    }

    public function reject(string $notes = null): void{
        $this->status = 'Rejected';
        $this->admin_notes = $notes;
        $this->save();
    }

    public function fulfill(): void{
        $this->status = 'Fulfilled';
        $this->save();
    }

    public function getPriorityColor(): string{
        return match($this->priority) {
            'Urgent' => 'danger',
            'Normal' => 'warning',
            'Low' => 'success',
            default => 'secondary'
        };
    }
    
    public function getStatusColor(): string{
        return match($this->status) {
            'Pending' => 'warning',
            'Approved' => 'info',
            'Rejected' => 'danger',
            'Fulfilled' => 'success',
            default => 'secondary'
        };
    }
}