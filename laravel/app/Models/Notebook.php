<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notebook extends Model
{
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
        'user_id' // Add this if you want to track notebook ownership
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function processor()
    {
        return $this->belongsTo(Processor::class, 'processorid');
    }
    
    public function operatingSystem()
    {
        return $this->belongsTo(OperatingSystem::class, 'opsystemid');
    }
}