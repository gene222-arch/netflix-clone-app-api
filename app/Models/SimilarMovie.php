<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SimilarMovie extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'model_id',
        'model_type',
        'similar_movie_id'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'similar_movie_id');  
    }
}
