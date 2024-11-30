<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    protected $table = 'hall';

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }
}
