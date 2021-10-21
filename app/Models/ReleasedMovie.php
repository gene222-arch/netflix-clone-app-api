<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleasedMovie extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'movie_id',
        'coming_soon_movie_id',
        'released_at'
    ];
}
