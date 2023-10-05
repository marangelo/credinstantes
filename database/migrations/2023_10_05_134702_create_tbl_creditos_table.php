<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_creditos', function (Blueprint $table) {
            $table->id('id_creditos');
            $table->integer('creado_por');
            $table->integer('id_diassemana');
            $table->integer('id_clientes');
            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_ultimo_abono')->nullable();
            $table->dateTime('fecha_culmina')->nullable();
            $table->decimal('monto_credito', 19, 4);
            $table->decimal('plazo', 5, 2);
            $table->decimal('taza_interes', 5, 2);
            $table->decimal('numero_cuotas', 5, 2);
            $table->decimal('total', 28, 8)->nullable();
            $table->decimal('cuota', 35, 13)->nullable();
            $table->decimal('saldo', 19, 4)->default(0.0000);
            $table->decimal('interes', 28, 8)->nullable();
            $table->decimal('intereses_por_cuota', 35, 13)->nullable();
            $table->integer('salud_credito')->nullable()->default(1);
            $table->integer('estado_credito')->nullable();
            $table->boolean('activo')->nullable();
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
        Schema::dropIfExists('tbl_creditos');
    }
}
