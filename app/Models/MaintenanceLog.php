<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'equipment_id', 'issue_description', 'cost', 'technician_name', 'repair_date'
    ];
    protected $casts = [
        'repair_date' => 'date',
    ];
    
    public function equipment() 
    {
        return $this->belongsTo(Equipment::class);
    }
}