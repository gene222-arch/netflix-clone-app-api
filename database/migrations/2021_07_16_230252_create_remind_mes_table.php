<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindMesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remind_mes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('user_profile_id');
            $table->foreignId('coming_soon_movie_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('reminded_at')->default(now());
            $table->boolean('is_released')->default(false);

            $table->unique([
                'user_profile_id',
                'coming_soon_movie_id'
            ]);
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('user_profile_id')
                ->references('id')
                ->on('user_profiles')
                ->cascadeOnDelete();

            $table->foreign('coming_soon_movie_id')
                ->references('id')
                ->on('coming_soon_movies')
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
        Schema::dropIfExists('remind_mes');
    }
}
