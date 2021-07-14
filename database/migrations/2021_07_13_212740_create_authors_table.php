<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('pseudonyms')->unique();
            $table->string('birth_name');
            $table->char('gender', 6);
            $table->unsignedDouble('height_in_cm', 5, 2)->default(0);
            $table->text('biographical_information');
            $table->string('birth_details');
            $table->timestamp('date_of_birth')->nullable();
            $table->string('place_of_birth');
            $table->string('death_details');
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
        Schema::dropIfExists('authors');
    }
}
