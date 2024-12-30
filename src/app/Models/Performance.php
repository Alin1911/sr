<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $table = 'performance';

    protected $casts = [
        'starting_date' => 'datetime',
        'ending_date' => 'datetime'
    ];

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }

    public function isActive()
    {
        if($this->ending_date->gt(now())){
            return true;
        }
        return false;
    }
    

    public function scopeActive($query)
    {
        return $query->where('ending_date', '>', date('Y-m-d H:i:s'))
        ->where('starting_date', '<', now()->addMonth(2));
    }
}
