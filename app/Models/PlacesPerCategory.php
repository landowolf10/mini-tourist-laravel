<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacesPerCategory extends Model
{
    use HasFactory;

    protected $table = 'places_per_category';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'owner_id',
        'card_id',
        'place_name',
        'category'
    ];
}
