<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    protected $table = 'processors';
    protected $fillable = ['manufacturer', 'type'];

    public function notebooks()
    {
        return $this->hasMany(Notebook::class, 'processorid');
    }
}