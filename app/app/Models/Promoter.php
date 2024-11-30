<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promoter extends Model
{
    protected $table = 'promoter';
    
    public function performances()
    {
        return $this->hasMany(Performance::class, 'promoter_id');
    }
}
