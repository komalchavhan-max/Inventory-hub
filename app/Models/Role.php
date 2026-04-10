<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'display_name', 'description', 'priority'
    ];
    
    // A role has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    // Check if role is admin
    public function isAdmin()
    {
        return $this->name === 'admin';
    }
    
    // Check if role is employee
    public function isEmployee()
    {
        return $this->name === 'employee';
    }
    
    // Check if role is manager
    public function isManager()
    {
        return $this->name === 'manager';
    }
}