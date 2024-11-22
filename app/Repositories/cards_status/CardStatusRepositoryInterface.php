<?php

namespace App\Repositories\cards_status;

use Carbon\Carbon;

interface CardStatusRepositoryInterface
{
    public function insertCardStatus($cardStatus);
    public function countByStatus();
    public function countByStatusAndDate(Carbon $date);
    public function countByStatusAndDateBetween(Carbon $startDate, Carbon $endDate);
    public function countByCityAndStatus($city);
    public function countByCardIdAndStatus($cardId);
    public function countByDateAndStatusAndCardId($cardId, Carbon $date);
    public function countByStatusAndCardIdAndDateBetween($cardId, Carbon $startDate, Carbon $endDate);
    public function countByCityAndStatusAndCardId($cardId, $city);
}