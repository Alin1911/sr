<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $table = 'performance';

    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeActive($query)
    {
        return $query->where('ending_date', '>', date('Y-m-d H:i:s'));
    }
}
