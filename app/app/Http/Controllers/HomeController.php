<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Hall;
use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\Performance;

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
        $performance = Performance::active()->get();
        $events = Event::whereIn('id', $performance->pluck('event_id'))->get()->sortByDesc('popularity_score')->paginate(10)->load('category');
        $events = $events->sortByDesc('popularity_score');
        dd($events);
        return view('events.index')->with('events', $events);        return view('home');
    }
}
