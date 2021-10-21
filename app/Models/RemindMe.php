<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemindMe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_profile_id',
        'coming_soon_movie_id',
        'reminded_at'
    ];

    public $timestamps = false;
}
