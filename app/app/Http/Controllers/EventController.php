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
use App\Models\Promoter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $cityId = $request->input('city_id');
        $categoryId = $request->input('category_id');

        $eventsCacheKey = "popular_events_{$cityId}_{$categoryId}";
        $popularEvents = Cache::remember($eventsCacheKey, 60, function () use ($cityId, $categoryId) {
            $eventsQuery = Event::getPopularEvents($cityId, $categoryId);
            return $eventsQuery->limit(20)->get()->load('category');
        });

        $hallsCacheKey = "popular_halls_{$cityId}_{$categoryId}";
        $popularHalls = Cache::remember($hallsCacheKey, 60, function () use ($cityId, $categoryId) {
            return Hall::getPopularHalls($cityId, $categoryId);
        });

        $hallIds = $popularHalls->pluck('id')->toArray();

        $performancesByHallCacheKey = "popular_performances_by_hall_" . md5(implode('_', $hallIds));
        $popularPerformancesByHall = Cache::remember($performancesByHallCacheKey, 60, function () use ($hallIds) {
            return Performance::active()
                ->whereIn('hall_id', $hallIds)
                ->orderByRaw("FIELD(hall_id, " . implode(',', $hallIds) . ")")
                ->limit(20)
                ->get()
                ->load('event', 'hall');
        });

        $promotersCacheKey = "popular_promoters_{$cityId}_{$categoryId}";
        $popularPromoters = Cache::remember($promotersCacheKey, 60, function () use ($cityId, $categoryId) {
            return Promoter::getPopularPromoters($cityId, $categoryId);
        });

        $promoterIds = $popularPromoters->pluck('id')->toArray();

        $performancesByPromoterCacheKey = "popular_performances_by_promoter_" . md5(implode('_', $promoterIds));
        $popularPerformancesByPromoter = Cache::remember($performancesByPromoterCacheKey, 60, function () use ($promoterIds) {
            return Performance::active()
                ->whereIn('promoter_id', $promoterIds)
                ->orderByRaw("FIELD(promoter_id, " . implode(',', $promoterIds) . ")")
                ->limit(20)
                ->get()
                ->load('event', 'promoter');
        });

        return view('events.index', compact('popularEvents', 'popularPerformancesByHall', 'popularPromoters', 'popularPerformancesByPromoter'));
    }

    public function search(Request $request)
    {
        $term = $request->get('search', "");
        $events = [];

        if (!empty($term)) {
            $searchCacheKey = "search_results_" . md5($term);
            $events = Cache::remember($searchCacheKey, 60, function () use ($term) {
                return Event::active()->where('title', 'like', "%$term%")->get();
            });
        }

        return view('events.search')->with('term', $term)->with('events', $events);
    }
}
