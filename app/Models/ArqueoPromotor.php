<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;

use Auth;

class ArqueoPromotor extends Model {
    public $timestamps = false;
    protected $table = "tbl_arqueo_promotor";
    protected $primaryKey = 'id_arqueo_prom';


    public function Creditos()
    {
        $SimpleDate = \Date::parse($this->fecha)->format('Y-m-d');
        return $this->hasMany(Credito::class, 'asignado', 'id_promotor')
            ->whereBetween('fecha_apertura', [
                $SimpleDate.' 00:00:00',
                $SimpleDate.' 23:59:59'
            ]);
    }

    public function getPromotor()
    {
        return $this->hasOne(Usuario::class, 'id','id_promotor');
    }

    public static function getData(Request $request)
    {
        $dtIni    = $request->input('dtIni').' 00:00:00';
        $dtEnd    = $request->input('dtEnd').' 23:59:59';
        $IdZna    = $request->input('IdZna');

        $Obj =  ArqueoPromotor::whereBetween('fecha', [$dtIni, $dtEnd])->Where('activo',1);

        // if ($IdZna > 0) {
        //     $Obj->Where('id_zona',$IdZna);
        // }

        $Arqueos = $Obj->get();

        $array_arqueos = array();
        
        foreach ($Arqueos as $key => $a) 
        {  
            //$name_user_arqueo = (empty($a->getZona->UsuarioCobrador->nombre)) ? 'N/D' : $a->getZona->UsuarioCobrador->nombre ;

            $name_user_arqueo = 'PROMOTOR ' . $a->id_arqueo_prom;

            $array_arqueos[$key] = [
                "Id"                => $a->id_arqueo_prom,
                "fecha_arqueo"      => \Date::parse($a->fecha)->format('d-m-Y') ,
                "Zona"              => $a->id_arqueo_prom,
                "Nombre"            => strtoupper($name_user_arqueo),
                
                "entregado"         => $a->entregado,
                "desembolsado"      => $a->desembolsado,
                "sobrante"          => $a->sobrante,
                "consolidado"       => $a->consolidado,
            ];
            
        }


        return $array_arqueos;
    }

    public static function TableDetalles(Request $request)
    {
        $ID_Arqueo = $request->input('Arqueo');

        $Arqueo = ArqueoPromotor::find($ID_Arqueo);
        $PromotorDetalles = ArqueoPromotorDetalles::where('id_arqueo_prom', $ID_Arqueo)->get();

        $EfectivoDesembolsado = 0;

        $Detalles   = array();
        $Resultado  = array();
        $InfoArqueo = array();

        $RowDetalles = (count($PromotorDetalles) > 0) ? $PromotorDetalles : $Arqueo->Creditos;

        foreach ($RowDetalles as $key => $c) 
        {  

            $NombreCliente  = (count($PromotorDetalles) > 0) ? $c->nombre_cliente : strtoupper($c->Clientes->nombre) . ' ' . strtoupper($c->Clientes->apellidos);
            $NombreZona     = (count($PromotorDetalles) > 0) ? $c->nombre_zona : strtoupper($c->Clientes->getZona->nombre_zona);

            $Detalles[$key] = [
                "cliente"           => $NombreCliente,
                "monto_credito"     => $c->monto_credito,
                "comprobante"       => $c->id_creditos,
                "ruta"              => $NombreZona,
            ];
            $EfectivoDesembolsado += $c->monto_credito;
            
        }

        $EfectivoDesembolsado = (is_null($Arqueo->desembolsado)) ? $EfectivoDesembolsado : ($Arqueo->desembolsado ? $Arqueo->desembolsado : 0 );
        
        $InfoArqueo[] = [
            "id_arqueo_prom"     => $Arqueo->id_arqueo_prom,
            "Promotor"           => strtoupper($Arqueo->getPromotor->nombre) . ' \ ' . strtoupper($Arqueo->getPromotor->Zona->nombre_zona),
            "fecha"              => \Date::parse($Arqueo->fecha)->format('Y-m-d'),
            "entregado"          => (is_null($Arqueo->entregado)) ? 0 : number_format($Arqueo->entregado, 2, '.', '') ,
            "desembolsado"       => (is_null($EfectivoDesembolsado)) ? 0 : number_format($EfectivoDesembolsado, 2, '.', '') ,
            "sobrante"           => (is_null($Arqueo->sobrante)) ? 0 : number_format($Arqueo->sobrante, 2, '.', '') ,
            "consolidado"        => (is_null($Arqueo->consolidado)) ? 0 : number_format($Arqueo->consolidado, 2, '.', '') ,
            "comentario"         => $Arqueo->comentario,
            "id_promotor"        => $Arqueo->id_promotor,
        ];


        $Resultado = [
            'InfoArqueo' => $InfoArqueo,
            'Detalles' => $Detalles,
        ];


        return $Resultado;
    }

