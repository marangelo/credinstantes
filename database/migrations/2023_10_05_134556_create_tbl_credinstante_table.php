<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCredinstanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_credinstante', function (Blueprint $table) {
            $table->increments('id_credinstante');
            $table->integer('id_municipio');
            $table->integer('UserId')->default(1);
            $table->string('cargo_responsable', 100)->nullable();
            $table->string('telefono', 100)->nullable();
            $table->string('nombre_sucursal', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_credinstante');
    }
}
