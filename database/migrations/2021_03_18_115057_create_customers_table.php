<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_customer_id');
            $table->bigInteger('user_id')->nullable();

            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->string("currency")->nullable();
            $table->string("accepts_marketing")->nullable();
            $table->string("state")->nullable();
            $table->text("addresses")->nullable();
            $table->text("note")->nullable();
            $table->integer("orders_count")->nullable();
            $table->float("total_spent")->nullable();
            $table->bigInteger("last_order_id")->nullable();
            $table->boolean('verified_email')->nullable();
            $table->timestamp('accepts_marketing_updated_at')->nullable();
            $table->string('marketing_opt_in_level')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
