<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('shopify_address_id')->nullable();
            $table->bigInteger('shopify_customer_id')->nullable();

            $table->bigInteger('shopify_shope_id')->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("company")->nullable();
            $table->text("address1")->nullable();
            $table->text("address2")->nullable();
            $table->string("city")->nullable();
            $table->string("province")->nullable();
            $table->string("country")->nullable();
            $table->string("zip")->nullable();
            $table->string("phone")->nullable();
            $table->string("name")->nullable();
            $table->string("province_code")->nullable();
            $table->string("country_code")->nullable();
            $table->string("country_name")->nullable();
            $table->string("default")->nullable();


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
        Schema::dropIfExists('addresses');
    }
}
