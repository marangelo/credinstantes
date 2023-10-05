<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatDepartamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_departamento', function (Blueprint $table) {
            $table->increments('id_departamento');
            $table->string('nombre_departamento', 30)->charset('latin1')->collation('latin1_swedish_ci')->nullable();
            $table->integer('activo')->default(1);
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
        Schema::dropIfExists('cat_departamento');
    }
}
