<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieCastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_casts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id');
            $table->foreignId('cast_id');
            $table->timestamps();

            $table->unique([
                'movie_id',
                'cast_id'
            ]);

            $table->foreign('movie_id')
                ->references('id')
                ->on('movies')
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
        Schema::dropIfExists('movie_casts');
    }
}
