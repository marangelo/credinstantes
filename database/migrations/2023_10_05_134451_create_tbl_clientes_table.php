<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_clientes', function (Blueprint $table) {
            $table->increments('id_clientes');
            $table->integer('id_municipio');
            $table->string('nombre', 150);
            $table->string('apellidos', 150);
            $table->string('direccion_domicilio', 250);
            $table->string('cedula', 50);
            $table->string('telefono', 20);
            $table->integer('score')->default(100);
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_clientes');
    }
}
