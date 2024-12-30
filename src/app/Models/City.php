<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    
    public function halls()
    {
        return $this->hasMany(Hall::class, 'city_id', 'id');
    }
}
