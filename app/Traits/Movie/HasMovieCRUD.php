<?php

namespace App\Traits\Movie;

use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload\HasUploadable;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait HasMovieCRUD
{
    use HasUploadable;

    public function createMovie(StoreRequest $request): bool|string
    {
        try {
            DB::transaction(function () use ($request)
            {
                $movieData = $this->filterMovieData($request);
                $authorIDs = explode(',', $request->author_ids);
                $castIDs = explode(',', $request->cast_ids);
                $directorIDs = explode(',', $request->director_ids);
                $genreIDs = explode(',', $request->genre_ids);
                
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
                /** Store data */
                $movieData = $this->filterMovieData($request);
                $authorIDs = explode(',', $request->author_ids);
                $castIDs = explode(',', $request->cast_ids);
                $directorIDs = explode(',', $request->director_ids);
                $genreIDs = explode(',', $request->genre_ids);

                $oldPath = 'public/movies/' . str_replace(' ', '-', Str::lower($movie->title));
                $newPath = 'public/movies/' . str_replace(' ', '-', Str::lower($request->title));

                /** Delete a file only if it exist within the request */
                $this->deleteFile($request, [
                    'poster_path' => $movie->poster_path,
                    'wallpaper_path' => $movie->wallpaper_path,
                    'title_logo_path' => $movie->title_logo_path,
                    'video_path' => $movie->video_path
                ]);

                Storage::rename($oldPath, $newPath);

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