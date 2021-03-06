<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'avatar_path',
        'pseudonym',
        'birth_name',
        'gender',
        'height_in_cm',
        'biographical_information',
        'birth_details',
        'date_of_birth',
        'place_of_birth',
        'death_details',
        'date_of_death',
        'enabled',
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
        return $this->belongsToMany(Movie::class, 'movie_authors');
    }
}
