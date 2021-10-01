<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimilarMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('similar_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id');
            $table->string('model_type');
            $table->foreignId('similar_movie_id')->constrained('movies', 'id');

            $table->index([
                'model_id',
                'similar_movie_id',
                'model_type'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('similar_movies');
    }
}
