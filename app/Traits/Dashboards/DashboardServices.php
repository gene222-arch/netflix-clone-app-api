<?php

namespace App\Traits\Dashboards;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait DashboardServices
{

    public function dashboard(int $year): array
    {
        return [
            'monthlySubscribersPerYear' => self::monthlySubscribersPerYear($year),
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
                        COUNT(*)  
                    FROM
                        users
                    LEFT JOIN 
                        model_has_roles
                    ON 
                        users.id  = model_has_roles.model_id
                    LEFT JOIN 
                        roles
                    ON 
                        model_has_roles.role_id = roles.id
                    WHERE 
                        roles.name != "Subscriber"
                ) as total_number_of_users,
                (
                    SELECT
                        COUNT(*)  
                    FROM
                        users
                    INNER JOIN 
                        model_has_roles
                    ON 
                        model_has_roles.model_id = users.id
                    INNER JOIN 
                        roles 
                    ON 
                        roles.id = model_has_roles.role_id
                    WHERE 
                        is_active = 1
                    AND 
                        roles.name = "Subscriber"
                ) AS total_active_subscribers,
                (
                    SELECT
                        COUNT(*)  
                    FROM
                        users
                    LEFT JOIN 
                        model_has_roles
                    ON 
                        users.id  = model_has_roles.model_id
                    LEFT JOIN 
                        roles
                    ON 
                        model_has_roles.role_id = roles.id
                    WHERE 
                        roles.name = "Subscriber"
                ) as total_number_of_subscribers,
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

    private static function monthlySubscribersPerYear(int $year): array 
    {
        $monthlySubscribers = DB::select('SELECT 
                COUNT(users.id) AS subscribers_count,
                MONTH(users.created_at) - 1 as month_number
            FROM 
                users
            INNER JOIN 
                model_has_roles
            ON 
                model_has_roles.model_id = users.id
            INNER JOIN 
                roles 
            ON 
                roles.id = model_has_roles.role_id
            WHERE
                roles.name = "Subscriber"
            AND 
                YEAR(users.created_at) = :filterYear
            GROUP BY 
                MONTH(users.created_at) - 1
        ', [
            'filterYear' => $year
        ]);

        $toArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        foreach ($monthlySubscribers as $monthlySubscriber) {
            $toArray[$monthlySubscriber->month_number] = $monthlySubscriber->subscribers_count;
        }

        return $toArray;
    }
}