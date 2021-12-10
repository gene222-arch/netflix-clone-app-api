<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'enabled'
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
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
