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
    protected $primaryKey = 'id_abonoscreditos';

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
        $Abonos = Abono::where('id_creditos',$IdCredito)->where('activo',1)->get();
        return $Abonos;
    }

    

    public static function SaveNewAbono(Request $request)
    {

        if ($request->ajax()) {
            try {
                $IdCred         = $request->input('IdCred');

                //OBTENEMOS LA INFORMACION DEL CREDITO
                $Info_Credito   = Credito::find($IdCred);

                $Total_         = $request->input('Total_');
                $cuota_cobrada  = $Total_;

                // RESTAMOS EL MONTO TOTAL DEL ABONO AL CREDITO
                $Saldo_actual_credito = $Info_Credito->saldo - $Total_;

                //RESTAR EL VALOR PENDIENTE SI EXISTE
                $lastAbonoSaldo = ($Info_Credito->abonos->isNotEmpty()) ? $Info_Credito->abonos->first()->saldo_cuota : 0 ;                

                if ($lastAbonoSaldo > 0) {
                    $Total_         = $Total_ - $lastAbonoSaldo;
                    $IdABono = $Info_Credito->abonos->first()->id_abonoscreditos;
                    $response = Abono::where('id_abonoscreditos', $IdABono)->update([
                        "saldo_cuota"           => 0,
                        "completado"            => 1,
                        'abono_dia2'            => $lastAbonoSaldo,
                        'fecha_cuota_secc2'     => date('Y-m-d H:i:s'),
                    ]);
                    
                } 
                

                
                //RESTAR EL VALOR DE INTERES POR CUOTA AL VALOR INGRESADO
                $Capital_       = $Total_  - $Info_Credito->intereses_por_cuota;
                $Interes_       = $Info_Credito->intereses_por_cuota;


                //RESTAMOS EL VALOR TOTAL DEL ABONO , AL SALDO DEL CREDITO
                $Saldo_Cuota    = $Info_Credito->cuota - $Total_ ;
                $Saldo_Cuota    = ($Saldo_Cuota < 0) ? 0 : $Saldo_Cuota ;

                $Completado     = ($Saldo_Cuota > 0 ) ? 0 : 1 ;

                $Estado     = ($Saldo_Cuota > 0 ) ? 2 : 1 ;


                if($Total_ > 0){

                    $datos_credito = [                    
                        'id_creditos'           => $IdCred,
                        'registrado_por'        => Auth::id(),
                        'fecha_cuota'           => date('Y-m-d H:i:s'),
                        'pago_capital'          => $Capital_,
                        'pago_intereses'        => $Interes_,
                        'cuota_credito'         => $Info_Credito->cuota,
                        'cuota_cobrada'         => $cuota_cobrada,
                        'intereses_por_cuota'   => $Info_Credito->intereses_por_cuota,
                        'abono_dia1'            => $Total_,
                        //'abono_dia2'            => $XXXXXX,
                        'fecha_cuota_secc1'     => date('Y-m-d H:i:s'),
                        //'fecha_cuota_secc2'     => $XXXXXX,
                        'completado'            => $Completado,
                        'saldo_cuota'           => $Saldo_Cuota,
                        'activo'                => 1,
                    ];
                    $response = Abono::insert($datos_credito);
    
                }

                Credito::where('id_creditos',  $IdCred)->update([
                    "fecha_ultimo_abono"    => date('Y-m-d H:i:s'),
                    "saldo" => $Saldo_actual_credito,
                    "estado_credito"=>$Estado
                ]);

                
                


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
    public static function rmAbono(Request $request)
    {
        if ($request->ajax()) {
            try {

                $id         = $request->input('IdElem');
                $Abono = Abono::find($id);
                

                $Info_Credito = Credito::find($Abono->id_creditos);
                $Saldo_actual_credito = $Info_Credito->saldo + $Abono->cuota_cobrada;

                Credito::where('id_creditos', $Abono->id_creditos)->update([
                    "saldo" => $Saldo_actual_credito
                ]);

                $deleted = DB::table("Tbl_AbonosCreditos")->where("id_abonoscreditos", $id)->update([
                    'activo' => 0
                ]);

                return response()->json($deleted);


            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
}
