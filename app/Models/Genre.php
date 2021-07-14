<?php

namespace App\Models;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Movie
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'enabled'
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
    * Define a many-to-many relationship with Movie class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_genres');
    }
}
