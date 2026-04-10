<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon'
    ];
    
    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            $category->slug = \Str::slug($category->name);
        });
        
        static::updating(function ($category) {
            $category->slug = \Str::slug($category->name);
        });
    }
}