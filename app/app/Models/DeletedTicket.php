<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeletedTicket extends Model
{
    protected $table = 'deleted_ticket';
    public function performance()
    {
        return $this->hasOne(Performance::class, 'id', 'performanceId');
    }
    
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id', 'transactionId');
    }
}