    public static function UpdateArqueoPromotor(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Arqueo     = $request->input('Arqueo');
                $Fecha      = $request->input('Fecha');
                $Entregado  = $request->input('Entregado');
                $Desembolso = $request->input('Desembolso');
                $Sobrante   = $request->input('Sobrante');
                $Consolido  = $request->input('Consolido');
                $Commit     = $request->input('Commit');

                $response = ArqueoPromotor::where('id_arqueo_prom',$Arqueo)->update([
                    "fecha"            => $Fecha,
                    "entregado"        => $Entregado,
                    "desembolsado"     => $Desembolso,                    
                    "comentario"       => $Commit,
                    "sobrante"         => $Sobrante,
                    "consolidado"      => $Consolido
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Arqueo guardado correctamente',
                    'data'    => [
                        'id' => $response
                    ]
                ], 200);
                
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar ',
                    'error'   => $e->getMessage()
                ], 500);
            
            }
        }
    } 
    public static function SaveArqueoPromotor(Request $request)
    {
        if ($request->ajax()) {
            try {

                $Arqueo     = $request->input('Arqueo');
                $Fecha      = $request->input('Fecha');
                $Entregado  = $request->input('Entregado');
                $Desembolso = $request->input('Desembolso');
                $Sobrante   = $request->input('Sobrante');
                $Consolido  = $request->input('Consolido');
                $Commit     = $request->input('Commit');

                $Detalles   = [];
                

                //ACTUALIZA LOS VALORES DEL ARQUEO PROMOTOR 
                $response = ArqueoPromotor::where('id_arqueo_prom',$Arqueo)->update([
                    "fecha"            => $Fecha,
                    "entregado"        => $Entregado,
                    "desembolsado"     => $Desembolso,                    
                    "comentario"       => $Commit,
                    "sobrante"         => $Sobrante,
                    "consolidado"      => $Consolido
                ]);
                

                $Arqueo = ArqueoPromotor::find($Arqueo);

                //INSERTA LOS DETALLES DE LOS CREDITOS
                foreach ($Arqueo->Creditos as $key => $c) 
                {  
                    $Detalles[$key] = [
                        "id_arqueo_prom"    => $Arqueo->id_arqueo_prom,
                        "nombre_cliente"    => strtoupper($c->Clientes->nombre) . ' ' . strtoupper($c->Clientes->apellidos),
                        "monto_credito"     => $c->monto_credito,
                        "id_creditos"       => $c->id_creditos,
                        "nombre_zona"       => strtoupper($c->Clientes->getZona->nombre_zona),
                        "created_by"        => Auth::id(),
                    ];
                    
                }

                $resultado = ArqueoPromotorDetalles::Insert($Detalles);


                return response()->json([
                    'success' => true,
                    'message' => 'Arqueo guardado correctamente',
                    'data'    => [
                        'id' => $resultado
                    ]
                ], 200);
                
            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar ',
                    'error'   => $e->getMessage()
                ], 500);
            
            }
        }
    } 
    
}