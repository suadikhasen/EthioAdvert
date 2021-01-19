<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddPercentsColumnChannelLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chanel_level', function (Blueprint $table) {
            $table->renameColumn('percentage_value','minimum_percentage_value');
            $table->integer('maximum_percentage_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chanel_level', function (Blueprint $table) {
            $table->dropColumn('maximum_percentage_value');

        });
    }
}
