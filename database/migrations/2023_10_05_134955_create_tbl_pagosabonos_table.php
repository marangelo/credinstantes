<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPagosabonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pagosabonos', function (Blueprint $table) {
            $table->increments('id_pagoabono');
            $table->integer('id_creditos')->nullable();
            $table->integer('numero_pago')->nullable();
            $table->dateTime('FechaPago')->nullable();
            $table->engine = 'InnoDB';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_pagosabonos');
    }
}
