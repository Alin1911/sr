<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('performances', function ($query) {
            $query->where('ending_date', '>', now())
                ->where('starting_date', '<', now()->addMonth(2));
        });
    }

    public static function getPopularEvents($cityId = null, $categoryId = null, $promoterId = null, $hallId = null)
    {
        $ticketScore = 2;
        $deletedTicketScore = 1;

        $query = Event::query()
            ->select('events.id', 'events.title', 'events.category_id', 'events.description', 'events.image_url')
            ->addSelect(DB::raw("
                SUM(CASE WHEN tickets.id IS NOT NULL THEN $ticketScore ELSE 0 END) +
                SUM(CASE WHEN deleted_ticket.id IS NOT NULL THEN $deletedTicketScore ELSE 0 END) AS popularity_score
            "))
            ->leftJoin('performance', 'events.id', '=', 'performance.event_id')
            ->leftJoin('tickets', 'performance.id', '=', 'tickets.performanceId')
            ->leftJoin('deleted_ticket', 'performance.id', '=', 'deleted_ticket.performanceId')
            ->leftJoin('hall', 'performance.hall_id', '=', 'hall.id')
            ->groupBy('events.id', 'events.title', 'events.category_id')
            ->orderByDesc('popularity_score')
            ->active();

        if ($cityId) {
            $query->where('hall.city_id', $cityId);
        }

        if ($categoryId) {
            $query->where('events.category_id', $categoryId);
        }

        if ($hallId) {
            $query->where('performance.hall_id', $hallId);
        }

        if ($promoterId) {
            $query->where('performance.promoter_id', $promoterId);
        }

        return $query;
    }

    public function getPopularityScoreAttribute()
    {
        $ticketScore = 2;
        $deletedTicketScore = 1;

        $popularityScore = DB::table('events')
            ->select(
                DB::raw("
                SUM(CASE WHEN tickets.id IS NOT NULL THEN $ticketScore ELSE 0 END) +
                SUM(CASE WHEN deleted_ticket.id IS NOT NULL THEN $deletedTicketScore ELSE 0 END) AS popularity_score
            ")
            )
            ->leftJoin('performance', 'events.id', '=', 'performance.event_id')
            ->leftJoin('tickets', 'performance.id', '=', 'tickets.performanceId')
            ->leftJoin('deleted_ticket', 'performance.id', '=', 'deleted_ticket.performanceId')
            ->where('events.id', $this->id)
            ->value('popularity_score');

        return $popularityScore ?: 0;
    }
}
