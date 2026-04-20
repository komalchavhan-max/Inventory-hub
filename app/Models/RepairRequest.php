<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairRequest extends Model
{
    protected $table = 'repair_requests';
    protected $fillable = [
        'user_id',              
        'equipment_id',         
        'issue_description',   
        'urgency',             
        'location',             
        'photos_available',    
        'status',               
        'admin_notes',
        'admin_message',          
        'request_date',         
        'completion_date'       
    ];
    protected $casts = [
        'photos_available' => 'boolean',
        'request_date' => 'datetime',
        'completion_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo{
        return $this->belongsTo(Equipment::class);
    }

    public function isUrgent(): bool{
        return $this->urgency === 'Critical' || $this->urgency === 'High';
    }

    public function isPending(): bool{
        return $this->status === 'Pending';
    }

    public function approve(): void {
        $this->status = 'Approved';
        $this->save();
        
        $equipment = $this->equipment;  
        $equipment->status = 'In-Repair';
        $equipment->assigned_to = null;
        $equipment->save();
    }

    public function complete(): void {
        $this->status = 'Completed';
        $this->completion_date = now();
        $this->save();
        
        $equipment = $this->equipment; 
        $equipment->status = 'Available';
        $equipment->save();
        
        MaintenanceLog::create([  
            'equipment_id' => $this->equipment_id,
            'issue_description' => $this->issue_description,
            'cost' => 0,
            'technician_name' => 'Pending',
            'repair_date' => now()
        ]);
    }
    
    public function reject(string $reason = null): void{
        $this->status = 'Rejected';
        $this->admin_notes = $reason;
        $this->save();
    }
    
    public function getUrgencyColor(): string{
        return match($this->urgency) {
            'Critical' => 'danger',
            'High' => 'warning',
            'Medium' => 'info',
            'Low' => 'success',
            default => 'secondary'
        };
    }

    public function getStatusColor(): string{
        return match($this->status) {
            'Pending' => 'warning',
            'In-Review' => 'info',
            'Approved' => 'primary',
            'Completed' => 'success',
            'Rejected' => 'danger',
            default => 'secondary'
        };
    }
}