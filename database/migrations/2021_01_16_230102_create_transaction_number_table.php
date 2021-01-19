<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_number', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('ref_number');
            $table->unsignedBigInteger('payment_method_code');
            $table->boolean('used_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_number');
    }
}
