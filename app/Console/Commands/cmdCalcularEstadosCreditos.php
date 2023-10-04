<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScheduleController;

class cmdCalcularEstadosCreditos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:CalcularEstadosCredito';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calcula los estados de Credito por cada cliente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scheduleController = new ScheduleController();
        $scheduleController->RunCalc();

        $this->info('Tarea de Calculo ejecutado correctamente.');
    }
}
