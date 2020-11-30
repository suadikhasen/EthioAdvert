<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModify1EthioAdvertPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ethio_advert_posts', function (Blueprint $table) {
            $table->date('gc_calendar_initial_date')->nullable()->change();
            $table->date('gc_calendar_final_date')->nullable()->change();
            $table->integer('payment_code')->nullable()->change();
            $table->integer('no_view')->nullable()->change();
            $table->float('payment_per_view')->nullable()->change();
            $table->integer('number_of_channel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ethio_advert_posts', function (Blueprint $table) {
            $table->dropColumn('number_of_channel');
        });
    }
}
