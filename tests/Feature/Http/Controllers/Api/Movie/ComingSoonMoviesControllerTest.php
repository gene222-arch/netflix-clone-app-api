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
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg')->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 100, 100)->size(100);
        $videoTrailer = UploadedFile::fake()->image('video_trailer.mp4')->size(1000);

        $data = [
            'title' => 'Kimi no Na wa',
            'plot' => 'Two teenagers share a profound, magical connection upon discovering they are swapping bodies. Things manage to become even more complicated when the boy and girl decide to meet in person.',
            'duration_in_minutes' => 107,
            'age_restriction' => 12,
            'country' => 'Japan',
            'language' => 'Japanese',
            'casts' => 'Mone Kamishiraishi, Ryûnosuke Kamiki, Aoi Yūki',
            'genres' => 'Anime, Romance, Drama, Animation, Fantasy',
            'directors' => 'Makoto Shinkai',
            'authors' => 'Makoto Shinkai',
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

        $this->assertResponse($response);
    }

    /** test */
    public function user_can_create_coming_soon_movie_trailer()
    {
        $id  = 1;

        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg')->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 100, 100)->size(100);
        $videoTrailer = UploadedFile::fake()->image('video_trailer.mp4')->size(1000);

        $data = [
            'coming_soon_movie_id' => $id,
            'title' => 'Kimi no Na wa',
            'poster' => $poster,
            'wallpaper' => $wallpaper,
            'video_trailer' => $videoTrailer,
            'title_logo' => $titleLogo,
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
        $id = 2;

        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg')->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 100, 100)->size(100);
        $videoTrailer = UploadedFile::fake()->image('video.mp4')->size(1000);

        $data = [
            'title' => 'Kimi no Na wa',
            'plot' => 'Two teenagers share a profound, magical connection upon discovering they are swapping bodies. Things manage to become even more complicated when the boy and girl decide to meet in person.',
            'duration_in_minutes' => 107,
            'age_restriction' => 12,
            'country' => 'Japan',
            'language' => 'Japanese',
            'casts' => 'Mone Kamishiraishi, Ryûnosuke Kamiki, Aoi Yūki',
            'genres' => 'Anime, Romance, Drama, Animation, Fantasy',
            'directors' => 'Makoto Shinkai',
            'authors' => 'Makoto Shinkai',
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

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_coming_soon_movie_trailer()
    {
        $comingSoonMovieID  = 1;
        $trailerID = 1;

        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg')->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 100, 100)->size(100);
        $videoTrailer = UploadedFile::fake()->image('video_trailer.mp4')->size(1000);

        $data = [
            'coming_soon_movie_id' => $comingSoonMovieID,
            'title' => 'Kimi no Na wa',
            'poster' => $poster,
            'wallpaper' => $wallpaper,
            'video_trailer' => $videoTrailer,
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
            'ids' => [1]
        ];

        $response = $this->delete(
            "/api/coming-soon-movies/$comingSoonMovieID/trailers",
            $data
        );

        $this->assertResponse($response);
    }
}
