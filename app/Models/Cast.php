<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cast extends Model
{
    use HasFactory;

    protected $fillable = [
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

    /**
    * Define a many-to-many relationship with Movie class
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function movies(): BelongsToMany
    {
        return $this->belongsToMany('movie_casts', Movie::class);
    }
}
