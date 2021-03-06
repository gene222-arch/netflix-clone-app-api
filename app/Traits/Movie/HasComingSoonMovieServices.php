<?php

namespace App\Traits\Movie;

use Carbon\Carbon;
use App\Models\Movie;
use App\Models\Trailer;
use App\Models\RemindMe;
use App\Models\SimilarMovie;
use App\Models\ReleasedMovie;
use App\Models\ComingSoonMovie;
use Illuminate\Support\Facades\DB;
use App\Traits\ActivityLogsServices;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\ReleaseRequest;
use App\ExpoNotificationServices\MovieReleasedExpoNotificationService;
use App\Services\RatingService;

trait HasComingSoonMovieServices
{
    use HasUploadable, ActivityLogsServices;
    
    public function getComingSoonMovies(bool $isForKids, bool $isComingSoon, string $trashedOnly) // null
    {
        if ($trashedOnly === 'true') 
        {
            $query = ComingSoonMovie::query();

            $query->onlyTrashed();
            $query->with('similarMovies.movie');
            $query->when($isForKids, fn($q) => $q->where('age_restriction', '<=', 12));
            $query->when($isComingSoon, fn($q) => $q->where('released_at', null));
            
            return $query
                ->orderBy('status')
                ->orderByDesc('created_at')
                ->with('trailers')
                ->get();
        }

        $cacheKey = 'coming.soon.movies.index';
        $isForKidsCacheKey = 'is.for.kids.coming.soon.movies';

        $twentyFourHourLimit = Carbon::now()->endOfDay();

        $cachedIsForKids = !Cache::has($isForKidsCacheKey)
            ? Cache::remember($isForKidsCacheKey, $twentyFourHourLimit, fn() => $isForKids)
            : Cache::get($isForKidsCacheKey);

        if (!Cache::has($cacheKey) || $cachedIsForKids !== $isForKids || $isComingSoon) 
        {
            Cache::forget($isForKidsCacheKey);
            Cache::forget($cacheKey);

            Cache::remember($isForKidsCacheKey, $twentyFourHourLimit, fn() => $isForKids);

            $result = Cache::remember($cacheKey, $twentyFourHourLimit, function () use($isComingSoon, $isForKids) 
            {
                $query = ComingSoonMovie::query();

                $query->with('similarMovies.movie');
                $query->when($isForKids, fn($q) => $q->where('age_restriction', '<=', 12));
                $query->when($isComingSoon, fn($q) => $q->where('released_at', null));
                
                return $query
                    ->orderBy('status')
                    ->orderByDesc('created_at')
                    ->with('trailers')
                    ->get();
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
                $similarMovieIds = $request->similar_movie_ids;

                $comingSoonMovie = ComingSoonMovie::create($comingSoonMovieData);

                if ($similarMovieIds) 
                {
                    $similarMovies = [];

                    foreach ($similarMovieIds as $similarMovieId) {
                        $similarMovies[] = new SimilarMovie([ 
                            'similar_movie_id' => $similarMovieId, 
                            'model_type' => ComingSoonMovie::class 
                        ]);
                    }

                    $comingSoonMovie->similarMovies()->saveMany($similarMovies);
                }
                
                $comingSoonMovie->authors()->attach($authorIDs);
                $comingSoonMovie->casts()->attach($castIDs);
                $comingSoonMovie->directors()->attach($directorIDs);
                $comingSoonMovie->genres()->attach($genreIDs);

                ComingSoonMovie::cacheToForget();
                
                $this->createLog(
                    'Create',
                    ComingSoonMovie::class,
                    "video-management/coming-soon-movies/$comingSoonMovie->id"
                );
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
                $similarMovieIds = $request->similar_movie_ids;

                if (is_array($similarMovieIds)) 
                {
                    if (! count($similarMovieIds)) {
                        $comingSoonMovie->similarMovies()->delete();
                    }
                    else {
                        $similarMovies = [];

                        foreach ($similarMovieIds as $similarMovieId) {
                            $similarMovies[] = new SimilarMovie([ 
                                'similar_movie_id' => $similarMovieId,
                                'model_type' => ComingSoonMovie::class
                            ]);
                        }

                        $comingSoonMovie->similarMovies()->delete();
                        $comingSoonMovie->similarMovies()->saveMany($similarMovies);
                    }
                }

                $comingSoonMovie->update($comingSoonMovieData);
                $comingSoonMovie->authors()->sync($authorIDs);
                $comingSoonMovie->casts()->sync($castIDs);
                $comingSoonMovie->directors()->sync($directorIDs);
                $comingSoonMovie->genres()->sync($genreIDs);
                
                ComingSoonMovie::cacheToForget();

                $this->createLog(
                    'Update',
                    ComingSoonMovie::class,
                    "video-management/coming-soon-movies/$comingSoonMovie->id"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
        return true;
    }


    public function createTrailer(ComingSoonMovie $comingSoonMovie, array $trailerDetails): bool|string
    {
        try {
            DB::transaction(function () use($comingSoonMovie, $trailerDetails) 
            {
                $id = $comingSoonMovie->trailers()->create($trailerDetails)->id;

                $this->createLog(
                    'Create',
                    Trailer::class,
                    "video-management/coming-soon-movies/$comingSoonMovie->id/trailers/$id"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function trailerUpdate(ComingSoonMovie $comingSoonMovie, int $trailerId, array $trailerDetails)
    {
        try {
            DB::transaction(function () use($comingSoonMovie, $trailerId, $trailerDetails) 
            {
                $comingSoonMovie 
                    ->trailers()
                    ->find($trailerId)
                    ->update($trailerDetails);

                $this->createLog(
                    'Update',
                    Trailer::class,
                    "video-management/coming-soon-movies/$comingSoonMovie->id/trailers/$trailerId"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function release(ReleaseRequest $request, ComingSoonMovie $comingSoonMovie): bool|string
    {
        try {
            DB::transaction(function () use ($request, $comingSoonMovie) 
            {
                $currentDate = Carbon::today();
                $comingSoonMovieID = $comingSoonMovie->id;
        
                $movieDetails = [
                    'year_of_release' => $currentDate->format('Y'),
                    'date_of_release' => $currentDate,
                    'duration_in_minutes' => $request->duration_in_minutes,
                    'video_path' => $request->video_path,
                    'video_preview_path' => $comingSoonMovie->video_trailer_path,
                    'video_size_in_mb' => $request->video_size_in_mb
                ];
    
                $authors = $comingSoonMovie->authors()->get();
                $casts = $comingSoonMovie->casts()->get();
                $directors = $comingSoonMovie->directors()->get();
                $genres = $comingSoonMovie->genres()->get();

                $movie = Movie::create($movieDetails + $comingSoonMovie->toArray());

                $movie->authors()->attach($authors);
                $movie->casts()->attach($casts);
                $movie->directors()->attach($directors);
                $movie->genres()->attach($genres);

                $comingSoonMovieSimilarMovieIds = $comingSoonMovie->similarMovies->pluck('similar_movie_id')->toArray();
                
                $similarMovies = [];

                foreach ($comingSoonMovieSimilarMovieIds as $similarMovieId) 
                {
                    $similarMovies[] = new SimilarMovie([ 
                        'similar_movie_id' => $similarMovieId, 
                        'model_type' => Movie::class
                    ]);
                }
                
                $movie->similarMovies()->saveMany($similarMovies);

                MovieReleasedExpoNotificationService::notify($movie->title, $comingSoonMovieID);
                event(new \App\Events\ComingSoonMovieReleasedEvent($comingSoonMovie));

                $comingSoonMovie->update([
                    'status' => 'Released',
                    'released_at' => $currentDate
                ]);

                ReleasedMovie::query()->create([
                    'movie_id' => $movie->id,
                    'coming_soon_movie_id' => $comingSoonMovieID
                ]);

                RemindMe::query()
                    ->where('coming_soon_movie_id', '=', $comingSoonMovieID)
                    ->update([ 'is_released' => true ]);
                    
                RatingService::onReleaseRelocate(
                    $comingSoonMovieID,
                    $movie->id
                );

                $this->createLog(
                    'Update',
                    ComingSoonMovie::class,
                    "video-management/coming-soon-movies/$comingSoonMovieID"
                );
                
                ComingSoonMovie::cacheToForget();
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function deleteManyComingSoonMovies(array $ids)
    {
        try {
            DB::transaction(function () use($ids)
            {
                ComingSoonMovie::whereIn('id', $ids)->delete();

                $this->createLog('Delete', ComingSoonMovie::class);

                ComingSoonMovie::cacheToForget();
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function deleteManyTrailers(ComingSoonMovie $comingSoonMovie, array $ids)
    {
        try {
            DB::transaction(function () use($ids, $comingSoonMovie)
            {
                $comingSoonMovie->trailers()->whereIn('id', $ids)->delete();

                $this->createLog('Delete', Trailer::class);
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