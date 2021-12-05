<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_genres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->foreignId('genre_id');

            $table->unique([
                'movie_id',
                'genre_id'
            ]);

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->cascadeOnDelete();

            $table->foreign('genre_id')
                ->references('id')
                ->on('genres')
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
        Schema::dropIfExists('movie_genres');
    }
}
