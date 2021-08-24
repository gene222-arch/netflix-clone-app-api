<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_profile_id',
        'movie_id',
        'model_type'
    ];

    public $timestamps = false;
}
