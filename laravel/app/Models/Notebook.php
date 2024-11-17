<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notebook extends Model
{
    protected $table = 'notebooks';
    protected $fillable = [
        'manufacturer', 'type', 'display', 'memory', 
        'harddisk', 'videocontroller', 'price', 
        'processorid', 'opsystemid', 'pieces'
    ];

    public function processor()
    {
        return $this->belongsTo(Processor::class, 'processorid');
    }

    public function operatingSystem()
    {
        return $this->belongsTo(OperatingSystem::class, 'opsystemid');
    }
}