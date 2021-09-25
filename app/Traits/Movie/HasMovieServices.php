<?php

namespace App\Traits\Movie;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use App\Traits\ActivityLogsServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


trait HasMovieServices
{
    use HasUploadable, ActivityLogsServices; 

    public function getMovies(bool $isForKids)
    {
        $cacheKey = 'movies.index';
        $isForKidsCacheKey = 'is.for.kids.movies';

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
            $result = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () use($isForKids) 
            {
                $query = Movie::query();
            
                $query->select('*');
                $query->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12));
    
                return $query->latest()->get()->map(function($movie) 
                {
                    $currentMovie = $movie;
                    $currentMovie->other_movies = [];

                    return $currentMovie;
                });
            });

            return $result;
        }

        return Cache::get($cacheKey);
    }


    public function getCategorizedMovies($user, bool $isForKids): array
    {
        $cacheKey = 'movies.categorizedMovies';
        $isForKidsCacheKey = 'is.for.kids.caregorized.movies';

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

            $result = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () use($user, $isForKids)
            {
                $recentlyAddedMovies = Movie::latest()->take(20)->get();

                $trendingNow = Movie::selectRaw('
                            movies.*, 
                            (movie_reports.total_likes_within_a_week + movie_reports.total_views_within_a_week + movie_reports.search_count) 
                        AS trending_score
                    ')
                        ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
                        ->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12))
                        ->orderByDesc('trending_score')
                        ->take(10)
                        ->get();
        
                $topTen = Movie::selectRaw('
                            movies.*,
                            (movie_reports.total_likes_within_a_day + movie_reports.total_views_within_a_day + movie_reports.search_count) 
                        AS top_ten_score
                    ')
                        ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
                        ->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12))
                        ->orderByDesc('top_ten_score')
                        ->take(10)
                        ->get();
        
                $popularity = Movie::selectRaw('
                        movies.*,
                        (movie_reports.views + movie_reports.search_count + ratings.likes) as popularity
                ')
                    ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
                    ->leftJoin('ratings', 'ratings.movie_id', '=', 'movies.id')
                    ->where('ratings.model_type', 'Movie')
                    ->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12))
                    ->orderByDesc('popularity')
                    ->take(10)
                    ->get();
        
                $result = [
                    [
                        'title' => 'Recently Added Movies',
                        'movies' => $recentlyAddedMovies
                    ],
                    [
                        'title' => 'Trending Now',
                        'movies' => $trendingNow
                    ],
                    [
                        'title' => 'Top 10',
                        'movies' => $topTen
                    ],
                    [
                        'title' => 'Popularity',
                        'movies' => $popularity
                    ],
                ];
        
                if ($user->address()->exists()) 
                {
                    $country = $user->address->country;
        
                    $trendingNowByUserAddress = Movie::selectRaw('
                            movies.*,
                            (movie_reports.total_likes_within_a_week + movie_reports.total_views_within_a_week + movie_reports.search_count) 
                        AS trending_score
                    ')
                        ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
                        ->where('movies.country', $country)
                        ->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12))
                        ->orderByDesc('trending_score')
                        ->take(10)
                        ->get();
        
                    array_push($result, [
                        'title' => "Trending Now in the $country",
                        'movies' => $trendingNowByUserAddress
                    ]);
                }
        
                return $result;
            });

            return $result;
        }

        return Cache::get($cacheKey);
    }


    public function getLatestTwentyMovies()
    {
        $cacheKey = 'movies.latestTwenty';

        if (! Cache::has($cacheKey)) {
            return Cache::remember($cacheKey, Carbon::now()->endOfDay(), fn() => Movie::latest()->take(20)->get());
        }

        return Cache::get($cacheKey);
    }

    
    public function getMostLikedMovies()
    {
        $cacheKey = 'movies.mostLikedMovies';

        if (! Cache::has($cacheKey)) 
        {
            $result = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () {
                $query = DB::select("SELECT 
                        movies.id,
                        movies.title,
                        (
                            ((avg_vote * avg_rating) + ((likes + dislikes) * (likes - dislikes)) ) / (avg_vote + (likes + dislikes))
                            + (movie_reports.views + movie_reports.search_count)
                        ) AS score 
                    FROM 
                        movies  
                    INNER JOIN 
                        ratings
                    ON 
                        ratings.movie_id = movies.id
                    INNER JOIN 
                        movie_reports
                    ON 
                        movie_reports.movie_id = movies.id 
                    INNER JOIN 	
                        (
                            SELECT 
                                (SUM(likes + dislikes) / COUNT(id)) AS avg_vote 
                            FROM 
                                ratings
                            WHERE 
                                model_type = 'Movie'
                        ) as table_1
                    INNER JOIN 	
                        (
                            SELECT 
                                (SUM(likes - dislikes) / COUNT(id)) AS avg_rating 
                            FROM 
                                ratings
                            WHERE 
                                model_type = 'Movie'
                        ) as table_2
                        
                    WHERE 
                        ratings.model_type = 'Movie'
                    LIMIT 
                        250
                    "
                );

                return $query;
            });

            return $result;
        }

        return Cache::get($cacheKey);
    }


    public function getTopSearches(bool $isForKids)
    {
        $cacheKey = 'movies.topSearches';
        $isForKidsCacheKey = 'is.for.kids.top.searches';

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
            $result = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () use($isForKids)
            {
                $query = Movie::query();

                $query->select('movies.*', 'movie_reports.*');
                $query->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12));

                $query = $query
                    ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
                    ->where('movie_reports.search_count', '>', 0)
                    ->orderByDesc('movie_reports.search_count')
                    ->take(42)
                    ->get();

                return $query;
            });

            return $result;
        }

        return Cache::get($cacheKey);
    }


    public function createMovie(StoreRequest $request): bool|string
    {
        try {
            DB::transaction(function () use ($request)
            {
                $movieData = $this->filterMovieData($request);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;
                
                $movie = Movie::create($movieData);
                $movie->authors()->attach($authorIDs);
                $movie->casts()->attach($castIDs);
                $movie->directors()->attach($directorIDs);
                $movie->genres()->attach($genreIDs);

                $this->createLog(
                    'Create',
                    Movie::class,
                    "http://localhost:3000/video-management/movies/$movie->id/update-movie"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
        return true;
    }


    public function updateMovie(UpdateRequest $request, Movie $movie): bool|string
    {
        try {
            DB::transaction(function () use ($request, $movie)
            {   
                /** Store data */
                $movieData = $this->filterMovieData($request);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

                /** Delete a file only if it exist within the request */
                $this->deleteFile($request, [
                    'poster_path' => $movie->poster_path,
                    'wallpaper_path' => $movie->wallpaper_path,
                    'title_logo_path' => $movie->title_logo_path,
                    'video_path' => $movie->video_path
                ]);

                /** Update */
                $movie->update($movieData);
                $movie->authors()->sync($authorIDs);
                $movie->casts()->sync($castIDs);
                $movie->directors()->sync($directorIDs);
                $movie->genres()->sync($genreIDs);

                $this->createLog(
                    'Update',
                    Movie::class,
                    "http://localhost:3000/video-management/movies/$movie->id/update-movie"
                );
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
        return true;
    }

    
    protected function filterMovieData($request)
    {
        return $request->except([
            'author_ids', 
            'cast_ids', 
            'director_ids', 
            'genre_ids'
        ]);
    }
}