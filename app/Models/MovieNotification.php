<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'type',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M, d');
    }
}
