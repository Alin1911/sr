<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Promoter extends Model
{
    protected $table = 'promoters';
    
    public function performances()
    {
        return $this->hasMany(Performance::class, 'promoter_id');
    }

    public static function getPopularPromoters($cityId = null, $categoryId = null)
    {
        $ticketScore = 2;
        $deletedTicketScore = 1;

        $query = DB::table('promoters')
            ->select(
                'promoters.id',
                'promoters.name',
                DB::raw("
                SUM(CASE WHEN tickets.id IS NOT NULL THEN $ticketScore ELSE 0 END) +
                SUM(CASE WHEN deleted_ticket.id IS NOT NULL THEN $deletedTicketScore ELSE 0 END) AS popularity_score
            ")
            )
            ->join('performance', 'promoters.id', '=', 'performance.promoter_id')
            ->leftJoin('hall', 'performance.hall_id', '=', 'hall.id')
            ->leftJoin('tickets', 'performance.id', '=', 'tickets.performanceId')
            ->leftJoin('deleted_ticket', 'performance.id', '=', 'deleted_ticket.performanceId')
            ->leftJoin('events', 'performance.event_id', '=', 'events.id');

        if ($cityId) {
            $query->where('hall.city_id', $cityId);
        }

        if ($categoryId) {
            $query->where('events.category_id', $categoryId);
        }

        $promoters = $query->groupBy('promoters.id', 'promoters.name')
            ->orderByDesc('popularity_score')
            ->get();

        return $promoters;
    }

    public function getPopularityScoreAttribute()
    {
        $ticketScore = 2;
        $deletedTicketScore = 1;

        $popularityScore = DB::table('promoters')
            ->select(
                DB::raw("
                    SUM(CASE WHEN tickets.id IS NOT NULL THEN $ticketScore ELSE 0 END) +
                    SUM(CASE WHEN deleted_ticket.id IS NOT NULL THEN $deletedTicketScore ELSE 0 END) AS popularity_score
                ")
            )
            ->leftJoin('performance', 'promoters.id', '=', 'performance.promoter_id')
            ->leftJoin('tickets', 'performance.id', '=', 'tickets.performanceId')
            ->leftJoin('deleted_ticket', 'performance.id', '=', 'deleted_ticket.performanceId')
            ->where('promoters.id', $this->id)
            ->value('popularity_score');

        return $popularityScore ?: 0;
    }
}
