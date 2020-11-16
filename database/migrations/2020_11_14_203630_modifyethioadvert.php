<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Modifyethioadvert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ethio_advert_posts', function (Blueprint $table) {
            $table->renameColumn('initial_date','et_calandar_initial_date');
            $table->renameColumn('final_date','et_calendar_final_date');
            $table->date('gc_calendar_initial_date');
            $table->date('gc_calendar_final_date');
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
            $table->dropColumn('gc_calendar_initial_date');
            $table->dropColumn('gc_calendar_final_date');
        });
    }
}
