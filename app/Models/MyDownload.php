<?php

namespace App\Models;

use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MyDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_profile_id',
        'movie_id',
        'uri',
        'downloaded_at'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movie) 
        {
            $movie->downloaded_at = Carbon::now();
        });
    }


    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function getDownloadedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
