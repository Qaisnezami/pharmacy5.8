<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_sale_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('counter_sale_id');
            $table->integer('medicine_id');
            $table->string('stock_id');
            $table->date('expiry_date');
            $table->double('quantity');
            $table->double('MRP');
            $table->double('amount');
            $table->tinyInteger('status');
            $table->integer('user_id');
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
        Schema::dropIfExists('counter_sale_details');
    }
}
