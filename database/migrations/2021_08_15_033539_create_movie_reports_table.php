<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('search_count')->default(0);
            $table->unsignedBigInteger('total_likes_within_a_day')->default(0);
            $table->unsignedBigInteger('total_views_within_a_day')->default(0);
            $table->unsignedBigInteger('total_likes_within_a_week')->default(0);
            $table->unsignedBigInteger('total_views_within_a_week')->default(0);
            $table->timestamp('current_date')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_reports');
    }
}
