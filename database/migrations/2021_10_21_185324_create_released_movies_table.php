<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasedMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('released_movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained();
            $table->foreignId('coming_soon_movie_id')->constrained();
            $table->timestamp('released_at')->default(Carbon::now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('released_movies');
    }
}
