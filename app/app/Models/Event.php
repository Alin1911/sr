<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function performances()
    {
        return $this->hasMany(Performance::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
