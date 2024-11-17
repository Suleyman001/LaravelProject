<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatingSystem extends Model
{
    protected $table = 'operating_systems';
    protected $fillable = ['name'];

    public function notebooks()
    {
        return $this->hasMany(Notebook::class, 'opsystemid');
    }
}