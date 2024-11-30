<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Hall;
use App\Models\Performance;
use App\Models\Ticket;
use App\Models\Transaction;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        // $performances = Performance::all();
        // foreach ($performances as $performance) {
        //     $startingDate = Carbon::parse($performance->starting_date);
        //     $endingDate = Carbon::parse($performance->ending_date);
        //     // Adaugă 1 an și 6 luni la fiecare dată
        //     $startingDate->addYear()->addMonths(6);
        //     $endingDate->addYear()->addMonths(6);
    
        //     // Actualizează datele în model
        //     $performance->starting_date = $startingDate;
        //     $performance->ending_date = $endingDate;
    
        //     // Salvează modificările în baza de date
        //     $performance->save();

        // }
        $performance = Performance::active()->get();
        $events = Event::whereIn('id', $performance->pluck('event_id'))->paginate(10);
        return view('events.index')->with('events', $events);
    }
}
