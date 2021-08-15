<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieReport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'movie_id',
        'search_count',
        'views',
        'total_likes_within_a_day',
        'total_views_within_a_day',
        'total_likes_within_a_week',
        'total_views_within_a_week',
        'current_date'
    ];

}
