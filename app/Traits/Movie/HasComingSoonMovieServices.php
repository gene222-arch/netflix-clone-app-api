<?php

namespace App\Traits\Movie;

use Carbon\Carbon;
use App\Models\Trailer;
use App\Models\ComingSoonMovie;
use Illuminate\Support\Facades\DB;
use App\Traits\ActivityLogsServices;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateStatusRequest;

trait HasComingSoonMovieServices
{
    use HasUploadable, ActivityLogsServices;
    
    public function getComingSoonMovies(bool $isForKids, ?string $status) // null
    {
        $cacheKey = 'coming.soon.movies.index';
        $isForKidsCacheKey = 'is.for.kids.coming.soon.movies';

        if (! Cache::has($isForKidsCacheKey)) {
            $cachedIsForKids = Cache::remember($isForKidsCacheKey, Carbon::now()->endOfDay(), fn() => $isForKids);
        } else {
            $cachedIsForKids = Cache::get($isForKidsCacheKey);
        }

        if (! Cache::has($cacheKey) || $cachedIsForKids !== $isForKids) 
        {
            Cache::forget($isForKidsCacheKey);
            Cache::forget($cacheKey);

            Cache::remember($isForKidsCacheKey, Carbon::now()->endOfDay(), fn() => $isForKids);

            $result = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () use($status, $isForKids) 
            {
                $query = ComingSoonMovie::query();

                $query->when($status === 'Coming Soon', fn($q) => $q->where('status', $status));
                $query->when($isForKids, fn($q) => $q->where('age_restriction', '<=', 12));
                
                return $query
                            ->orderBy('status')
                            ->orderBy('created_at', 'desc')
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

                $comingSoonMovie = ComingSoonMovie::create($comingSoonMovieData);
                
                $comingSoonMovie->authors()->attach($authorIDs);
                $comingSoonMovie->casts()->attach($castIDs);
                $comingSoonMovie->directors()->attach($directorIDs);
                $comingSoonMovie->genres()->attach($genreIDs);

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

                $this->createLog(
                    'Update',
                    ComingSoonMovie::class,
                    "video-management/coming-soon-movies/$comingSoonMovie->id"
                );

                ComingSoonMovie::cacheToForget();
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


    public function updateMovieStatus(UpdateStatusRequest $request, ComingSoonMovie $comingSoonMovie)
    {
        try {
            DB::transaction(function () use ($request, $comingSoonMovie) 
            {
                $currentDate = Carbon::today();

                $status = $comingSoonMovie->status === 'Released' ? 'Coming Soon' : 'Released';
                $releasedAt = $comingSoonMovie->status === 'Released' ? null : $currentDate;
        
                $comingSoonMovie->update([
                    'status' => $status,
                    'released_at' => $releasedAt
                ]);
        
                if ($status === 'Released') 
                {
                    $movie = array_merge(
                        $comingSoonMovie->toArray(),
                        [
                            'year_of_release' => $currentDate->format('Y'),
                            'date_of_release' => $currentDate,
                            'duration_in_minutes' => $request->duration_in_minutes,
                            'video_path' => $request->video_path,
                            'video_preview_path' => $comingSoonMovie->video_trailer_path,
                            'video_size_in_mb' => $request->video_size_in_mb
                        ]
                    );
        
                    $authorIds = $comingSoonMovie->authors()->get();
                    $castIds = $comingSoonMovie->casts()->get();
                    $directorIds = $comingSoonMovie->directors()->get();
                    $genreIds = $comingSoonMovie->genres()->get();
        
                    $movie = Movie::create($movie);
                    $movie->authors()->attach($authorIds);
                    $movie->casts()->attach($castIds);
                    $movie->directors()->attach($directorIds);
                    $movie->genres()->attach($genreIds);

                    event(new ComingSoonMovieReleasedEvent($comingSoonMovie));
                }

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


    public function deleteManyComingSoonMovies(array $ids)
    {
        try {
            DB::transaction(function () use($ids)
            {
                ComingSoonMovie::whereIn('id', $ids)->delete();

                $this->createLog('Delete', ComingSoonMovie::class);
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