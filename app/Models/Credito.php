<?php

namespace App\Models;
use Auth;
use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Credito extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "tbl_creditos";    
    protected $primaryKey = 'id_creditos';

    public function Clientes()
    {
        return $this->hasOne(Clientes::class, 'id_clientes','id_clientes');
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class, 'id_creditos', 'id_creditos')->where('activo',1)->orderBy('id_abonoscreditos', 'desc')->limit(1);
    }
    public function refAbonos()
    {
        return $this->hasMany(RefAbonos::class, 'id_creditos', 'id_creditos')->orderBy('numero_pago', 'desc')->limit(1);
    }

    public function getDiasSemana(){
        return $this->hasOne(DiasSemana::class, 'id_diassemana','id_diassemana');
    }
    
    public function AbonoLogs()
    {
        return $this->hasMany(PagosFechas::class, 'ID_CREDITO', 'id_creditos')
        ->whereDate('FECHA_PAGO', '<=', now()) 
        ->selectRaw('SUM(SALDO_PENDIENTE) as SALDO_PENDIENTE') 
        ->groupBy('ID_CREDITO') 
        ->limit(1);
    }
    public function AbonoToday()
    {
        return $this->hasMany(PagosFechas::class, 'ID_CREDITO', 'id_creditos')
        ->whereDate('FECHA_PAGO', '=', now()) 
        ->selectRaw('COUNT(ID_CREDITO) as tDay') 
        ->groupBy('ID_CREDITO') 
        ->limit(1);
    }

    public function getRefResquest()
    {
        return $this->hasOne(RefResquestCredit::class, 'id_credit','id_creditos');
    }
    
    public function abonosCount()
    {
        return $this->hasMany(Abono::class, 'id_creditos', 'id_creditos')->where('activo',1)->count();
    }
    public function Estado()
    {
        return $this->hasOne(Estados::class, 'id_estados','estado_credito');
    }

    public function getAbonos()
    {
        return $this->hasMany(Abono::class, 'id_creditos','id_creditos')->where('activo',1);
    }

    public static function getCreditos()
    {
        return Credito::where('activo',1)->get();
    }
    public static function Creditos($Zona,$D1, $D2)
    {
        return CreditosHistory::where('CREDITO_ACTIVO', 1)
                ->whereDate('FECHA', '<=', $D2)
                ->whereIn('ESTADO_CREDITO', [1, 2, 3])
                ->when($Zona > -1, function ($query) use ($Zona) {
                    $query->where('ID_ZONA', $Zona);
                })->get();        
    }
    public static function ClientesRenovados($Zona,$D1, $D2)
    {
        return Reloan::whereBetween('date_reloan', [$D1,$D2])->count();
    }
    public static function ClientesNuevos($Zona,$D1, $D2)
    {
        $Reloan = Reloan::whereBetween('date_reloan', [$D1,$D2])->get()->toArray();
        $NewClients = CreditosHistory::whereBetween('FECHA', [$D1,$D2]);

        return $NewClients->whereNotIn('ID_CREDITO', array_column($Reloan, 'loan_id'))->count();
    }
    public static function Saldos_Cartera($Zona,$D1, $D2)
    {
        return CreditosHistory::where('CREDITO_ACTIVO', 1)
                ->whereDate('FECHA', '<=', $D2)
                ->when($Zona > -1, function ($query) use ($Zona) {
                    $query->where('ID_ZONA', $Zona);
                })->sum('SALDO_CREDITO');        
    }
    public static function getCreditosActivos()
    {
        return Credito::where('activo',1)->whereNotIn('estado_credito',[2,3,4])->get();
    }
    public static function ChanceStatus(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Credi_     = $request->input('Credi_');
                $Value_     = $request->input('Value_');
                
                $response =   Credito::where('id_creditos',  $Credi_)->update([
                    "estado_credito" => $Value_,
                ]);

                return response()->json($response);


            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }

    }
    public static function SaveNewCredito(Request $request)
    {

        if ($request->ajax()) {
            try {
                $id_municipio           = $request->input('Municipio_');
                $nombre                 = $request->input('Nombre_');
                $Promotor               = $request->input('Promotor_');
                $apellidos              = $request->input('Apellido_');
                $direccion_domicilio    = $request->input('Dire_');
                $cedula                 = $request->input('Cedula_');
                $telefono               = $request->input('Tele_');
                $score                  = 100;
                $activo                 = 1;
                $Estado                 = 1;
                $Zona_                  = $request->input('Zona_');

                $DiaSemana_         = $request->input('DiaSemana_');
                $Monto_             = $request->input('Monto_');
                $Plato_             = $request->input('Plato_');
                $Interes_           = $request->input('Interes_');
                $Total_             = $request->input('Total_');
                $Cuotas_             = $request->input('Cuotas_');

                $vlCuota           = $request->input('vlCuota');
                $vlInteres             = $request->input('vlInteres');
                $Saldos_             = $request->input('Saldos_');
                $InteresesPorCuota             = $request->input('InteresesPorCuota');
                $FechaOpen             = $request->input('FechaOpen');

                
                $iNegocio   = $request->input('iNegocio');
                $iConyugue  = $request->input('iConyugue');
                $iGarantias = $request->input('iGarantias');
                $iReferencias = $request->input('iReferencias');


            

                $fecha = new DateTime($FechaOpen);
                $Fecha_abonos = [];


                $datos_a_insertar = [
                    'id_municipio'         => $id_municipio,
                    'nombre'               => $nombre,
                    'apellidos'            => $apellidos,
                    'direccion_domicilio'  => $direccion_domicilio,
                    'cedula'               => $cedula,
                    'telefono'             => $telefono,
                    'id_zona'              => $Zona_,
                    'score'                => $score,
                    'activo'               => $activo,
                ];
                $idInsertado = Clientes::insertGetId($datos_a_insertar);

                $datos_credito = [
                    'creado_por'          => Auth::id(),
                    'asignado'            => $Promotor,
                    'fecha_apertura'      => date('Y-m-d',strtotime($FechaOpen)),
                    'id_diassemana'       => $DiaSemana_,
                    'id_clientes'         => $idInsertado,
                    'monto_credito'       => $Monto_,
                    'plazo'               => $Plato_,
                    'taza_interes'        => $Interes_,
                    'numero_cuotas'       => $Cuotas_,
                    'total'               => $Total_,
                    'cuota'               => $vlCuota,
                    'interes'             => $vlInteres,
                    'intereses_por_cuota'=>$InteresesPorCuota,
                    'saldo'               => $Saldos_,
                    'estado_credito'               => $Estado,
                    'activo'            => 1
                ];

                $IdCredito = Credito::insertGetId($datos_credito);

                for ($i = 1; $i <= $Cuotas_; $i++) {
                    $fecha->add(new DateInterval('P1W')); 

                    // Adjust the date if it falls on a holiday
                    $Fecha_pago = Credito::isHoliday($fecha);

                    $Fecha_abonos[] = [
                        'id_creditos'    => $IdCredito,
                        'numero_pago'  => $i, 
                        'FechaPago'   => $Fecha_pago
                    ];
                    
                }

                
                Credito::where('id_creditos',  $IdCredito)->update([
                    "fecha_ultimo_abono"    => $Fecha_abonos[$Cuotas_-1]['FechaPago']
                ]);

                $response = RefAbonos::insert($Fecha_abonos); 

                //VERIFICA EL ESTADO DEL CREDITO AL QUE SE LE ABONO
                //Clientes::CheckStatus($IdCredito);


                $IdProspecto           = $request->input('IdProspecto_'); 


                RefResquestCredit::updateOrCreate(
                    ['id_resquest' => $IdProspecto],
                    [
                        'id_resquest'   => $IdProspecto,
                        'id_credit'     => $IdCredito
                    ]
                );


                ClientesNegocio::updateOrCreate(
                    ['id_req' => $IdProspecto],
                    [
                        'id_cliente'        => $idInsertado,
                        'nombre_negocio'    => $iNegocio['nombre_negocio'],
                        'antiguedad'        => $iNegocio['Antiguedad_negocio'],
                        'direccion'         => $iNegocio['direccion_negocio']              
                    ] 
                );
                
                ClientesConyugue::updateOrCreate(
                    ['id_req' => $IdProspecto],
                    [
                        'id_cliente'        => $idInsertado,
                        'nombres'            => $iConyugue['nombres_conyugue'],
                        'apellidos'          => $iConyugue['apellidos_conyugue'],
                        'no_cedula'          => $iConyugue['cedula_conyugue'],
                        'telefono'           => $iConyugue['telefono_conyugue'],    
                        'direccion_trabajo'  => $iConyugue['direccion_conyugue']     
                    ] 
                );
        
                if(isset($iGarantias) && $iGarantias != null){
                    foreach ($iGarantias as $articulo) {
                        ClientesGarantia::updateOrCreate(
                            [
                                'detalle_articulo' => $articulo['detalle_articulo'],
                                'id_req' => $IdProspecto
        
                            ],
                            [
                                'id_cliente' => $idInsertado,
                                'marca' => $articulo['marca'],
                                'color' => $articulo['color'],
                                'valor_recomendado' => $articulo['valor_recomendado']
                            ]
                        );
                    }
                }
        
                if(isset($iReferencias) && $iReferencias != null){
                    foreach ($iReferencias as $ref) {
                        ClientesReferencias::updateOrCreate(
                            [
                                'nombre_ref' => $ref['nombre_ref'],
                                'id_req' => $IdProspecto
        
                            ],
                            [
                                'id_cliente'    => $idInsertado,
                                'direccion_ref' => $ref['direccion_ref'],
                                'telefono_ref'  => $ref['telefono_ref']
                            ]
                        );
                    }
                }

               


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
    public static function UpdateCredito(Request $request)
    {

        if ($request->ajax()) {
            try {
                $IdCredito      = $request->input('IdCredito_');
                $Cuotas_        = $request->input('Cuotas_');
                $FechaOpen      = $request->input('FechaOpen');

                $fecha = new DateTime($FechaOpen);
                $Fecha_abonos = [];

                $IdCliente =    Credito::select('id_clientes')->where('id_creditos', $IdCredito)->first()->id_clientes;

                // ACTUALIZA LA INFORMACION DEL CLIENTE
                Clientes::where('id_clientes',  $IdCliente)->update([                   
                    'id_zona'              => $request->input('Zona_'),
                    'id_municipio'         => $request->input('Municipio_'),
                    'nombre'               => $request->input('Nombre_'),
                    'apellidos'            => $request->input('Apellido_'),
                    'direccion_domicilio'  => $request->input('Dire_'),
                    'cedula'               => $request->input('Cedula_'),
                    'telefono'             => $request->input('Tele_'),
                ]);

                 //CALCULO PARA LA FECHA DE ABONOS
                 for ($i = 1; $i <= $Cuotas_; $i++) {
                    $fecha->add(new DateInterval('P1W')); 
                    $Fecha_abonos[] = [
                        'id_creditos'    => $IdCredito,
                        'numero_pago'  => $i, 
                        'FechaPago'   => $fecha->format('Y-m-d')
                    ];
                    
                }
                // ACTUALIZA LA INFORMACION DEL CREDITO
                $response =  Credito::where('id_creditos', $IdCredito)->update([
                    'asignado'            => $request->input('Promotor_'),
                    "fecha_ultimo_abono"   => $Fecha_abonos[$Cuotas_-1]['FechaPago'],
                    'fecha_apertura'      => date('Y-m-d',strtotime($FechaOpen)),
                    'id_diassemana'       => $request->input('DiaSemana_'),
                    'monto_credito'       => $request->input('Monto_'),
                    'plazo'               => $request->input('Plato_'),
                    'taza_interes'        => $request->input('Interes_'),
                    'numero_cuotas'       => $Cuotas_,
                    'total'               => $request->input('Total_'),
                    'cuota'               => $request->input('vlCuota'),
                    'interes'             => $request->input('vlInteres'),
                    'intereses_por_cuota' => $request->input('InteresesPorCuota'),
                    'saldo'               => $request->input('Saldos_'),
                    'activo'              => 1
                ]);

                //elimina los registro de la tabla RefAbonos
                RefAbonos::where('id_creditos', $IdCredito)->delete();

                //ELIMINA TODOS LOS ABONOS QUE CREADITO A TENIDO
                Abono::where('id_creditos', $IdCredito)->delete();

                $response = RefAbonos::insert($Fecha_abonos); 

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
    public static function AddCredito(Request $request)
    {

        if ($request->ajax()) {
            try {

                $idInsertado        = $request->input('IdClientes');

                $DiaSemana_         = $request->input('DiaSemana_');
                $Promotor_          = $request->input('Promotor_');
                $Monto_             = $request->input('Monto_');
                $Plato_             = $request->input('Plato_');
                $Interes_           = $request->input('Interes_');
                $Total_             = $request->input('Total_');
                $Cuotas_            = $request->input('Cuotas_');

                $vlCuota            = $request->input('vlCuota');
                $vlInteres          = $request->input('vlInteres');
                $Saldos_             = $request->input('Saldos_');
                $InteresesPorCuota             = $request->input('InteresesPorCuota');
                $FechaOpen             = $request->input('FechaOpen');

                $fecha = new DateTime($FechaOpen);
                $Fecha_abonos = [];

                $datos_credito = [
                    'creado_por'            => Auth::id(),
                    'asignado'              => $Promotor_,
                    'fecha_apertura'        => $FechaOpen,
                    'id_diassemana'         => $DiaSemana_,
                    'id_clientes'           => $idInsertado,
                    'monto_credito'         => $Monto_,
                    'plazo'                 => $Plato_,
                    'taza_interes'          => $Interes_,
                    'numero_cuotas'         => $Cuotas_,
                    'total'                 => $Total_,
                    'cuota'                 => $vlCuota,
                    'interes'               => $vlInteres,
                    'saldo'                 => $Saldos_,
                    'intereses_por_cuota'   =>$InteresesPorCuota,
                    'estado_credito'        => 1,
                    'activo'                => 1,
                ];
                
                $IdCredito = Credito::insertGetId($datos_credito);

                for ($i = 1; $i <= $Cuotas_; $i++) {
                    $fecha->add(new DateInterval('P1W')); 

                    // Ajustar la fecha si cae en feriado
                    $Fecha_pago = Credito::isHoliday($fecha);


                    $Fecha_abonos[] = [
                        'id_creditos'    => $IdCredito,
                        'numero_pago'  => $i, 
                        'FechaPago'   => $Fecha_pago
                    ];
                    
                }

                Credito::where('id_creditos',  $IdCredito)->update([
                    "fecha_ultimo_abono"    => $Fecha_abonos[$Cuotas_-1]['FechaPago']
                ]);

                $response = RefAbonos::insert($Fecha_abonos); 

                 //VERIFICA EL ESTADO DEL CREDITO AL QUE SE LE ABONO
                //Clientes::CheckStatus($IdCredito);

                Reloan::insert([
                    'loan_id'       => $IdCredito,
                    'date_reloan'   => $FechaOpen, 
                    'amount_reloan' => $Monto_,
                    'user_created'  => $Promotor_,
                    'id_clientes'   => $idInsertado
                ]); 
                $IdProspecto           = $request->input('IdProspecto_'); 
                RefResquestCredit::updateOrCreate(
                    ['id_resquest' => $IdProspecto],
                    [
                        'id_resquest'   => $IdProspecto,
                        'id_credit'     => $IdCredito
                    ]
                );
                


                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
        
    }
    public static function isHoliday(DateTime $Date)
    {
        $Year_now = $Date->format('Y');
        $Holidays = [];

        for ($i = 0; $i < 3; $i++) {
            $Holidays[] = "$Year_now-01-01";
            $Holidays[] = "$Year_now-12-25";
            $Year_now++;
        }


        while (in_array($Date->format('Y-m-d'), $Holidays)) {
            // Add 1 Week
            $Date->add(new DateInterval('P1W')); 
        }

        return $Date->format('Y-m-d');
    }

    public static function numberToWords($numero) {
        $unidades = array('', 'Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco', 'Seis', 'Siete', 'Ocho', 'Nueve', 'Diez', 'Once', 'Doce', 'Trece', 'Catorce', 'Quince', 'Dieciséis', 'Diecisiete', 'Dieciocho', 'Diecinueve');
        $decenas = array('', '', 'Veinte', 'Treinta', 'Cuarenta', 'Cincuenta', 'Sesenta', 'Setenta', 'Ochenta', 'Noventa');
        $centenas = array('', 'Ciento', 'Doscientos', 'Trescientos', 'Cuatrocientos', 'Quinientos', 'Seiscientos', 'Setecientos', 'Ochocientos', 'Novecientos');
     
        if ($numero == 0) {
            return 'cero';
        }
     
        if ($numero < 0) {
            return 'menos ' . numero_a_letras(abs($numero));
        }
     
        $letras = '';
     
        if ($numero >= 1000) {
            $parte_entera = floor($numero / 1000);
            $letras .= Credito::numberToWords($parte_entera) . ' mil';
            $resto = $numero - ($parte_entera * 1000);
            if ($resto > 0) {
                $letras .= ' ' . Credito::numberToWords($resto);
            }
        } else {
            if ($numero < 20) {
                $letras .= $unidades[$numero];
            } elseif ($numero < 100) {
                $letras .= $decenas[floor($numero / 10)];
                $resto = $numero % 10;
                if ($resto) {
                    $letras .= ' y ' . $unidades[$resto];
                }
            } else {
                $letras .= $centenas[floor($numero / 100)];
                $resto = $numero % 100;
                if ($resto) {
                    $letras .= ' ' .Credito:: numberToWords($resto);
                }
            }
        }
     
        return $letras;
    }

}
