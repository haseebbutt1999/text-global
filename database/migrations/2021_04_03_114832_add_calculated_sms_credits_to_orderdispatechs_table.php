<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalculatedSmsCreditsToOrderdispatechsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orderdispatches', function (Blueprint $table) {
            $table->bigInteger('calculated_credit_per_sms')->nullable()->after('sender_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orderdispatches', function (Blueprint $table) {
            //
        });
    }
}
