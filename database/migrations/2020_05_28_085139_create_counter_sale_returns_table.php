<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_sale_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('counter_sale_details_id');
            $table->integer('medicine_id');
            $table->string('stock_id');
            $table->date('expiry_date');
            $table->double('quantity');
            $table->double('MRP');
            $table->double('amount');
            $table->integer('user_id');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('counter_sale_returns');
    }
}
