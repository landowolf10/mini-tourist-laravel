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
        'category',
        'premium',
        'image',
        'back_image',
        'creation_date',
        'update_date'
    ];
}
