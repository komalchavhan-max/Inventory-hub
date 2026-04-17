<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = ['name', 'email', 'password', 'role_id'];
    protected $hidden = ['password', 'remember_token',];
    protected $casts = ['email_verified_at' => 'datetime',];
    
    public function role(){
        return $this->belongsTo(Role::class);
    }
    
    public function isAdmin(){                         // Check if user is admin
        return $this->role && $this->role->name === 'admin';
    }
    
    public function isEmployee(){                      // Check if user is employee
        return $this->role && $this->role->name === 'employee';
    }
    
    public function getRoleName(){                     // Get user role name
        return $this->role ? $this->role->display_name : 'No Role';
    }
    
    public function assignedEquipment(){              // Equipment assigned to user
        return $this->hasMany(Equipment::class, 'assigned_to');
    }
    
    public function equipmentRequests(){               // Equipment requests by user
        return $this->hasMany(EquipmentRequest::class);
    }
    
    public function exchangeRequests(){                  // Exchange requests by user
        return $this->hasMany(ExchangeRequest::class);
    }
    
    public function repairRequests(){                     // Repair requests by user
        return $this->hasMany(RepairRequest::class);
    }
    
    public function returnRequests(){                    // Return requests by user
        return $this->hasMany(ReturnRequest::class);
    }
    
    public function notifications(){                     // Notifications for user
        return $this->hasMany(Notification::class);
    }
}