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

    public function index(Request $request)
    {
        $cityId = $request->input('city_id');
        $categoryId = $request->input('category_id');
        
        $eventsQuery = Event::getPopularEvents($cityId, $categoryId);
        $popularEvents = $eventsQuery->limit(20)->get();
        $popularEvents->load('category');
        
        $popularHalls = Hall::getPopularHalls($cityId, $categoryId);

        $hallIds = $popularHalls->pluck('id')->toArray();

        $popularPerformancesByHall = Performance::active()
            ->whereIn('hall_id', $hallIds)  
            ->orderByRaw("FIELD(hall_id, " . implode(',', $hallIds) . ")")
            ->limit(20)
            ->get()
            ->load('event','hall');

        return view('events.index', compact('popularEvents', 'popularPerformancesByHall'));
    }
}