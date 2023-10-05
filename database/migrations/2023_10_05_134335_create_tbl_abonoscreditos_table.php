<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAbonoscreditosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_abonoscreditos', function (Blueprint $table) {
            $table->increments('id_abonoscreditos');
            $table->integer('id_creditos');
            $table->integer('registrado_por')->nullable();
            $table->dateTime('fecha_cuota')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('pago_capital', 19, 4)->default(0.0000);
            $table->decimal('pago_intereses', 19, 4)->nullable();
            $table->decimal('cuota_credito', 19, 4)->default(0.0000);
            $table->decimal('cuota_cobrada', 19, 4)->default(0.0000);
            $table->decimal('intereses_por_cuota', 19, 4)->nullable();
            $table->decimal('abono_dia1', 19, 4)->nullable();
            $table->decimal('abono_dia2', 19, 4)->nullable();
            $table->dateTime('fecha_cuota_secc1')->nullable();
            $table->dateTime('fecha_cuota_secc2')->nullable();
            $table->dateTime('fecha_programada')->nullable();
            $table->boolean('completado')->nullable();
            $table->decimal('saldo_cuota', 19, 4)->nullable();
            $table->decimal('saldo_anterior', 19, 4)->nullable();
            $table->decimal('saldo_actual', 19, 4)->nullable();
            $table->boolean('activo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_abonoscreditos');
    }
}
