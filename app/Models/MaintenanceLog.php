<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'equipment_id', 'issue_description', 'cost', 'status', 'repair_date'
    ];
    protected $casts = [
        'repair_date' => 'date',
    ];
    protected $attributes = [
        'status' => 'Pending',  
    ];
    
    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }
}