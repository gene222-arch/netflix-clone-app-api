<?php

namespace App\Traits\Movie\Movie;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\Movie\Movie\StoreRequest;
use App\Http\Requests\Movie\Movie\UpdateRequest;
use App\Models\Movie;

trait HasMovieCRUD
{

    public function createMovie(StoreRequest $request): bool|string
    {
        try {
            DB::transaction(function () use ($request)
            {
                $movieData = $request->except(['author_ids', 'cast_ids', 'director_ids', 'genre_ids']);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

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

    public function updateMovie(UpdateRequest $request, Movie $movie): bool|string
    {
        try {
            DB::transaction(function () use ($request, $movie)
            {   
                $movieData = $request->except(['author_ids', 'cast_ids', 'director_ids', 'genre_ids']);
                $authorIDs = $request->author_ids;
                $castIDs = $request->cast_ids;
                $directorIDs = $request->director_ids;
                $genreIDs = $request->genre_ids;

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