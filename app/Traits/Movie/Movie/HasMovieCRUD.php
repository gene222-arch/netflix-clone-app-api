<?php

namespace App\Traits\Movie\Movie;

use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use Illuminate\Support\Facades\File;

trait HasMovieCRUD
{
    use HasUploadable;

    public function createMovie(StoreRequest $request): bool|string
    {
        try {
            DB::transaction(function () use ($request)
            {
                $movieData = $request->except(['author_ids', 'cast_ids', 'director_ids', 'genre_ids', 'poster', 'wallpaper', 'title_logo', 'video']);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

                $pathToStore = 'movies/' . trim(Str::lower($request->title));

                $poster = $this->upload($request, 'poster', $pathToStore);
                $wallpaper = $this->upload($request, 'wallpaper', $pathToStore);
                $title_logo = $this->upload($request, 'title_logo', $pathToStore);
                $video = $this->upload($request, 'video', $pathToStore);

                $movieData = array_merge(
                    $movieData,
                    [
                        'poster_path' => $poster,
                        'wallpaper_path' => $wallpaper,
                        'title_logo_path' => $title_logo,
                        'video_path' => $video
                    ],
                );

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

    /**
     * Todo: prevent updating the file/image all at once, only if the file is not null in the request
     */
    public function updateMovie(UpdateRequest $request, Movie $movie): bool|string
    {
        try {
            DB::transaction(function () use ($request, $movie)
            {   
                $movieData = $request->except(['author_ids', 'cast_ids', 'director_ids', 'genre_ids', 'poster', 'wallpaper', 'title_logo', 'video']);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

                $oldFiles = [
                    'poster' => $movie->poster_path,
                    'wallpaper' => $movie->wallpaper_path,
                    'title_logo' => $movie->title_logo_path,
                    'video' => $movie->video_path
                ];

                $pathToStore = 'movies/' . trim(Str::lower($request->title));

                $poster = '';
                $wallpaper = '';
                $title_logo = '';
                $video = '';

                foreach ($oldFiles as $fileName => $filePath) {
                    if ($request->hasFile($fileName)) {
                       $$fileName = $this->upload($request, $fileName, $pathToStore);
                    }
                }

                $this->deleteFile($request, [
                    'poster' => $movie->poster_path,
                    'wallpaper' => $movie->wallpaper_path,
                    'title_logo' => $movie->title_logo_path,
                    'video' => $movie->video_path
                ]);

                $movieData = array_merge($movieData, [
                    'poster_path' => $poster,
                    'wallpaper_path' => $wallpaper,
                    'title_logo_path' => $title_logo,
                    'video_path' => $video
                ]);

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
}