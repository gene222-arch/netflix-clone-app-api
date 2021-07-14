<?php

namespace Tests\Feature\Http\Controllers\Api\Movie;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MoviesControllerTest extends TestCase
{

    /** test */
    public function user_can_view_any_movies()
    {
        $response = $this->get(
            '/api/movies',
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_view_movie()
    {
        $id = 1;

        $response = $this->get(
            "/api/movies/$id",
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_create_movie()
    {
        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg')->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 100, 100)->size(100);
        $video = UploadedFile::fake()->image('video.mp4')->size(10000);

        $data = [
            'title' => 'Kimi no Na wa',
            'plot' => 'Two teenagers share a profound, magical connection upon discovering they are swapping bodies. Things manage to become even more complicated when the boy and girl decide to meet in person.',
            'year_of_release' => 2016,
            'date_of_release' => '12-14-2016',
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
            'video' => $titleLogo,
            'title_logo' => $video,
            'video_size_in_mb' => '325'
        ];

        $response = $this->post(
            '/api/movies',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_update_movie()
    {
        $poster = UploadedFile::fake()->image('poster.jpg', 500, 578)->size(100);
        $wallpaper = UploadedFile::fake()->image('wallpaper.jpg')->size(100);
        $titleLogo = UploadedFile::fake()->image('title_logo.png', 100, 100)->size(100);
        $video = UploadedFile::fake()->image('video.mp4')->size(10000);

        $data = [
            'title' => 'Kimi no Na wa',
            'plot' => 'Two teenagers share a profound, magical connection upon discovering they are swapping bodies. Things manage to become even more complicated when the boy and girl decide to meet in person.',
            'year_of_release' => 2016,
            'date_of_release' => '12-14-2016',
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
            'video' => $titleLogo,
            'title_logo' => $video,
            'video_size_in_mb' => '325'
        ];

        $response = $this->put(
            '/api/movies/',
            $data,
            $this->apiHeader()
        );

        $this->assertResponse($response);
    }


    /** test */
    public function user_can_delete_movies()
    {
        $data = [
            1
        ];

        $response = $this->delete(
            '/api/movies/',
            $data
        );

        $this->assertResponse($response);
    }

}
