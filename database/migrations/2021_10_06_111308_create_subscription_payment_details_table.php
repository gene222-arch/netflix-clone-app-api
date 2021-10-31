<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id');
            $table->string('payment_method');
            $table->unsignedDouble('amount', 10, 2);
            $table->timestamp('paid_at')->default(now());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_payment_details');
    }
}
