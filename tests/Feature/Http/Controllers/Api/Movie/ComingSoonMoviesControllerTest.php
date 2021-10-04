<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComingSoonMoviesControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_coming_soon_movies()
    {
        $response = $this->get(
            '/api/coming-soon-movies',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_coming_soon_movie()
    {
        $id = 1;

        $response = $this->get(
            "/api/coming-soon-movies/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_coming_soon_movie_trailer()
    {
        $id = 1;

        $response = $this->get(
            "/api/coming-soon-movies/$id/trailers",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_coming_soon_movie()
    {
        $data = [
            'title' => 'Tenki no ko',
            'plot' => 'Set during a period of exceptionally rainy weather, high-school boy Hodaka Morishima runs away from his troubled rural home to Tokyo and befriends an orphan girl who can manipulate the weather.',
            'duration_in_minutes' => 112,
            'age_restriction' => 12,
            'country' => 'Japan',
            'language' => 'Japanese',
            'casts' => 'Kotaro Daigo',
            'cast_ids' => [2],
            'genres' => 'Anime, Romance, Drama, Animation, Fantasy',
            'genre_ids' => [1, 2, 3, 4, 5],
            'directors' => 'Makoto Shinkai',
            'director_ids' => [1],
            'authors' => 'Makoto Shinkai',
            'author_ids' => [1],
            'poster' => '',
            'wallpaper' => '',
            'video_trailer' => '',
            'title_logo' => '',
        ];

        $response = $this->post(
            '/api/coming-soon-movies',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_coming_soon_movie_trailer()
    {
        $id  = 1;

        $data = [
            'coming_soon_movie_id' => $id,
            'title' => 'Kimi no Na wa Trailer II',
            'poster' => '',
            'wallpaper' => '',
            'video_trailer' => '',
            'title_logo' => '',
        ];

        $response = $this->post(
            "/api/coming-soon-movies/$id/trailers",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_coming_soon_movie()
    {
        $id = 1;

        $data = [
            'id' => $id,
            'title' => 'Tenki no Ko',
            'plot' => 'Set during a period of exceptionally rainy weather, high-school boy Hodaka Morishima runs away from his troubled rural home to Tokyo and befriends an orphan girl who can manipulate the weather.',
            'duration_in_minutes' => 112,
            'age_restriction' => 12,
            'country' => 'Japan',
            'language' => 'Japanese',
            'casts' => 'Kotaro Daigo',
            'cast_ids' => [2],
            'genres' => 'Anime, Romance, Drama, Animation, Fantasy',
            'genre_ids' => [1, 2, 3, 4, 5],
            'directors' => 'Makoto Shinkai',
            'director_ids' => [1],
            'authors' => 'Makoto Shinkai',
            'author_ids' => [1],
            'poster' => '',
            'wallpaper' => '',
            'video_trailer' => '',
            'title_logo' => '',
        ];

        $response = $this->put(
            "/api/coming-soon-movies/$id",
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_release_coming_soon_movie()
    {
        $id = 1;

        $data = [
            'video_path' => 'https://laravel-flicklify-files.s3.ap-southeast-1.amazonaws.com/movies/videos/Demon%20Slayer%20Season%202%20-%20Official%20Trailer_720P%20HD-1631719414.mp4',
            'duration_in_minutes' => 134,
            'video_size_in_mb' => 123124.1523
        ];

        $response = $this->put(
            "/api/coming-soon-movies/$id/release",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_update_coming_soon_movie_trailer()
    {
        $comingSoonMovieID  = 1;
        $trailerID = 2;

        $data = [
            'id' => $trailerID,
            'coming_soon_movie_id' => $comingSoonMovieID,
            'title' => 'Weathering with you trailer part II',
            'poster' => '',
            'wallpaper' => '',
            'video_trailer' => '',
            'title_logo' => '',
        ];

        $response = $this->put(
            "/api/coming-soon-movies/$comingSoonMovieID/trailers/$trailerID",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_upload_coming_soon_movie_poster()
    {
        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);

        $data = [
            'title' => '',
            'poster' => $poster
        ];

        $response = $this->put(
            "/api/coming-soon/movies/upload/poster",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_upload_coming_soon_movie_wallpaper()
    {
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg', 2000, 1500)->size(100);

        $data = [
            'title' => '',
            'wallpaper' => $wallpaper
        ];

        $response = $this->put(
            "/api/coming-soon/movies/upload/wallpaper",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_upload_coming_soon_movie_title_logo()
    {
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 1280, 288)->size(100);

        $data = [
            'title' => '',
            'title_logo' => $titleLogo
        ];

        $response = $this->put(
            "/api/coming-soon/movies/upload/title_logo",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_upload_coming_soon_movie_video()
    {
        $video = UploadedFile::fake()->image('video.mp4')->size(10000);

        $data = [
            'title' => '',
            'video' => $video
        ];

        $response = $this->put(
            "/api/coming-soon/movies/upload/video",
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_coming_soon_movies()
    {
        $data = [
            'ids' => [1]
        ];

        $response = $this->delete(
            '/api/coming-soon-movies',
            $data
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_coming_soon_movie_trailers()
    {
        $comingSoonMovieID = 1;
        $data = [
            'ids' => [3]
        ];

        $response = $this->delete(
            "/api/coming-soon-movies/$comingSoonMovieID/trailers",
            $data
        );

        $this->assertResponse($response);
    }
}
