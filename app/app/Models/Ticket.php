<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    public function performance()
    {
        return $this->hasOne(Performance::class, 'id', 'performanceId');
    }
    
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id', 'transactionId');
    }
}
