<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Hall;
use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\Performance;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user = Auth::user();
        $cityId = $user->city_id;
        $categoryId = $user->category_id;

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
            ->load('event', 'hall');

        $popularPromoters = Promoter::getPopularPromoters($cityId, $categoryId);

        $promoterIds = $popularPromoters->pluck('id')->toArray();
        $popularPerformancesByPromoter = Performance::active()
            ->whereIn('promoter_id', $promoterIds)
            ->orderByRaw("FIELD(promoter_id, " . implode(',', $promoterIds) . ")")
            ->limit(20)
            ->get()
            ->load('event', 'promoter');

        return view('home', compact('popularEvents', 'popularPerformancesByHall', 'popularPromoters', 'popularPerformancesByPromoter'));
        return view('home');
    }
}
