<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleasedMovieNotifiedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'released_movie_id',
        'user_id',
        'notified_at',
        'ip_address'
    ];

    public $timestamps = false;
}
