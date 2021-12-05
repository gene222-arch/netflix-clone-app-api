<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComingSoonMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coming_soon_movies', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('plot');
            $table->unsignedDouble('duration_in_minutes');
            $table->unsignedInteger('age_restriction');
            $table->string('country');
            $table->string('language');
            $table->string('casts');
            $table->string('genres');
            $table->string('directors');
            $table->string('authors');
            $table->string('poster_path');
            $table->string('wallpaper_path');
            $table->string('video_trailer_path');
            $table->string('title_logo_path');
            $table->string('status')->default('Coming Soon');
            $table->timestamp('released_at')->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('coming_soon_movie_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coming_soon_movie_id');
            $table->foreignId('genre_id');

            $table->unique([
                'coming_soon_movie_id',
                'genre_id'
            ]);

            $table->foreign('coming_soon_movie_id')
                ->references('id')
                ->on('coming_soon_movies')
                ->cascadeOnDelete();

            $table->foreign('genre_id')
                ->references('id')
                ->on('genres')
                ->cascadeOnDelete();
        });

        Schema::create('coming_soon_movie_directors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coming_soon_movie_id');
            $table->foreignId('director_id');

            $table->unique([
                'coming_soon_movie_id',
                'director_id'
            ], 'csm_coming_soon_movie_id_director_id_unique');

            $table->foreign('coming_soon_movie_id')
                ->references('id')
                ->on('coming_soon_movies')
                ->cascadeOnDelete();

            $table->foreign('director_id')
                ->references('id')
                ->on('directors')
                ->cascadeOnDelete();
        });

        Schema::create('coming_soon_movie_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coming_soon_movie_id');
            $table->foreignId('author_id');

            $table->unique([
                'coming_soon_movie_id',
                'author_id'
            ]);

            $table->foreign('coming_soon_movie_id')
                ->references('id')
                ->on('coming_soon_movies')
                ->cascadeOnDelete();

            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->cascadeOnDelete();
        });

        Schema::create('coming_soon_movie_casts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coming_soon_movie_id');
            $table->foreignId('cast_id');

            $table->unique([
                'coming_soon_movie_id',
                'cast_id'
            ]);

            $table->foreign('coming_soon_movie_id')
                ->references('id')
                ->on('coming_soon_movies')
                ->cascadeOnDelete();

            $table->foreign('cast_id')
                ->references('id')
                ->on('casts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coming_soon_movie_casts');
        Schema::dropIfExists('coming_soon_movie_authors');
        Schema::dropIfExists('coming_soon_movie_directors');
        Schema::dropIfExists('coming_soon_movie_genres');
        Schema::dropIfExists('coming_soon_movies');
    }
}
