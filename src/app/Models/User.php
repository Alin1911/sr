<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city_id',
        'category_id',
        'hall_id_e',
        'city_id_e',
        'category_id_e',
        'promoter_id_e'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id')->orderBy('id', 'desc');
    }


    function clearUserEventCache()
    {
        if ($this->city_id_e) {
            $cityCacheKey = "popular_events_user_city_{$this->id}";
            Cache::forget($cityCacheKey);
        }

        if ($this->category_id_e) {
            $categoryCacheKey = "popular_events_user_category_{$this->id}";
            Cache::forget($categoryCacheKey);
        }

        if ($this->promoter_id_e) {
            $promoterCacheKey = "popular_events_user_promoter_{$this->id}";
            Cache::forget($promoterCacheKey);
        }

        if ($this->hall_id_e) {
            $hallCacheKey = "popular_events_user_hall_{$this->id}";
            Cache::forget($hallCacheKey);
        }
    }

    public function updateScore()
    {
        $transactions = Transaction::where('user_id', $this->id)->get();
        if ($transactions->count() == 0) {
            return;
        }
        $tickets = Ticket::whereIn('transactionId', $transactions->pluck('id'))->get();
        if ($tickets->isEmpty()) {
            return;
        }
        $tickets->load('performance.event.category', 'performance.hall.city');

        $hallMap = [];
        $cityMap = [];
        $categoryMap = [];
        $promoterMap = [];

        foreach ($tickets as $ticket) {
            $hallMap[$ticket->performance->hall_id] = ($hallMap[$ticket->performance->hall_id] ?? 0) + 1;
            $cityMap[$ticket->performance->hall->city_id] = ($cityMap[$ticket->performance->hall->city_id] ?? 0) + 1;
            $categoryMap[$ticket->performance->event->category_id] = ($categoryMap[$ticket->performance->event->category_id] ?? 0) + 1;
            $promoterMap[$ticket->performance->promoter_id] = ($promoterMap[$ticket->performance->promoter_id] ?? 0) + 1;
        }

        $hallId = $this->getMaxKey($hallMap);
        $cityId = $this->getMaxKey($cityMap);
        $categoryId = $this->getMaxKey($categoryMap);
        $promoterId = $this->getMaxKey($promoterMap);

        $this->update([
            'hall_id_e' => $hallId,
            'city_id_e' => $cityId,
            'category_id_e' => $categoryId,
            'promoter_id_e' => $promoterId,
        ]);
    }

    protected function getMaxKey($map)
    {
        return array_search(max($map), $map);
    }
}
