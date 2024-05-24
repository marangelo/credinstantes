<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Zonas;
use App\Models\MetricasHistory;

class CalcularMetricas extends Command
{
    // El nombre y la firma del comando de la consola.
    protected $signature = 'metricas:calcular';

    // La descripción del comando de la consola.
    protected $description = 'Calcular métricas diariamente';

   // Ejecuta el comando de la consola.
    public function handle()
    {
        // Tu lógica para calcular las métricas
        $zonas = Zonas::all();
        $dt_end = date('Y-m-d H:i:s');

        MetricasHistory::insert([
            'Fecha'             => $dt_end,
            'INGRESO'           => 0,
            'CAPITAL'           => 0,
            'INTERESES'         => 0,
            'UTIL_NETA'         => 0,
            'SALDOS_CARTERA'    => 0,
            'MORA_ATRASADA'     => 0,
            'MORA_VENCIDA'      => 0,
            'clientes_activos'  => 0,
            
        ]);
    }
}
