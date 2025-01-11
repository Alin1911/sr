<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Hall;
use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\Performance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Crează o instanță nouă a controller-ului.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afișează dashboard-ul aplicației.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $cityId = $user->city_id;
        $categoryId = $user->category_id;

        $popularEvents = Cache::remember("popular_events_{$cityId}_{$categoryId}", now()->addMinutes(30), function () use ($cityId, $categoryId) {
            $eventsQuery = Event::getPopularEvents($cityId, $categoryId);
            return $eventsQuery->limit(20)->distinct()->get()->load('category');
        });

        $popularHalls = Cache::remember("popular_halls_{$cityId}_{$categoryId}", now()->addMinutes(30), function () use ($cityId, $categoryId) {
            return Hall::getPopularHalls($cityId, $categoryId);
        });

        $popularPerformancesByHall = [];
        $hallIds = $popularHalls->pluck('id')->toArray();
        if (count($hallIds)) {
            $hallCacheKey = 'popular_performances_by_hall_' . md5(implode('_', $hallIds));
            $popularPerformancesByHall = Cache::remember($hallCacheKey, now()->addMinutes(30), function () use ($hallIds) {
                return Performance::active()
                    ->whereIn('hall_id', $hallIds)
                    ->orderByRaw("FIELD(hall_id, " . implode(',', $hallIds) . ")")
                    ->limit(20)
                    ->distinct()->get()
                    ->load('event', 'hall');
            });
        }

        $popularPromoters = Cache::remember("popular_promoters_{$cityId}_{$categoryId}", now()->addMinutes(30), function () use ($cityId, $categoryId) {
            return Promoter::getPopularPromoters($cityId, $categoryId);
        });

        $popularPerformancesByPromoter = [];
        $promoterIds = $popularPromoters->pluck('id')->toArray();
        if (count($promoterIds)) {
            $promoterCacheKey = 'popular_performances_by_promoter_' . md5(implode('_', $promoterIds));
            $popularPerformancesByPromoter = Cache::remember($promoterCacheKey, now()->addMinutes(30), function () use ($promoterIds) {
                return Performance::active()
                    ->whereIn('promoter_id', $promoterIds)
                    ->orderByRaw("FIELD(promoter_id, " . implode(',', $promoterIds) . ")")
                    ->limit(20)
                    ->distinct()->get()
                    ->load('event', 'promoter');
            });
        }
        $popularByUser = [];
        $alreadyAddedEventIds = [];
        
        if ($user->city_id_e) {
            $cityCacheKey = "popular_events_user_city_{$user->id}";
            $events = Cache::remember($cityCacheKey, now()->addMinutes(30), function () use ($user, $alreadyAddedEventIds) {
                return Event::getPopularEvents($user->city_id_e)
                    ->whereNotIn('events.id', $alreadyAddedEventIds)
                    ->limit(5)
                    ->distinct()
                    ->get()
                    ->toArray();
            });
            $popularByUser = array_merge($popularByUser, $events);
            $alreadyAddedEventIds = array_merge($alreadyAddedEventIds, array_column($events, 'id'));
        }
        
        if ($user->category_id_e) {
            $categoryCacheKey = "popular_events_user_category_{$user->id}";
            $events = Cache::remember($categoryCacheKey, now()->addMinutes(30), function () use ($user, $alreadyAddedEventIds) {
                return Event::getPopularEvents(null, $user->category_id_e)
                    ->whereNotIn('events.id', $alreadyAddedEventIds)
                    ->limit(5)
                    ->distinct()
                    ->get()
                    ->toArray();
            });
            $popularByUser = array_merge($popularByUser, $events);
            $alreadyAddedEventIds = array_merge($alreadyAddedEventIds, array_column($events, 'id'));
        }
        
        if ($user->promoter_id_e) {
            $promoterCacheKey = "popular_events_user_promoter_{$user->id}";
            $events = Cache::remember($promoterCacheKey, now()->addMinutes(30), function () use ($user, $alreadyAddedEventIds) {
                return Event::getPopularEvents(null, null, $user->promoter_id_e)
                    ->whereNotIn('events.id', $alreadyAddedEventIds)
                    ->limit(5)
                    ->distinct()
                    ->get()
                    ->toArray();
            });
            $popularByUser = array_merge($popularByUser, $events);
            $alreadyAddedEventIds = array_merge($alreadyAddedEventIds, array_column($events, 'id'));
        }
        
        if ($user->hall_id_e) {
            $hallCacheKey = "popular_events_user_hall_{$user->id}";
            $events = Cache::remember($hallCacheKey, now()->addMinutes(30), function () use ($user, $alreadyAddedEventIds) {
                return Event::getPopularEvents(null, null, null, $user->hall_id_e)
                    ->whereNotIn('events.id', $alreadyAddedEventIds)
                    ->limit(5)
                    ->distinct()
                    ->get()
                    ->toArray();
            });
            $popularByUser = array_merge($popularByUser, $events);
            $alreadyAddedEventIds = array_merge($alreadyAddedEventIds, array_column($events, 'id'));
        }
        

        return view('home', compact('popularEvents', 'popularPerformancesByHall', 'popularPromoters', 'popularPerformancesByPromoter', 'popularByUser'));
    }
}
