<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;

    protected $table = 'cards';

    public $timestamps = false;
    protected $fillable = [
        'cardid',
        'memberid',
        'cardname',
        'city',
        'place',
        'category',
        'premium',
        'image',
        'back_image',
        'lat',
        'long',
        'creation_date',
        'update_date'
    ];
}
