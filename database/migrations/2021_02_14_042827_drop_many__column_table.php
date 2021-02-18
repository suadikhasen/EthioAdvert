<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropManyColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ethio_advert_posts', function (Blueprint $table) {
            $table->dropColumn('payment_per_view');
            $table->dropColumn('package_id');
            $table->dropColumn('level_id');
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
