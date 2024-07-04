<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounterSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('BillNo')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->String('patient_name')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->integer('user_id')->nullable();
            $table->double('total')->nullable();
            $table->double('remaind')->nullable();
            $table->double('discount')->nullable();
            $table->double('paid')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('counter_sales');
    }
}
