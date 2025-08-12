<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CardController;
use App\Http\Controllers\Api\v1\CardStatusController;
use App\Http\Controllers\Api\v1\MemberController;

Route::prefix('v1')->group(function () {
    //Cards routes
    Route::get('cards', [CardController::class, 'getAllCards']);
    Route::get('card/{cardId}', [CardController::class, 'getCardById']);
    Route::get('card/lat-long/{cardId}', [CardController::class, 'getLatAndLongdByCardId']);
    Route::get('cards/card', [CardController::class, 'getCardNameByCategory']);
    Route::get('cards/category', [CardController::class, 'getCardsByCategory']);
    Route::get('cards/premium', [CardController::class, 'getCardsByPremium']);
    Route::get('cards/place', [CardController::class, 'getCardsByPlace']);
    Route::get('cards/carousel', [CardController::class, 'getCardsByIsPlace']);
    Route::get('cards/place/category', [CardController::class, 'getCardsByPlacesPerCategory']);
    Route::get('cards/category/count', [CardController::class, 'countCardsByCategory']);

    //Cards status routes
    Route::post('cards/register_status', [CardStatusController::class, 'insertCardStatus']);
    Route::get('cards/count/general', [CardStatusController::class, 'countByStatus']);
    Route::get('cards/count/date', [CardStatusController::class, 'countByStatusAndDate']);
    Route::get('cards/count', [CardStatusController::class, 'countByStatusAndDateBetween']);
    Route::get('cards/count/city', [CardStatusController::class, 'countByCityAndStatus']);
    Route::get('card/count/card', [CardStatusController::class, 'countByCardIdAndStatus']);
    Route::get('card', [CardStatusController::class, 'countByDateAndStatusAndCardId']);
    Route::get('card/count/range', [CardStatusController::class, 'countByStatusAndCardIdAndDateBetween']);
    Route::get('card/count/city', [CardStatusController::class, 'countByCityAndStatusAndCardId']);

    //Members routes
    Route::post('card/register', [CardController::class, 'registerMember']);
    Route::get('cards/list', [MemberController::class, 'getCardsByMemberId']);
    Route::post('/login', [MemberController::class, 'authenticate']);
});