<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalculatedSmsCreditsToWorderconfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orderconfirms', function (Blueprint $table) {
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
        Schema::table('orderconfirms', function (Blueprint $table) {
            //
        });
    }
}
