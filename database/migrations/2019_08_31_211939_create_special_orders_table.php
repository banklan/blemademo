<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('special_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no');
            $table->bigInteger('user_id')->unsigned();
            $table->string('request');
            $table->text('special_req')->nullable();
            $table->text('details');
            $table->string('units');
            $table->date('del_date');
            $table->time('del_time');
            $table->string('order_status')->default(1)->nullable();
            $table->boolean('payment_status')->nullable();
            $table->string('cost')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_orders');
    }
}
