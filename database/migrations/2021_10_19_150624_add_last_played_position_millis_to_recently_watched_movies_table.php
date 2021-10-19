<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastPlayedPositionMillisToRecentlyWatchedMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recently_watched_movies', function (Blueprint $table) {
            $table->unsignedBigInteger('last_played_position_millis')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recently_watched_movies', function (Blueprint $table) {
            $table->dropColumn('last_played_position_millis');
        });
    }
}
