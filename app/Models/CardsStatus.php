<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardsStatus extends Model
{
    use HasFactory;

    protected $table = 'card_status';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'cardid',
        'status',
        'city',
        'date'
    ];
}
