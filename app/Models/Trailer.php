<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trailer extends Model
{
    use HasFactory;

    protected $table = 'coming_soon_movie_trailers';

    protected $fillable = [
        'coming_soon_movie_id',
        'title',
        'poster_path',
        'wallpaper_path',
        'title_logo_path',
        'video_path'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];  
    
    /**
     * Define an inverse one-to-one or many relationship with Coming Soon Movie class
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comingSoonMovie(): BelongsTo
    {
        return $this->belongsTo(ComingSoonMovie::class);
    }
}
