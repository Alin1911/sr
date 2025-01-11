<?php

namespace App\Http\Controllers;

use App\Models\DeletedTicket;
use App\Models\Performance;
use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function buy($id){

        $performance = Performance::findOrFail($id);

        $user = Auth::user();
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->save();

        $ticket = new Ticket();
        $ticket->transactionId = $transaction->id;
        $ticket->performanceId = $performance->id;
        $ticket->save();
        $user->clearUserEventCache();
        $user->updateScore();

        return redirect("/home");

    }

    public function cart($id){
        $performance = Performance::findOrFail($id);

        $user = Auth::user();
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->save();

        $ticket = new DeletedTicket();
        $ticket->transactionId = $transaction->id;
        $ticket->performanceId = $performance->id;
        $ticket->save();
        $user->clearUserEventCache();
        $user->updateScore();

        return redirect("/home");
    }
}
