<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEtCalendarColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ethio_advert_posts', function (Blueprint $table) {
            $table->dropColumn('et_calandar_initial_date');
            $table->dropColumn('et_calendar_final_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ethio_advert_post', function (Blueprint $table) {
            //
        });
    }
}
