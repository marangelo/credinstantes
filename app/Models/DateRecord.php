<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DateRecord extends Model {
    public $timestamps = false;
    protected $table = "tbl_logs";    
    protected $primaryKey = 'id_log';

    public static function Check()
    {
        // Obten la fecha actual en el formato deseado (puedes ajustar el formato según tus necesidades)
        $fechaActual = Carbon::now()->format('Y-m-d');

        // Busca un registro en tbl_logs que tenga la fecha actual
        $registroHoy = DateRecord::whereDate('created_at', $fechaActual)->first();

       // Verifica si se encontró un registro
        if ($registroHoy) {
            // Si se encontró un registro, retorna falso
            return false;
        } else {
            // Si no se encontró un registro, crea uno nuevo
            $nuevoRegistro = new DateRecord();
            $nuevoRegistro->created_at = Carbon::now();
            $nuevoRegistro->updated_at = Carbon::now();
            $nuevoRegistro->save();

            // Retorna verdadero para indicar que se creó un registro nuevo
            return true;
        }
    }

}
