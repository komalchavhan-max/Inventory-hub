<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name', 'display_name', 'description', 'priority'
    ];
    
    public function users()  // A role has many users
    {
        return $this->hasMany(User::class);
    }
    
    public function isAdmin()  // Check if role is admin
    {
        return $this->name === 'admin';
    }
    
    public function isEmployee()  // Check if role is employee
    {
        return $this->name === 'employee';
    }
}