<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Consolidado extends Model {
    public $timestamps = false;
    protected $table = "tbl_consolidados";

    /**
     * Eliminar los últimos cinco días a partir de una fecha dada.
     *
     * @param string $date La fecha a partir de la cual se eliminarán los últimos cinco días.
     * @return void
     */
    public static function deleteLastThreeDays($date) {
        // Obtener la fecha actual si no se proporciona ninguna fecha
        if (!$date) {
            $date = Carbon::now()->toDateString();
        }

        // Calcular la fecha cinco días atrás
        $fiveDaysAgo = Carbon::parse($date)->subDays(4)->toDateString();

        // Eliminar los registros correspondientes a los últimos cinco días
        self::where('fecha', '>=', $fiveDaysAgo)->delete();
    }
}
