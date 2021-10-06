<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->char('type', 8)->index();
            $table->unsignedDouble('cost');
            $table->unsignedDouble('paid_amount')->default(0);
            $table->boolean('is_first_subscription')->default(false);
            $table->boolean('is_cancelled')->default(false)->index();
            $table->boolean('is_expired')->default(false)->index();
            $table->timestamp('subscribed_at')->default(Carbon::now());
            $table->timestamp('expired_at')->default(Carbon::now());
            $table->timestamp('cancelled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
