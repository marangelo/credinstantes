<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Abono extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Tbl_AbonosCreditos";

    public function credito()
    {
        return $this->belongsTo(Credito::class, 'id_creditos', 'id_creditos');
    }

    public static function getAbonos()
    {
        return Abono::all();
    }

    public static function getHistorico($IdCredito)
    {  
        $Abonos = Abono::where('id_creditos',$IdCredito)->get();
        return $Abonos;
    }

    

    public static function SaveNewAbono(Request $request)
    {

        if ($request->ajax()) {
            try {

                $IdCred             = $request->input('IdCred');
                $Capital_           = $request->input('Capital_');
                $Interes_           = $request->input('Interes_');
                $Total_             = $request->input('Total_');
                
                $Info_Credito = Credito::find($IdCred);


                $datos_credito = [                    
                    'id_creditos'           => $IdCred,
                    'registrado_por'        => Auth::id(),
                    'fecha_cuota'           => date('Y-m-d H:i:s'),
                    'pago_capital'          => $Capital_,
                    'pago_intereses'        => $Interes_,
                    'cuota_credito'         => $Info_Credito->cuota,
                    'cuota_cobrada'         => $Total_,
                    // 'intereses_por_cuota'   => $XXXXXX,
                    // 'abono_dia1'            => $XXXXXX,
                    // 'abono_dia2'            => $XXXXXX,
                    // 'fecha_cuota_secc1'     => $XXXXXX,
                    // 'fecha_cuota_secc2'     => $XXXXXX,
                    // 'completado'            => 1,
                    // 'fecha_cuota_secc2'     => $XXXXXX,
                ];
                $response = Abono::insert($datos_credito);
                


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
}
