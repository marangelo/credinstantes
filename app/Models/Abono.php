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
    protected $table = "tbl_abonoscreditos";
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
        $Abonos = Abono::where('id_creditos',$IdCredito)->where('activo',1)->orderBy('id_abonoscreditos', 'asc')->get();
        
        $abonosArray = [];

        foreach ($Abonos as $Key => $abono) {
            
            $abonosArray[$Key] = [
                'id' => $Key + 1 ,
                'id_abonoscreditos' => $abono->id_abonoscreditos,
                'id_creditos' => $abono->id_creditos,
                'registrado_por' => $abono->registrado_por,
                'fecha_cuota' =>  \Date::parse($abono->fecha_cuota)->format('D, M d, Y'),
                'pago_capital' => $abono->pago_capital,
                'pago_intereses' => $abono->pago_intereses,
                'cuota_credito' => $abono->cuota_credito,
                'cuota_cobrada' => $abono->cuota_cobrada,
                'intereses_por_cuota' => $abono->intereses_por_cuota,
                'abono_dia1' => $abono->abono_dia1,
                'abono_dia2' => $abono->abono_dia2,
                'fecha_cuota_secc1' => $abono->fecha_cuota_secc1,
                'fecha_cuota_secc2' => $abono->fecha_cuota_secc2,
                'fecha_programada' => $abono->fecha_programada,
                'completado' => $abono->completado,
                'saldo_cuota' => $abono->saldo_cuota,
                'saldo_anterior' => $abono->saldo_anterior,
                'saldo_actual' => $abono->saldo_actual,
                'activo' => $abono->activo,
                'NumPago' => $abono->NumPago,
                'Descuento' => $abono->Descuento,
            ];

        }

        return $abonosArray;
    }
    public static function getSaldoAbono($IdCredito,$Opt)
    {  
        $Abono = 0;
        
        $Credito = Credito::where('id_creditos',$IdCredito)->where('activo',1)->first();

        $SaldoPendiente = ($Credito->abonos->isNotEmpty()) ? number_format($Credito->abonos->first()->saldo_cuota,2, '.', '') : 0 ;
        $Pagos = PagosFechas::Pagos($IdCredito);
        
        $datos_credito = [];

        switch ($Opt) {
            case 0:
                
                $Abono = ($Credito->saldo > $Credito->cuota) ? $Credito->cuota :$Credito->saldo;

                $datos_credito = [                    
                    'Cuotas_pend'          => 0,
                    'Interes_'             => 0,
                    'Capital_'             => 0,
                    'Saldo_to_cancelar'    => $Abono,
                    'Saldo_Pendiente'      => $SaldoPendiente,
                    'Plan_pago'             => $Pagos
                ];
                
                break;
            case 1:

                // AQUI SE CALCULA TOMANDO EL SALDO PENDIENTE YA QUE CONTIENE EL INTERES NECESARIO                
                $Saldo_to_cancelar = $Credito->saldo;

                $LstAbono = Abono::selectRaw('NumPago')->where('id_creditos', $IdCredito)->groupBy('NumPago')->orderByDesc('NumPago')->first();                
                $IntePago  = Abono::where('id_creditos',$IdCredito)->where('NumPago',$LstAbono->NumPago)->sum('pago_intereses');
                $IntePend  = $Credito->intereses_por_cuota - $IntePago ;
                $IntResta = ($IntePend <= 0) ? 0 : $IntePend ;                

                $Cuotas_pend    = RefAbonos::WHERE('id_creditos',$IdCredito)->where('Pagado',0)->count();
                $Taza_          =  $Credito->taza_interes / 100;
                $Abono_         =  $Credito->cuota;
                
                $Interes_       = (($Credito->intereses_por_cuota) * $Cuotas_pend) + $IntResta;
                $Capital_       = $Saldo_to_cancelar  -  $Interes_;

                $datos_credito = [                    
                    'Cuotas_pend'          => $Cuotas_pend,
                    'Interes_'             => $Interes_,
                    'Capital_'             => $Capital_,
                    'Saldo_to_cancelar'    => $Saldo_to_cancelar,
                    'Saldo_Pendiente'      => $SaldoPendiente,
                    'Plan_pago'             => $Pagos
                ];
                
                $Abono = $Saldo_to_cancelar;
                break;
            case 2;

                $Abono = ($Credito->saldo > $Credito->cuota) ? $Credito->cuota :$Credito->saldo;

                $datos_credito = [                    
                    'Cuotas_pend'          => 0,
                    'Interes_'             => 0,
                    'Capital_'             => 0,
                    'Saldo_to_cancelar'    => $Abono,
                    'Saldo_Pendiente'      => $SaldoPendiente,
                    'Plan_pago'             => $Pagos
                ];
                break;
            
            default:
                # code...
                break;
        }
        
    
        return $datos_credito ;
    }
    public static  function MultiAbonos(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Abono_a_capital = 0;
                $intereses_total = 0;

                $IdCred          = $request->input('IdCred');
                $FechaAbono      = $request->input('FechaAbono');
               
                $Total_          = $request->input('Total_');
                
                //$IdCred          = 1;
                //$Total_          = 2000;
                //$FechaAbono      = '2023-11-21 00:00:00';

                $Credito         = Credito::find($IdCred);
                $cuota_cobrada   = $Total_;
                

                $Abonos          = PagosFechas::getAbonosPendientes($IdCred);
                
                $Saldo_Credito   = $Credito->saldo;

                foreach ($Abonos as $key => $a) {

                    if ($Total_ > 0) {
                        
                        $INTERES = $Credito->intereses_por_cuota;  
                        $CAPITAL = $Credito->cuota - $INTERES; 

                        //FECHA QUE TIENE EL ABONO
                        //$FechaAbono   = $a->FECHA_PAGO;
                        $NumPago      = $a->NUM_PAGO;
                        
                        $intereses_total = ($Total_ >= $INTERES) ? $INTERES :  $Total_ ;   

                        $Abono_a_capital = ($Total_ >= $CAPITAL) ? $CAPITAL : $Total_ - $INTERES ;

                        $Abono_a_capital = ( $Abono_a_capital > 0) ?  $Abono_a_capital : 0 ;
                        $Total_ = ($Total_ > 0) ? $Total_ : 0;

                        if ($Total_ <= $Credito->cuota) {
                            
                            $intereses_total = ($Total_ < $INTERES) ? $Total_ : $INTERES;
                            $Abono_a_capital =  $Total_ - $intereses_total;
                        }
                        $Saldo_actual_credito = $Saldo_Credito - ( $Abono_a_capital + $intereses_total );

                        $PENDIENTE = $Credito->cuota - ($Abono_a_capital + $intereses_total);
                        $LastDate = ($Saldo_actual_credito <= 0) ? $FechaAbono: null ;

                        //VALIDA SI EL SALDO ES MAYOR QUE EL ABONO
                        $Pago = ($Total_ >= $Credito->cuota) ? $Credito->cuota : $Total_ ;
                        
                        $pagos[] = [
                            'id_creditos'           => $IdCred,
                            'registrado_por'        => Auth::id(),
                            'fecha_cuota'           => $FechaAbono,
                            'pago_capital'          => $Abono_a_capital,
                            'pago_intereses'        => $intereses_total,
                            'cuota_credito'         => $Credito->cuota,
                            'cuota_cobrada'         => $Pago,
                            'intereses_por_cuota'   => $INTERES,
                            'abono_dia1'            => $Pago,
                            'fecha_cuota_secc1'     => $FechaAbono,
                            'completado'            => (($PENDIENTE <= 0) ? 1 : 0),
                            'saldo_cuota'           => (($PENDIENTE <= 0) ? 0 : $PENDIENTE)  ,
                            'saldo_actual'          => $Saldo_actual_credito,
                            'saldo_anterior'        => $Saldo_Credito,
                            'activo'                => 1,
                            'NumPago'               => $NumPago,
                            'Descuento'             => 0,
                        ];

                        $Saldo_Credito = $Saldo_actual_credito;
                    
                        $Total_ = $Total_ - $INTERES ; 
                        $Total_ = $Total_ - $CAPITAL ; 

                        RefAbonos::where('id_creditos',  $IdCred)->where('numero_pago',  $NumPago)->update([
                            "Pagado"=> 1,
                        ]);
                    }
                }
                Abono::insert($pagos);

                Credito::where('id_creditos',  $IdCred)->update([
                    //"fecha_ultimo_abono"    => date('Y-m-d H:i:s'),
                    "saldo" => (($Saldo_actual_credito <= 0) ? 0 : $Saldo_actual_credito),
                    "estado_credito"=>1,
                    "fecha_culmina"=>$LastDate
                ]);

               
    

                return $pagos;
                
            
                

            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function NewPagos(Request $request){

        if ($request->ajax()) {
            try {
                $IdCred         = $request->input('IdCred');
                $FechaAbono     = $request->input('FechaAbono');
                $NumPago        = $request->input('NumPago');
                $Descuento      = $request->input('Desc');
                $ttAbono        = $request->input('Total_');

                $InteresPagado  = Abono::where('id_creditos',$IdCred)->where('NumPago',$NumPago)->sum('pago_intereses');
                $Info_Credito   = Credito::where('id_creditos',$IdCred)->where('activo',1)->first();            
                $Interes_Abono  = $Info_Credito->intereses_por_cuota - $InteresPagado;                
                $Credito_Saldo  = $Info_Credito->saldo;

                $Credito_Cuota  = ($Info_Credito->abonos->isNotEmpty()) ? number_format($Info_Credito->abonos->first()->saldo_cuota,2, '.', '') : $Info_Credito->cuota ;
                $Credito_Cuota  = ($Credito_Cuota == 0 ) ? $Info_Credito->cuota : $Credito_Cuota ;
                
                $Pago_Interes       = ($ttAbono >= $Interes_Abono) ? $Interes_Abono : $ttAbono ;
                $Pago_Interes       = ($Pago_Interes < 0) ? 0 : $Pago_Interes ;

                $Pago_Capital       = $ttAbono  - $Interes_Abono;
                $Pago_Capital       = ($Pago_Capital < 0) ? 0 : $Pago_Capital ;

                $Saldo_Cuota        = $Credito_Cuota - $ttAbono ;
                $Saldo_Cuota        = ($Saldo_Cuota <= 0) ? 0 : $Saldo_Cuota ;

                $Saldo_Actual       = $Credito_Saldo - $ttAbono;
                $Saldo_Actual       = ($Saldo_Actual <= 0) ? 0 : $Saldo_Actual ;

                $Credito_Estado     = ($Saldo_Cuota > 0 ) ? 2 : 1 ;
                $Credito_Estado     = ($Saldo_Actual <= 0) ? 4 : $Credito_Estado ;

                $Fecha_Culmina      = ($Saldo_Actual <= 0) ? $FechaAbono: null ;
                

                $datos_credito = [                    
                    'id_creditos'           => $IdCred,
                    'registrado_por'        => Auth::id(),
                    'fecha_cuota'           => $FechaAbono,
                    'pago_capital'          => $Pago_Capital,
                    'pago_intereses'        => $Pago_Interes,
                    'cuota_credito'         => $Info_Credito->cuota,
                    'cuota_cobrada'         => $ttAbono,
                    'intereses_por_cuota'   => $Info_Credito->intereses_por_cuota,
                    'abono_dia1'            => $ttAbono,
                    'fecha_cuota_secc1'     => $FechaAbono,
                    'completado'            => 1,
                    'saldo_cuota'           => $Saldo_Cuota,
                    'saldo_actual'          => $Saldo_Actual,
                    'activo'                => 1,
                    'saldo_anterior'        => $Info_Credito->saldo,
                    'NumPago'               => $NumPago,
                    'Descuento'             => $Descuento
                ];

                $response = Abono::insert($datos_credito);

                Credito::where('id_creditos',  $IdCred)->update([
                    "saldo" => $Saldo_Actual,
                    "estado_credito"=>$Credito_Estado,
                    "fecha_culmina"=>$Fecha_Culmina
                ]);

                RefAbonos::where('id_creditos',  $IdCred)->where('numero_pago',  $NumPago)->update([
                    "Pagado"=> ($Saldo_Cuota > 0) ? 0 : 1 ,
                ]);

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }

    //ANTIGUA FORMA DE APLICAR PAGO
    public static function SaveNewAbono(Request $request)
    {

        if ($request->ajax()) {
            try {
                $IdCred         = $request->input('IdCred');
                $FechaAbono     = $request->input('FechaAbono');
                $NumPago        = $request->input('NumPago');
                $Descuento      = $request->input('Desc');
                $CompletarPago  = false;

                //OBTENEMOS LA INFORMACION DEL CREDITO
                $Info_Credito   = Credito::find($IdCred);

                $Total_         = $request->input('Total_');
                $cuota_cobrada  = $Total_;

                $Saldo_actual_credito = $Info_Credito->saldo - $Total_;

                $lastAbonoSaldo = ($Info_Credito->abonos->isNotEmpty()) ? $Info_Credito->abonos->first()->saldo_cuota : 0 ;
                if ($lastAbonoSaldo > 0) {
                    $Total_         = $Total_ - $lastAbonoSaldo;
                    $IdABono        = $Info_Credito->abonos->first()->id_abonoscreditos;

                    $pago_capital       = $Info_Credito->abonos->first()->pago_capital;
                    $cuota_cobrada      = $Info_Credito->abonos->first()->cuota_cobrada;
                    $saldo_actual       = $Info_Credito->abonos->first()->saldo_actual;

                    $pago_capital       = $pago_capital  + $lastAbonoSaldo;
                    $cuota_cobrada      = $cuota_cobrada + $lastAbonoSaldo;
                    $saldo_actual       = $saldo_actual  - $lastAbonoSaldo;

                    $response = Abono::where('id_abonoscreditos', $IdABono)->update([
                        "saldo_cuota"           => 0,
                        "completado"            => 1,
                        'abono_dia2'            => $lastAbonoSaldo,
                        'fecha_cuota_secc2'     => $FechaAbono,
                        'cuota_cobrada'         => $cuota_cobrada,
                        'pago_capital'          => $pago_capital,
                        'saldo_actual'          => ($saldo_actual <=0) ? 0 : $saldo_actual ,
                    ]);

                    $Saldo_actual_credito = $saldo_actual;

                    $CompletarPago  = true;

                    
                } 
                

                
                $Capital_       = $Total_  - $Info_Credito->intereses_por_cuota;
                $Interes_       = $Info_Credito->intereses_por_cuota;


                $Saldo_Cuota    = $Info_Credito->cuota - $Total_ ;
                $Saldo_Cuota    = ($Saldo_Cuota < 0) ? 0 : $Saldo_Cuota ;

                $Completado     = ($Saldo_Cuota > 0 ) ? 0 : 1 ;

                $Estado         = ($Saldo_Cuota > 0 ) ? 2 : 1 ;

                $LastDate = ($Saldo_actual_credito <= 0) ? $FechaAbono: null ;
                $Estado = ($Saldo_actual_credito <= 0) ? 4 : $Estado ;

                //$Saldo_Cuota = ($Saldo_actual_credito <= 0) ? 0 : $Saldo_Cuota ;

                $Estado = ($CompletarPago) ? 1 : $Estado ;


                if($Total_ > 0){

                    $datos_credito = [                    
                        'id_creditos'           => $IdCred,
                        'registrado_por'        => Auth::id(),
                        'fecha_cuota'           => $FechaAbono,
                        'pago_capital'          => $Capital_,
                        'pago_intereses'        => $Interes_,
                        'cuota_credito'         => $Info_Credito->cuota,
                        'cuota_cobrada'         => $cuota_cobrada,
                        'intereses_por_cuota'   => $Info_Credito->intereses_por_cuota,
                        'abono_dia1'            => $Total_,
                        'fecha_cuota_secc1'     => $FechaAbono,
                        'completado'            => $Completado,
                        'saldo_cuota'           => (($Saldo_Cuota <= 0) ? 0 : $Saldo_Cuota) ,
                        'saldo_actual'          => (($Saldo_actual_credito <= 0) ? 0 : $Saldo_actual_credito),
                        'activo'                => 1,
                        'saldo_anterior'        => $Info_Credito->saldo,
                        'NumPago'               => $NumPago,
                        'Descuento'             => $Descuento
                    ];
                    $response = Abono::insert($datos_credito);
    
                }

                Credito::where('id_creditos',  $IdCred)->update([
                    //"fecha_ultimo_abono"    => date('Y-m-d H:i:s'),
                    "saldo" => (($Saldo_actual_credito <= 0) ? 0 : $Saldo_actual_credito),
                    "estado_credito"=>$Estado,
                    "fecha_culmina"=>$LastDate
                ]);

                RefAbonos::where('id_creditos',  $IdCred)->where('numero_pago',  $NumPago)->update([
                    "Pagado"=> 1,
                ]);

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }

    public static function Cancelacion(Request $request)
    {

        if ($request->ajax()) {
            try {

                $IdCred         = $request->input('IdCred');
                $FechaAbono     = $request->input('FechaAbono');
                $Descuentos     = $request->input('Desc');
                $CompletarPago  = false;
                $Total_         = $request->input('Total_');
                $NumPago        = $request->input('NumPago');

                //OBTENEMOS LA INFORMACION DEL CREDITO
                $InteresPagado  = Abono::where('id_creditos',$IdCred)->where('NumPago',$NumPago)->sum('pago_intereses');
                $Info_Credito   = Credito::where('id_creditos',$IdCred)->where('activo',1)->first();
                $Cuotas_pend    = RefAbonos::WHERE('id_creditos',$IdCred)->where('Pagado',0)->count();
                
                
                $Interes_       = ((($Info_Credito->intereses_por_cuota * $Cuotas_pend) - $InteresPagado ) ) - $Descuentos ;  
                $Total_         = $Total_ - $Descuentos;
                $Capital_       = $Total_  - $Interes_ ;

                $datos_credito = [                    
                    'id_creditos'           => $IdCred,
                    'registrado_por'        => Auth::id(),
                    'fecha_cuota'           => $FechaAbono,
                    'pago_capital'          => $Capital_,
                    'pago_intereses'        => $Interes_,
                    'cuota_credito'         => $Info_Credito->cuota,
                    'cuota_cobrada'         => $Total_,
                    'intereses_por_cuota'   => $Info_Credito->intereses_por_cuota,
                    'abono_dia1'            => $Total_,
                    'fecha_cuota_secc1'     => $FechaAbono,
                    'completado'            => 1,
                    'saldo_cuota'           => 0 ,
                    'saldo_actual'          => 0,
                    'activo'                => 1,
                    'saldo_anterior'        => $Info_Credito->saldo,
                    'Descuento'             => $Descuentos
                ];


                $response = Abono::insert($datos_credito);

                $Abonos_a_cancelar = RefAbonos::where('id_creditos',  $IdCred)->where('Pagado',  0)->get();
                foreach ($Abonos_a_cancelar as $key => $a) {

                    RefAbonos::where('id_creditos',  $a->id_creditos)->where('numero_pago',  $a->numero_pago)->update([
                        "Pagado"=> 1,
                    ]);
                }
                Credito::where('id_creditos',  $IdCred)->update([
                    "saldo" => 0,
                    "estado_credito" => 4,
                    "fecha_culmina"=> $FechaAbono
                ]);


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
    public static function Bluid(Request $request){
        $Pagos = PagosFechas::all();
        $RefAbonos = [] ;
        foreach ($Pagos as $key => $p) {
            if (!empty($p->ID_PAGO)) {
                Abono::where('id_abonoscreditos', $p->ID_PAGO)->update( [                    
                    'NumPago'        => $p->NUM_PAGO,
                    'Descuento'      => 0
                ]);

                RefAbonos::where('id_creditos', $p->ID_CREDITO)->where('numero_pago', $p->NUM_PAGO)->update( [ 
                    'Pagado'      => 1
                ]);
              
            }
        }
        return $Pagos;
    }
    public static function rmAbono(Request $request)
    {
        if ($request->ajax()) {
            try {

                $id         = $request->input('IdElem');
                $Abono      = Abono::find($id);                

                $Info_Credito = Credito::find($Abono->id_creditos);
                $Saldo_actual_credito = $Info_Credito->saldo + $Abono->pago_capital + $Abono->pago_intereses + $Abono->Descuento;

                Credito::where('id_creditos', $Abono->id_creditos)->update([
                    "saldo" => $Saldo_actual_credito
                ]);

                $deleted = DB::table("tbl_abonoscreditos")->where("id_abonoscreditos", $id)->update([
                    'activo' => 0
                ]);

                RefAbonos::where('id_creditos',  $Abono->id_creditos)->where('numero_pago',  $Abono->NumPago)->update([
                    "Pagado"=>0,
                ]);

                Clientes::CheckStatus($Abono->id_creditos);

                return response()->json($deleted);


            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function Dispensa($D1, $D2){
        $Dispensa = Abono::whereBetween('fecha_cuota_secc1', [$D1, $D2])->sum('Descuento');
        return $Dispensa;
    }
    Public static function getDataReporteDispensa(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $IdZna    = $request->input('IdZna');
        $array_abonos = array();

        $Obj = Abono::whereBetween('fecha_cuota_secc1', [$dtIni, $dtEnd])->Where('Descuento', '>', 0)->Where('activo',1);
        
    
        if ($IdZna > 0) {
            $Obj->whereHas('credito.Clientes', function ($query) use ($IdZna) {
                $query->where('id_zona', $IdZna);
            });
        }

        $Creditos = $Obj->get();
        
        foreach ($Creditos as $key => $a) {     
            
            $array_abonos[$key] = [
                "id_abonoscreditos"     => $a->id_abonoscreditos,
                "ZONA"                  => $a->credito->Clientes->getZona->nombre_zona,
                "fecha_ultimo_abono"    => date('d/m/Y', strtotime($a->fecha_cuota_secc1)),
                "Nombre"                => strtoupper($a->credito->Clientes->nombre),
                "apellido"              => strtoupper($a->credito->Clientes->apellidos),
                "saldo"                 => $a->Descuento,
            ];  
        }

        return $array_abonos;
    }
}
