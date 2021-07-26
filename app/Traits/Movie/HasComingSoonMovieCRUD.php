<?php

namespace App\Traits\Movie;

use Illuminate\Support\Str;
use App\Models\ComingSoonMovie;
use Illuminate\Support\Facades\DB;
use App\Traits\Upload\HasUploadable;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Movie\ComingSoonMovie\StoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerStoreRequest;
use App\Http\Requests\Movie\ComingSoonMovie\TrailerUpdateRequest;
use App\Http\Requests\Movie\ComingSoonMovie\UpdateRequest;
use App\Models\Trailer;

trait HasComingSoonMovieCRUD
{
    use HasUploadable;

    public function createComingSoonMovie(StoreRequest $request): bool|string
    {
        try {
            DB::transaction(function () use ($request)
            {
                $comingSoonMovieData = $this->filterComingSoonMovieData($request);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

                $comingSoonMovie = ComingSoonMovie::create($comingSoonMovieData);
                
                $comingSoonMovie->authors()->attach($authorIDs);
                $comingSoonMovie->casts()->attach($castIDs);
                $comingSoonMovie->directors()->attach($directorIDs);
                $comingSoonMovie->genres()->attach($genreIDs);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
        return true;
    }

    public function updateComingSoonMovie(UpdateRequest $request, ComingSoonMovie $comingSoonMovie): bool|string
    {
        try {
            DB::transaction(function () use ($request, $comingSoonMovie)
            {   
                $comingSoonMovieData = $this->filterComingSoonMovieData($request);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

                $oldPath = 'public/coming-soon-movies/' . str_replace(' ', '-', Str::lower($comingSoonMovie->title));
                $newPath = 'public/coming-soon-movies/' . str_replace(' ', '-', Str::lower($request->title));

                /** Delete a file only if it exist within the request */
                $this->deleteFile($request, [
                    'poster' => $comingSoonMovie->poster_path,
                    'wallpaper' => $comingSoonMovie->wallpaper_path,
                    'title_logo' => $comingSoonMovie->title_logo_path,
                    'video_trailer' => $comingSoonMovie->video_trailer_path
                ]);

                if ($oldPath !== $newPath) {
                    Storage::rename($oldPath, $newPath);
                }

                $comingSoonMovie->update($comingSoonMovieData);
                $comingSoonMovie->authors()->sync($authorIDs);
                $comingSoonMovie->casts()->sync($castIDs);
                $comingSoonMovie->directors()->sync($directorIDs);
                $comingSoonMovie->genres()->sync($genreIDs);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
        return true;
    }

    protected function filterComingSoonMovieData($request): array
    {
        return $request->except([
            'author_ids', 
            'cast_ids', 
            'director_ids', 
            'genre_ids'
        ]);
    }

}