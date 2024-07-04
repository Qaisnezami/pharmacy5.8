<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('BillNo')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->String('supplier_ref')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('user_id')->nullable();
            $table->double('total')->nullable();
            $table->double('remind')->nullable();
            $table->double('discount')->nullable();
            $table->double('paid')->nullable();
            $table->tinyInteger('approve')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
