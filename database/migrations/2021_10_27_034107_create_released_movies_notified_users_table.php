<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasedMoviesNotifiedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('released_movies_notified_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('released_movie_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('ip_address');
            $table->timestamp('notified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('released_movies_notified_users');
    }
}
