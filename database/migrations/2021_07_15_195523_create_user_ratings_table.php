<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('user_profile_id');
            $table->foreignId('movie_id');
            $table->char('model_type', 60);
            $table->boolean('like')->default(0);
            $table->boolean('dislike')->default(0);
            $table->char('rate', 10)->default('unrated');
            $table->timestamp('rated_at')->default(now());

            $table->unique([
                'user_profile_id',
                'movie_id',
                'model_type'
            ], 'unique_rating');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('user_profile_id')
                ->references('id')
                ->on('user_profiles')
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
        Schema::dropIfExists('user_ratings');
    }
}
