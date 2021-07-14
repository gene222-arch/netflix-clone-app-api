<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directors', function (Blueprint $table) {
            $table->id();
            $table->string('pseudonyms')->unique();
            $table->string('birth_name');
            $table->char('gender', 6);
            $table->unsignedDouble('height_in_cm', 5, 2)->default(0);
            $table->text('biographical_information')->nullable();
            $table->string('birth_details')->nullable();
            $table->timestamp('date_of_birth')->default(now());
            $table->string('place_of_birth')->nullable();
            $table->string('death_details')->nullable();
            $table->timestamp('date_of_death')->nullable();
            $table->boolean('enabled');
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
        Schema::dropIfExists('directors');
    }
}
