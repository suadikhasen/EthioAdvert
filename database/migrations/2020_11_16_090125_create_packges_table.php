<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('package_name');
            $table->unsignedBigInteger('channel_level_id');
            $table->time('initial_posting_time_in_one_day');
            $table->time('final_postig_time_in_one_day');
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('number_of_days');
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
        Schema::dropIfExists('packges');
    }
}
