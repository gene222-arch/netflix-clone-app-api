<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_profile_id',
        'movie_id',
        'type',
        'read_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movieNotification) {
            $movieNotification->user_id = auth('api')->user()->id;
        });
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('M, d');
    }
}
