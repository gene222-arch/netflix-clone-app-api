<?php

namespace App\Traits\Dashboards;

use Illuminate\Support\Facades\DB;

trait DashboardServices
{

    public function dashboard(): array
    {
        return [
            'general_analytics' => self::generalAnalytics(),
            'top_five_most_rated_movies' => self::getTopFiveMostRatedMovies(),
            'top_five_most_liked_movies' => self::getTopFiveMostLikedMovies()
        ];
    }

    private static function generalAnalytics()
    {
        $query =  DB::select(
            'SELECT
                (
                    SELECT
                        COUNT(users.id)
                    FROM
                        users
                    INNER JOIN 
                        model_has_roles
                    ON 
                        users.id  = model_has_roles.model_id
                    INNER JOIN 
                        roles
                    ON 
                        model_has_roles.role_id = roles.id 
                    WHERE 
                        roles.name = "User"
                ) as total_number_of_users,
                (
                    SELECT 
                        COUNT(movies.id) 
                    FROM
                        movies
                ) as total_number_of_movies,
                (
                    SELECT 
                        COUNT(coming_soon_movies.id) 
                    FROM
                        coming_soon_movies
                    WHERE 
                        coming_soon_movies.status != "Released"
                ) as total_number_of_coming_soon_movies
            ');
        
        return reset($query);
    }

    private static function getTopFiveMostLikedMovies(): array
    {
        $query = DB::select(
            'SELECT
                movies.id,
                movies.title,
                ratings.likes as count
            FROM 
                ratings
            INNER JOIN 
                movies
            ON 
                ratings.movie_id = movies.id 
            GROUP BY 
                movies.id 
            ORDER BY
                ratings.likes 
            DESC
            LIMIT 
                5
        ');

        return $query;
    }

    private static function getTopFiveMostRatedMovies(): array
    {
        $query = DB::select(
            'SELECT
                movies.id,
                movies.title,
                ratings.total_votes as count
            FROM 
                ratings
            INNER JOIN 
                movies
            ON 
                ratings.movie_id = movies.id 
            GROUP BY 
                movies.id 
            ORDER BY
                ratings.total_votes 
            DESC
            LIMIT 
                5
        ');

        return $query;
    }
}