<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToUserCamapignLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_camapign_logs', function (Blueprint $table) {
            $table->string('firstname')->nullable()->after('model_type');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('mobileno')->nullable()->after('lastname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_camapign_logs', function (Blueprint $table) {
            //
        });
    }
}
