<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEthioAdvertPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ethio_advert_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('advertiser_id');
            $table->string('text_message');
            $table->string('image_path');
            $table->date('initial_date');
            $table->date('final_date');
            $table->integer('no_view');
            $table->boolean('active_status');
            $table->boolean('approve_status');
            $table->boolean('payment_status');
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
        Schema::dropIfExists('ethio_advert_posts');
    }
}
