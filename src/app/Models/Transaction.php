<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = "transaction";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'transactionId');
    }

    public function deleted_tickets()
    {
        return $this->hasMany(DeletedTicket::class, 'transactionId');
    }
}
