<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;

    protected $table = 'cards';
    protected $primaryKey = 'cardid';

    public $timestamps = false;
    protected $fillable = [
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
        'schedule',
        'phone_number',
        'web',
        'social_media',
        'characteristics',
        'creation_date',
        'update_date'
    ];
}
