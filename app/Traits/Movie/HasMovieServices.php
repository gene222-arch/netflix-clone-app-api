<?php

namespace App\Traits\Movie;

use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait HasMovieServices
{
    use HasUploadable;

    public function getMovies(bool $isForKids)
    {
        $cacheKey = 'movies.index';

        if (! Cache::has($cacheKey)) {
            $result = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () use($isForKids) {
                $query = Movie::query();
            
                $query->select('movies.*', 'coming_soon_movies.video_trailer_path');
                $query->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12));
                $query->leftJoin('coming_soon_movies', 'coming_soon_movies.title', '=', 'movies.title');
    
                return $query->latest()->get()->map('addOtherMovieProps');
            });

            return $result;
        }

        return Cache::get($cacheKey);
    }

    public function addOtherMovieProps($movie) 
    {
        $currentMovie = $movie;
        $currentMovie->other_movies = [];
        return $currentMovie;
    }

    public function getCategorizedMovies($user, bool $isForKids): array
    {
        $recentlyAddedMovies = Movie::latest()->take(20)->get();

        $trendingNow = Movie::selectRaw('
                    movies.*, 
                    coming_soon_movies.video_trailer_path,
                    (movie_reports.total_likes_within_a_week + movie_reports.total_views_within_a_week + movie_reports.search_count) 
                AS trending_score
            ')
                ->leftJoin('coming_soon_movies', 'coming_soon_movies.title', '=', 'movies.title')
                ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
                ->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12))
                ->orderByDesc('trending_score')
                ->take(10)
                ->get();

        $topTen = Movie::selectRaw('
                    movies.*,
                    coming_soon_movies.video_trailer_path, 
                    (movie_reports.total_likes_within_a_day + movie_reports.total_views_within_a_day + movie_reports.search_count) 
                AS top_ten_score
            ')
                ->leftJoin('coming_soon_movies', 'coming_soon_movies.title', '=', 'movies.title')
                ->leftJoin('movie_reports', 'movie_reports.movie_id', '=', 'movies.id')
                ->when($isForKids, fn($q) => $q->where('movies.age_restriction', '<=', 12))
                ->orderByDesc('top_ten_score')
                ->take(10)
                ->get();

        $popularity = Movie::selectRaw('
                movies.*,
                coming_soon_movies.video_trailer_path, 
                (movie_reports.views + movie_reports.search_count + ratings.likes) as popularity
        ')
            ->leftJoin('coming_soon_movies', 'coming_soon_movies.title', '=', 'movies.title')
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
                    coming_soon_movies.video_trailer_path, 
                    (movie_reports.total_likes_within_a_week + movie_reports.total_views_within_a_week + movie_reports.search_count) 
                AS trending_score
            ')
                ->leftJoin('coming_soon_movies', 'coming_soon_movies.title', '=', 'movies.title')
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