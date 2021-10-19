<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationInMillisToRecentlyWatchedMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recently_watched_movies', function (Blueprint $table) {
            $table->unsignedBigInteger('duration_in_millis')->default(0);
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
            $table->dropColumn('duration_in_millis');
        });
    }
}
