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
        return CardsStatus::selectRaw("
           SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
           SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
        ")
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->whereDate('date', $date)
        ->groupBy('date') // Grouping by the date
        ->get();
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
            date,
            SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
            SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
        ")
        ->where('cardid', $cardId)
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->whereDate('date', $date)
        ->groupBy('date') 
        ->get();
    }

    public function countByStatusAndCardIdAndDateBetween($cardId, Carbon $startDate, Carbon $endDate)
    {
        return CardsStatus::selectRaw("
           SUM(CASE WHEN status = 'Visited' THEN 1 ELSE 0 END) AS visited_count,
           SUM(CASE WHEN status = 'Downloaded' THEN 1 ELSE 0 END) AS downloaded_count
        ")
        ->where('cardid', $cardId)
        ->whereIn('status', ['Visited', 'Downloaded'])
        ->whereBetween('date', [$startDate, $endDate])
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