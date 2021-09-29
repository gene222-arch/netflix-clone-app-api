<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimilarMovie extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'movie_id',
        'similar_movie_id'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class)->select('title');
    }
}
