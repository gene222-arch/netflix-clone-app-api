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

                $pathToStore = 'coming-soon-movies/' . str_replace(' ', '-', Str::lower($request->title));

                $poster = $this->upload($request, 'poster', $pathToStore);
                $wallpaper = $this->upload($request, 'wallpaper', $pathToStore);
                $titleLogo = $this->upload($request, 'title_logo', $pathToStore);
                $videoTrailer = $this->upload($request, 'video_trailer', $pathToStore);

                $comingSoonMovieData = array_merge($comingSoonMovieData, [
                    'poster_path' => $poster,
                    'wallpaper_path' => $wallpaper,
                    'title_logo_path' => $titleLogo,
                    'video_trailer_path' => $videoTrailer
                ]);

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

                if ($newPath !== $oldPath) {
                    /** Rename folder */
                    Storage::rename($oldPath, $newPath);
                    $pathToStore = $newPath;
                }
                else {
                    $pathToStore = $oldPath;
                }

                $poster = '';
                $wallpaper = '';
                $title_logo = '';
                $video_trailer = '';

                /** Get old data file's path */
                $oldFiles = [
                    'poster' => $comingSoonMovie->poster_path,
                    'wallpaper' => $comingSoonMovie->wallpaper_path,
                    'title_logo' => $comingSoonMovie->title_logo_path,
                    'video_trailer' => $comingSoonMovie->video_trailer_path
                ];

                /** Upload a file/image only if it exist within the request */
                foreach ($oldFiles as $fileName => $filePath) {
                    $$fileName = $request->hasFile($fileName) 
                        ? $this->upload($request, $fileName, $pathToStore)
                        : $filePath;
                }

                $comingSoonMovieData = array_merge($comingSoonMovieData, [
                    'poster_path' => $poster,
                    'wallpaper_path' => $wallpaper,
                    'title_logo_path' => $title_logo,
                    'video_trailer_path' => $video_trailer
                ]);

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
            'genre_ids', 
            'poster', 
            'wallpaper', 
            'title_logo', 
            'video_trailer'
        ]);
    }

    public function trailerCreate(TrailerStoreRequest $request, ComingSoonMovie $comingSoonMovie): Trailer
    {
        $trailerData = $this->filterTrailerData($request);

        $mainTrailerPath = "coming-soon-movies/" . str_replace(' ', '-', Str::lower($comingSoonMovie->title)) . "/more-trailers/";
        $pathToStore = $mainTrailerPath . str_replace(' ', '-', Str::lower($request->title));

        $poster = $this->upload($request, 'poster', $pathToStore);
        $wallpaper = $this->upload($request, 'wallpaper', $pathToStore);
        $titleLogo = $this->upload($request, 'title_logo', $pathToStore);
        $video = $this->upload($request, 'video', $pathToStore);

        $trailerData = array_merge($trailerData, [
            'poster_path' => $poster,
            'wallpaper_path' => $wallpaper,
            'title_logo_path' => $titleLogo,
            'video_path' => $video
        ]);

        return Trailer::create($trailerData);
    }

    public function trailerUpdate(TrailerUpdateRequest $request, ComingSoonMovie $comingSoonMovie, Trailer $trailer): bool
    {
        $trailerData = $this->filterTrailerData($request);

        $mainTrailerPath = "coming-soon-movies/" . str_replace(' ', '-', Str::lower($comingSoonMovie->title)) . "/more-trailers/";
        $oldPath = $mainTrailerPath . str_replace(' ', '-', Str::lower($trailer->title));
        $newPath = $mainTrailerPath . str_replace(' ', '-', Str::lower($request->title));

        /** Delete a file only if it exist within the request */
        $this->deleteFile($request, [
            'poster' => $trailer->poster_path,
            'wallpaper' => $trailer->wallpaper_path,
            'title_logo' => $trailer->title_logo_path,
            'video' => $trailer->video_path
        ]);

        if ($newPath !== $oldPath) {
            /** Rename folder */
            Storage::rename('public/' . $oldPath, 'public/' . $newPath);
            $pathToStore = $newPath;
        }
        else {
            $pathToStore = $oldPath;
        }

        $poster = '';
        $wallpaper = '';
        $title_logo = '';
        $video = '';

        /** Get old data file's path */
        $oldFiles = [
            'poster' => $trailer->poster_path,
            'wallpaper' => $trailer->wallpaper_path,
            'title_logo' => $trailer->title_logo_path,
            'video' => $trailer->video_trailer_path
        ];

        /** Upload a file/image only if it exist within the request */
        foreach ($oldFiles as $fileName => $filePath) {
            $$fileName = $request->hasFile($fileName) 
                ? $this->upload($request, $fileName, $pathToStore)
                : $filePath;
        }

        $trailerData = array_merge($trailerData, [
            'poster_path' => $poster,
            'wallpaper_path' => $wallpaper,
            'title_logo_path' => $title_logo,
            'video_path' => $video
        ]);

        return $trailer->update($trailerData);
    }

    protected function filterTrailerData($request): array
    {
        return $request->except([
            'poster', 
            'wallpaper', 
            'title_logo', 
            'video'
        ]);
    }
}