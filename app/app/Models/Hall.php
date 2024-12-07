<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hall extends Model
{
    protected $table = 'hall';

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public static function getPopularHalls($cityId = null, $categoryId = null)
    {
        $ticketScore = 2;
        $deletedTicketScore = 1;

        $query = DB::table('hall')
            ->select(
                'hall.id',
                'hall.name',
                DB::raw("
                SUM(CASE WHEN tickets.id IS NOT NULL THEN $ticketScore ELSE 0 END) +
                SUM(CASE WHEN deleted_ticket.id IS NOT NULL THEN $deletedTicketScore ELSE 0 END) AS popularity_score
            ")
            )
            ->leftJoin('performance', 'hall.id', '=', 'performance.hall_id')
            ->leftJoin('tickets', 'performance.id', '=', 'tickets.performanceId')
            ->leftJoin('deleted_ticket', 'performance.id', '=', 'deleted_ticket.performanceId')
            ->leftJoin('events', 'performance.event_id', '=', 'events.id');

        if ($cityId) {
            $query->where('hall.city_id', $cityId);
        }

        if ($categoryId) {
            $query->where('events.category_id', $categoryId);
        }

        $halls = $query->groupBy('hall.id', 'hall.name')
            ->orderByDesc('popularity_score')
            ->get();

        return $halls;
    }

    public function getPopularityScoreAttribute()
    {
        $ticketScore = 2;
        $deletedTicketScore = 1;

        $popularityScore = DB::table('hall')
            ->select(
                DB::raw("
                    SUM(CASE WHEN tickets.id IS NOT NULL THEN $ticketScore ELSE 0 END) +
                    SUM(CASE WHEN deleted_ticket.id IS NOT NULL THEN $deletedTicketScore ELSE 0 END) AS popularity_score
                ")
            )
            ->leftJoin('performance', 'hall.id', '=', 'performance.hall_id')
            ->leftJoin('tickets', 'performance.id', '=', 'tickets.performanceId')
            ->leftJoin('deleted_ticket', 'performance.id', '=', 'deleted_ticket.performanceId')
            ->where('hall.id', $this->id)
            ->value('popularity_score');

        return $popularityScore ?: 0;
    }
}
