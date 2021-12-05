<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_directors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->foreignId('director_id');

            $table->unique([
                'movie_id',
                'director_id'
            ]);

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->cascadeOnDelete();

            $table->foreign('director_id')
                ->references('id')
                ->on('directors')
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
        Schema::dropIfExists('movie_directors');
    }
}
