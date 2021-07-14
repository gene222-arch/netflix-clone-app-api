<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('plot');
            $table->year('year_of_release');
            $table->timestamp('date_of_release')->default(now());
            $table->unsignedInteger('duration_in_minutes');
            $table->unsignedInteger('age_restriction');
            $table->string('country');
            $table->string('language');
            $table->string('casts');
            $table->string('genres');
            $table->string('directors');
            $table->string('authors');
            $table->string('poster_path');
            $table->string('wallpaper_path');
            $table->string('video_path');
            $table->string('title_logo_path');
            $table->string('video_size_in_mb');
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
        Schema::dropIfExists('movies');
    }
}
