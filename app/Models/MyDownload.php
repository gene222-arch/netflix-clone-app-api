<?php

namespace App\Models;

use App\Models\Movie;
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

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
