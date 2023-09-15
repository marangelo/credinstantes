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
                $Info_Credito = Credito::find($IdCred);

                $Total_             = $request->input('Total_');
                $Saldo_actual_credito = $Info_Credito->saldo - $Total_;

                //RESTAR EL VALOR PENDIENTE SI EXISTE
                $lastAbonoSaldo = ($Info_Credito->abonos->isNotEmpty()) ? $Info_Credito->abonos->first()->saldo_cuota : 0 ;
                $Total_         = $Total_ - $lastAbonoSaldo;

                //RESTAR EL VALOR DE INTERES POR CUATA AL VALOR INGRESADO
                $Capital_           = $Total_  - $Info_Credito->intereses_por_cuota;
                $Interes_           = $Info_Credito->intereses_por_cuota;

                $Saldo_Cuota     = $Info_Credito->cuota - $Total_ ;

                $Completado = ($Saldo_Cuota === 0 ) ? 0 : 1 ;

                $datos_credito = [                    
                    'id_creditos'           => $IdCred,
                    'registrado_por'        => Auth::id(),
                    'fecha_cuota'           => date('Y-m-d H:i:s'),
                    'pago_capital'          => $Capital_,
                    'pago_intereses'        => $Interes_,
                    'cuota_credito'         => $Info_Credito->cuota,
                    'cuota_cobrada'         => $Total_,
                    'intereses_por_cuota'   => $Info_Credito->intereses_por_cuota,
                    'abono_dia1'            => $Total_,
                    // 'abono_dia2'            => $XXXXXX,
                    'fecha_cuota_secc1'     => date('Y-m-d H:i:s'),
                    // 'fecha_cuota_secc2'     => $XXXXXX,
                    'completado'            => $Completado,
                    'saldo_cuota'            => $Saldo_Cuota,
                    'activo'           => 1,
                ];
                $response = Abono::insert($datos_credito);

                Credito::where('id_creditos',  $IdCred)->update([
                    "fecha_ultimo_abono"    => date('Y-m-d H:i:s'),
                    "saldo" => $Saldo_actual_credito
                ]);
                


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
}
