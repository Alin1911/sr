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

        // Cache pentru evenimente populare
        $popularEvents = Cache::remember("popular_events_{$cityId}_{$categoryId}", now()->addMinutes(30), function () use ($cityId, $categoryId) {
            $eventsQuery = Event::getPopularEvents($cityId, $categoryId);
            return $eventsQuery->limit(20)->get()->load('category');
        });

        // Cache pentru sălile populare
        $popularHalls = Cache::remember("popular_halls_{$cityId}_{$categoryId}", now()->addMinutes(30), function () use ($cityId, $categoryId) {
            return Hall::getPopularHalls($cityId, $categoryId);
        });

        // Cache pentru performanțele populare pe săli
        $hallIds = $popularHalls->pluck('id')->toArray();
        $hallCacheKey = 'popular_performances_by_hall_' . md5(implode('_', $hallIds)); // Utilizăm un hash MD5 pentru a face cheia mai mică
        $popularPerformancesByHall = Cache::remember($hallCacheKey, now()->addMinutes(30), function () use ($hallIds) {
            return Performance::active()
                ->whereIn('hall_id', $hallIds)
                ->orderByRaw("FIELD(hall_id, " . implode(',', $hallIds) . ")")
                ->limit(20)
                ->get()
                ->load('event', 'hall');
        });

        // Cache pentru promotorii populari
        $popularPromoters = Cache::remember("popular_promoters_{$cityId}_{$categoryId}", now()->addMinutes(30), function () use ($cityId, $categoryId) {
            return Promoter::getPopularPromoters($cityId, $categoryId);
        });

        // Cache pentru performanțele populare pe promotori
        $promoterIds = $popularPromoters->pluck('id')->toArray();
        $promoterCacheKey = 'popular_performances_by_promoter_' . md5(implode('_', $promoterIds)); // Utilizăm un hash MD5 pentru a face cheia mai mică
        $popularPerformancesByPromoter = Cache::remember($promoterCacheKey, now()->addMinutes(30), function () use ($promoterIds) {
            return Performance::active()
                ->whereIn('promoter_id', $promoterIds)
                ->orderByRaw("FIELD(promoter_id, " . implode(',', $promoterIds) . ")")
                ->limit(20)
                ->get()
                ->load('event', 'promoter');
        });

        // Cache pentru evenimente populare bazate pe preferințele utilizatorului
        $popularByUser = [];
        if ($user->city_id_e) {
            $cityCacheKey = "popular_events_user_city_{$user->id}";
            $popularByUser = array_merge(Cache::remember($cityCacheKey, now()->addMinutes(30), function () use ($user) {
                return Event::getPopularEvents($user->city_id_e)->limit(5)->get()->toArray();
            }), $popularByUser);
        }
        if ($user->category_id_e) {
            $categoryCacheKey = "popular_events_user_category_{$user->id}";
            $popularByUser = array_merge(Cache::remember($categoryCacheKey, now()->addMinutes(30), function () use ($user) {
                return Event::getPopularEvents(null, $user->category_id_e)->limit(5)->get()->toArray();
            }), $popularByUser);
        }
        if ($user->promoter_id_e) {
            $promoterCacheKey = "popular_events_user_promoter_{$user->id}";
            $popularByUser = array_merge(Cache::remember($promoterCacheKey, now()->addMinutes(30), function () use ($user) {
                return Event::getPopularEvents(null, null, $user->promoter_id_e)->limit(5)->get()->toArray();
            }), $popularByUser);
        }
        if ($user->hall_id_e) {
            $hallCacheKey = "popular_events_user_hall_{$user->id}";
            $popularByUser = array_merge(Cache::remember($hallCacheKey, now()->addMinutes(30), function () use ($user) {
                return Event::getPopularEvents(null, null, null, $user->hall_id_e)->limit(5)->get()->toArray();
            }), $popularByUser);
        }

        // Returnează view-ul cu datele cache-uite
        return view('home', compact('popularEvents', 'popularPerformancesByHall', 'popularPromoters', 'popularPerformancesByPromoter', 'popularByUser'));
    }
}
