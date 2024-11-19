<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notebook extends Model
{
    protected $table = 'notebooks';

    protected $fillable = [
        'manufacturer', 
        'type', 
        'display', 
        'memory', 
        'harddisk', 
        'videocontroller', 
        'price', 
        'processorid', 
        'opsystemid', 
        'pieces', 
        'user_id'  // Add this to allow mass assignment
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'System',
            'role' => 'system'
        ]);
    }

    public function processor()
    {
        return $this->belongsTo(Processor::class, 'processorid');
    }

    public function operatingSystem()
    {
        return $this->belongsTo(OperatingSystem::class, 'opsystemid');
    }

    // Scope to find notebooks without a user
    public function scopeSystemNotebooks($query)
    {
        return $query->whereNull('user_id');
    }
}