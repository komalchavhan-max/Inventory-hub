<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    protected $table = 'return_requests';
    protected $fillable = [
        'user_id',             
        'equipment_id',       
        'return_reason',       
        'equipment_condition', 
        'missing_parts',        
        'return_date',        
        'status',               
        'admin_verified',       
        'admin_notes'           
    ];
    protected $casts = [
        'admin_verified' => 'boolean',
        'return_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    public function approve(): void
    {
        $this->status = 'Approved';
        $this->save();
    }

    public function complete(): void
    {
        $this->status = 'Completed';
        $this->admin_verified = true;
        $this->save();
        

        $equipment = $this->equipment;
        $equipment->assigned_to = null;
        $equipment->status = 'Available';
        $equipment->save();
    }

    public function reject(string $reason = null): void
    {
        $this->status = 'Rejected';
        $this->admin_notes = $reason;
        $this->save();
    }

    public function getReturnReasonLabel(): string
    {
        return match($this->return_reason) {
            'Leaving Company' => '📤 Leaving Company',
            'Exchange' => '🔄 Exchange',
            'Broken' => '🔧 Broken',
            'Upgrade' => '⬆️ Upgrade',
            'Other' => '❓ Other',
            default => $this->return_reason
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'Pending' => 'warning',
            'Approved' => 'info',
            'Completed' => 'success',
            'Rejected' => 'danger',
            default => 'secondary'
        };
    }
}