<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComingSoonMovieTrailersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coming_soon_movie_trailers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coming_soon_movie_id');
            $table->string('title')->unique();
            $table->string('poster_path');
            $table->string('wallpaper_path');
            $table->string('title_logo_path');
            $table->string('video_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coming_soon_movie_trailers');
    }
}
