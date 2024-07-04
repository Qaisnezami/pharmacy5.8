<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('barcode')->unique();
            $table->string('name');
            $table->integer('group_id')->nullable();
            $table->integer('classification_id')->nullable();
            $table->integer('manufacturer_id')->nullable();
            $table->integer('doctor_id')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('user_id');
            $table->tinyInteger('isactive');
            $table->tinyInteger('approve');
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
        Schema::dropIfExists('medicines');
    }
}
