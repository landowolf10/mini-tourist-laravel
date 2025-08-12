<?php

namespace App\Repositories\cards_status;

use App\Models\CardsStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CardStatusRepositoryImp implements CardStatusRepositoryInterface
{
    public function insertCardStatus($cardStatus)
    {
        return CardsStatus::create($cardStatus);
    }

    public function countByStatus()
    {
        return CardsStatus::selectRaw("
            SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
            SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
        ")->whereIn('status', ['Visited', 'Downloaded'])->get();
    }

    public function countByStatusAndDate(Carbon $date)
    {
        // Usamos DB::select para obtener resultados directos
        $results = DB::select("
            SELECT 
                COALESCE(SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END), 0) AS visited_count,
                COALESCE(SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END), 0) AS downloaded_count
            FROM card_status
            WHERE status IN ('Visited', 'Downloaded')
            AND DATE(date) = ?
            GROUP BY DATE(date)
        ", [$date->format('Y-m-d')]);

        // Siempre devolvemos los conteos como enteros
        return [
            'visited_count' => $results ? (int)$results[0]->visited_count : 0,
            'downloaded_count' => $results ? (int)$results[0]->downloaded_count : 0
        ];
    }

    public function countByStatusAndDateBetween(Carbon $startDate, Carbon $endDate)
    {
        return CardsStatus::selectRaw("
           SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
           SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
        ")
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->whereBetween('date', [$startDate, $endDate])
        ->get();
    }

    public function countByCityAndStatus($city)
    {
        return CardsStatus::selectRaw("
            SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
            SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
        ")
        ->where('city', $city)  // Filter by city
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->get();
    }

    public function countByCardIdAndStatus($cardId)
    {
        return DB::table('card_status AS cs')
            ->selectRaw("
                c.cardname AS card_name,
                SUM(CASE WHEN cs.status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
                SUM(CASE WHEN cs.status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
            ")
            ->join('Cards AS c', 'c.cardid', '=', 'cs.cardid')
            ->where('cs.cardid', $cardId)
            ->groupBy('cs.cardid', 'c.cardname')
            ->get();
    }

    public function countByDateAndStatusAndCardId($cardId, Carbon $date)
    {
        return CardsStatus::selectRaw("
            DATE(date) as date,
            COALESCE(SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END), 0) AS visited_count,
            COALESCE(SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END), 0) AS downloaded_count
        ")
        ->where('cardid', $cardId)
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->whereDate('date', $date)
        ->groupByRaw('DATE(date)') // Usar groupByRaw para función DATE
        ->get();
    }

    public function countByStatusAndCardIdAndDateBetween($cardId, Carbon $startDate, Carbon $endDate)
    {
        return CardsStatus::selectRaw("
            COALESCE(SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END), 0) AS visited_count,
            COALESCE(SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END), 0) AS downloaded_count
        ")
        ->where('cardid', $cardId)
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->whereBetween(DB::raw('DATE(`date`)'), [$startDate->toDateString(), $endDate->toDateString()])
        ->get();
    }

    public function countByCityAndStatusAndCardId($cardId, $city)
    {
        return CardsStatus::selectRaw("
            SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
            SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
        ")
        ->where('cardid', $cardId)
        ->where('city', $city)
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->get();
    }
}