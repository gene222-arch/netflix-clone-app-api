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
        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg', 2000, 1500)->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 1280, 288)->size(100);
        $videoTrailer = UploadedFile::fake()->image('video_trailer.mp4')->size(1000);

        $data = [
            'title' => 'Weathering With You',
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
            'poster' => $poster,
            'wallpaper' => $wallpaper,
            'video_trailer' => $videoTrailer,
            'title_logo' => $titleLogo,
        ];

        $response = $this->post(
            '/api/coming-soon-movies',
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));
        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_coming_soon_movie_trailer()
    {
        $id  = 1;

        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg', 2000, 1500)->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 1280, 288)->size(100);
        $video = UploadedFile::fake()->image('video.mp4')->size(1000);

        $data = [
            'coming_soon_movie_id' => $id,
            'title' => 'Kimi no Na wa Trailer II',
            'poster' => $poster,
            'wallpaper' => $wallpaper,
            'video' => $video,
            'title_logo' => $titleLogo,
        ];

        $response = $this->post(
            "/api/coming-soon-movies/$id/trailers",
            $data,
            $this->apiHeader()
        );

        dd(json_decode($response->getContent()));

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_coming_soon_movie()
    {
        $id = 1;

        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg', 2000, 1500)->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 1280, 288)->size(100);
        $videoTrailer = UploadedFile::fake()->image('video_trailer.mp4')->size(1000);

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
            'poster' => $poster,
            'wallpaper' => $wallpaper,
            'video_trailer' => $videoTrailer,
            'title_logo' => $titleLogo,
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
    public function user_can_update_coming_soon_movie_status()
    {
        $id = 1;

        $response = $this->put(
            "/api/coming-soon-movies/$id/status",
            [],
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_update_coming_soon_movie_trailer()
    {
        $comingSoonMovieID  = 1;
        $trailerID = 2;

        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg', 2000, 1500)->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 1280, 288)->size(100);
        $video = UploadedFile::fake()->image('video.mp4')->size(1000);

        $data = [
            'id' => $trailerID,
            'coming_soon_movie_id' => $comingSoonMovieID,
            'title' => 'Weathering with you trailer part II',
            'poster' => $poster,
            'wallpaper' => $wallpaper,
            'video' => $video,
            'title_logo' => $titleLogo,
        ];

        $response = $this->put(
            "/api/coming-soon-movies/$comingSoonMovieID/trailers/$trailerID",
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