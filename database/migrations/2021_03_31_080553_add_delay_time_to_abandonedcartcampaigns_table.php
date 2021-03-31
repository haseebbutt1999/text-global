<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDelayTimeToAbandonedcartcampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('abandonedcartcampaigns', function (Blueprint $table) {
            $table->integer('delay_time')->nullable()->after('sender_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('abandonedcartcampaigns', function (Blueprint $table) {
            //
        });
    }
}
