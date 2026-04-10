<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipment';
    
    protected $fillable = [
        'name', 'description', 'specifications', 'serial_number',
        'category_id', 'purchase_date', 'warranty_until', 'status',
        'condition', 'assigned_to'
    ];
    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function isAvailable()
    {
        return $this->status === 'Available';
    }
    public function equipmentRequests()
    {
        return $this->hasMany(EquipmentRequest::class);
    }
    public function exchangeRequestsAsOld()
    {
        return $this->hasMany(ExchangeRequest::class, 'old_equipment_id');
    }
    public function exchangeRequestsAsNew()
    {
        return $this->hasMany(ExchangeRequest::class, 'requested_equipment_id');
    }
    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class);
    }
    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}