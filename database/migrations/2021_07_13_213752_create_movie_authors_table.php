<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->foreignId('author_id');
            $table->timestamps();

            $table->unique([
                'movie_id',
                'author_id'
            ]);

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
                ->cascadeOnDelete();

            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
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
        Schema::dropIfExists('movie_authors');
    }
}
