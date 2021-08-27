<?php

namespace App\Traits\Movie;

use Illuminate\Support\Str;
use App\Models\ComingSoonMovie;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Models\Trailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait HasComingSoonMovieServices
{
    use HasUploadable;
    
    /**
     * ! Problem: is for kids filter not working
     * Todo: cache result if is for kids param has the same value as before
     */
    public function getComingSoonMovies($isForKids, $status)// null
    {
        $isForKidsCacheKey = 'is.for.kids';

        if (! Cache::has($isForKidsCacheKey)) {
            $cachedIsForKids = Cache::remember($isForKidsCacheKey, Carbon::now()->endOfDay(), function () use($isForKids) {
                return $isForKids;
            });
        }
        else {
            $cachedIsForKids = Cache::get($isForKidsCacheKey);
        }

        $cacheKey = 'coming.soon.movies.index';

        if ((! Cache::has($cacheKey)) || ( $cachedIsForKids !== $isForKids )) 
        {
            Cache::forget($isForKidsCacheKey);

            $result = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () use($status, $isForKids) {
                $query = ComingSoonMovie::query();

                $query->when($status === 'Coming Soon', function($q) use($status) {
                    return $q->where('status', $status);
                });

                $query->when($isForKids, fn($q) => $q->where('age_restriction', '<=', 12));
                
                return $query->latest()->with('trailers')->get();
            });

            return $result;
        }

        return Cache::get($cacheKey);
    }

    public function createComingSoonMovie(StoreRequest $request): bool|string
    {
        try {
            DB::transaction(function () use ($request)
            {
                $comingSoonMovieData = $this->filterComingSoonMovieData($request);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

                $comingSoonMovie = ComingSoonMovie::create($comingSoonMovieData);
                
                $comingSoonMovie->authors()->attach($authorIDs);
                $comingSoonMovie->casts()->attach($castIDs);
                $comingSoonMovie->directors()->attach($directorIDs);
                $comingSoonMovie->genres()->attach($genreIDs);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
        return true;
    }

    public function updateComingSoonMovie(UpdateRequest $request, ComingSoonMovie $comingSoonMovie): bool|string
    {
        try {
            DB::transaction(function () use ($request, $comingSoonMovie)
            {   
                $comingSoonMovieData = $this->filterComingSoonMovieData($request);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;
                
                /** Delete a file only if it exist within the request */
                $this->deleteFile($request, [
                    'poster' => $comingSoonMovie->poster_path,
                    'wallpaper' => $comingSoonMovie->wallpaper_path,
                    'title_logo' => $comingSoonMovie->title_logo_path,
                    'video_trailer' => $comingSoonMovie->video_trailer_path
                ]);

                $comingSoonMovie->update($comingSoonMovieData);
                $comingSoonMovie->authors()->sync($authorIDs);
                $comingSoonMovie->casts()->sync($castIDs);
                $comingSoonMovie->directors()->sync($directorIDs);
                $comingSoonMovie->genres()->sync($genreIDs);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
        return true;
    }

    protected function filterComingSoonMovieData($request): array
    {
        return $request->except([
            'author_ids', 
            'cast_ids', 
            'director_ids', 
            'genre_ids'
        ]);
    }

}