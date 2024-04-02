<?php

namespace App\Models;
use Auth;
use Session; 
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clientes extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "tbl_clientes";
    protected $primaryKey = 'id_clientes';

    public function getMunicipio()
    {
        return $this->hasOne(Municipios::class, 'id_municipio','id_municipio');
    }
    public function getZona()
    {
        return $this->hasOne(Zonas::class, 'id_zona','id_zona')->where('activo',1);
    }
    public static function getClientesMora()
    {
        $resultados = Credito::select('id_clientes', DB::raw('GROUP_CONCAT(estado_credito) as estados'))
            ->where('activo', 1)
            ->whereNotIn('estado_credito', [1])
            ->groupBy('id_clientes')
            ->get()
            ->toArray();

        return $resultados;
    }
    public static function CheckStatus($IdCred){

        $Estado             = 1;
        $SumSaldoPendiente  = 0;

        //EXTRAER INFORMACION DEL CREDITO
        $Credito   = CreditosHistory::where('ID_CREDITO',$IdCred)->first();

        //VERIFICA QUE SI EL CREDITO ESTA VENCIDO
        $Estado   = ($Credito->DIAS_PARA_VENCER < 0) ? 3 : $Estado ;

        //VERIFICA QUE EL ESTADO DEL CREDITO NO ESTE EN MORA;
        if ($Estado == 1) {
            $SumSaldoPendiente  = PagosFechas::getSaldoPendiente($IdCred);        
            $Estado   = ($SumSaldoPendiente > 0) ? 2 : $Estado ;
        }    
            

        Credito::where('id_creditos',  $IdCred)->update([
            "estado_credito"=>$Estado
        ]);

    } 


    public static function getClientes($Id)
    {
        // $Zona = Auth::User()->id_zona;
        $Role = Auth::User()->id_rol;
        
        $IdZona = Auth::User()->id_zona;

        $e = 0;
        
        $Clientes = Clientes::where('activo', 1)->orderBy('id_clientes', 'asc')->whereHas('getCreditos', function ($query) use ($e) {
            $query->whereIn('estado_credito', [1,2,3]);
        });

        if ($Role==2) {
            $Clientes->where('id_zona',$IdZona);
        }else{
            if ($Id > 0) {
                $Clientes->where('id_zona',$Id);
            } 
            
        }

    
        return $Clientes->get();
    }

    public static function getInactivos($id)
    {
        $e = 1;
        $role   = Auth::User()->id_rol;

        //BUSCA LOS CREDITOS QUE TENGA SOLAMENTE CREDITOS INACTIVOS & NO TENGA ACTIVOS O VENCIDOS Y EN MORA
        $Clientes_Inactivos = Clientes::select('tbl_clientes.id_clientes')
        ->selectRaw('GROUP_CONCAT(tbl_creditos.estado_credito) as GROUP_CONCAT_CREDITOS')
        ->join('tbl_creditos', 'tbl_clientes.id_clientes', '=', 'tbl_creditos.id_clientes')
        ->where('tbl_creditos.activo', 1)
        ->where('tbl_creditos.activo', 1)
        ->groupBy('tbl_clientes.id_clientes', 'tbl_clientes.nombre', 'tbl_clientes.apellidos', 'tbl_clientes.activo')
        ->havingRaw("GROUP_CONCAT(tbl_creditos.estado_credito) NOT LIKE '%1%'")
        ->havingRaw("GROUP_CONCAT(tbl_creditos.estado_credito) NOT LIKE '%2%'")
        ->havingRaw("GROUP_CONCAT(tbl_creditos.estado_credito) NOT LIKE '%3%'");
        
        if ($role === 2) {
            $id = Auth::User()->id_zona;
        }
    
        if ($id > 0) {
            $Clientes_Inactivos->where('tbl_clientes.id_zona', $id);
        }
    

        $clientesIds = $Clientes_Inactivos->pluck('id_clientes')->toArray();

        $Clientes = Clientes::whereIn('id_clientes', $clientesIds)->orderBy('id_clientes', 'asc')->whereHas('getCreditos', function ($query) use ($e) {
            $query->whereIn('estado_credito', [4]);
        })->get(); 

        
        return $Clientes;
    }
    public static function getMorosos()
    {
        $e = 2;
        
        return Clientes::where('activo', 1)->orderBy('id_clientes', 'asc')->whereHas('getCreditos', function ($query) use ($e) {
            $query->where('estado_credito', $e);
        })->get();
    }
    
    public function getCreditos()
    {
        return $this->hasMany(Credito::class, 'id_clientes','id_clientes')->orderBy('id_creditos', 'desc')->where('activo',1);
    }

    public function Credito_activo()
    {
        return $this->hasMany(Credito::class, 'id_clientes', 'id_clientes')
                    ->where('activo', 1)
                    ->where('estado_credito', 1);
    }
    public static function Clientes_promotor($Zona){

        $ClientesInactivos = Clientes::getInactivos($Zona);

        $ClientePromotores = ClientePromotores::get();

        $ArrayClientesInactivos     = [] ;
        $ArrayClientesDisponible    = [] ;
        $ArrayInactivos             = [] ;

        $position_array_cliente     = 0 ;


        
        if ($Zona > 0) {
            foreach ($ClientesInactivos as $k => $v){
                if ($v->id_zona == $Zona) {
                    $ArrayInactivos [$k] = $v->id_clientes;
                }
            }
            $ClientesInactivos = Clientes::whereIn('id_clientes', $ArrayInactivos)->get();
            $ClientePromotores = ClientePromotores::where('id_zona',$Zona)->get();
        }

        foreach ($ClientesInactivos as $c) {
        
            $ArrayClientesInactivos[$position_array_cliente] = [
                'id_clientes'       => $c->id_clientes,
                'Nombre'            => $c->nombre,
                'Apellidos'         => $c->apellidos,
                'Departamento'      => $c->getMunicipio->getDepartamentos->nombre_departamento,
                'Zona'              => $c->getZona->nombre_zona,
                'Direccion'         => $c->direccion_domicilio,
                'Accion'            => 'INACTIVO',
            ];
            
            $position_array_cliente++;
        }
        

        foreach ($ClientePromotores as $c) {
            $cl = Clientes::where('id_clientes',$c->id_clientes)->first();

            $ArrayClientesDisponible[$position_array_cliente] = [
                'id_clientes'       => $c->id_clientes,
                'Nombre'            => $cl->nombre,
                'Apellidos'         => $cl->apellidos,
                'Departamento'      => $cl->getMunicipio->getDepartamentos->nombre_departamento,
                'Zona'              => $cl->getZona->nombre_zona,
                'Direccion'         => $cl->direccion_domicilio,
                'Accion'            => 'Menos de 3 Abonos',
            ];
            $position_array_cliente++;
        }

        $array_merge = array_merge($ArrayClientesInactivos , $ArrayClientesDisponible);
        
        

        return $array_merge;
    } 

    
    public function tieneCreditoVencido()
    {
        return $this->getCreditos()->whereIn('estado_credito', [3, 2]);
    }
    public static function editClient(Request $request) {
        if ($request->ajax()) {
            try {

                $IdCl_          = $request->input('IdCl_');

                $Municipio_     = $request->input('Municipio_');
                $Zona_          = $request->input('Zona_');
                $Nombre_        = $request->input('Nombre_');
                $Apellido_      = $request->input('Apellido_');
                $Dire_          = $request->input('Dire_');
                $Cedula_        = $request->input('Cedula_');
                $Tele_          = $request->input('Tele_');

                $response =   Clientes::where('id_clientes',  $IdCl_)->update([
                    "id_municipio"          => $Municipio_,
                    "nombre"                => $Nombre_,
                    "apellidos"             => $Apellido_,
                    "direccion_domicilio"   => $Dire_,
                    "cedula"                => $Cedula_,
                    "id_zona"               => $Zona_,
                    "telefono"              => $Tele_,
                ]);

                return response()->json($response);
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function creditCheck(Request $request) {
        if ($request->ajax()) {
            try {

                $IdCl_      = $request->input('Cliente');

                $Cliente    = Clientes::find($IdCl_);

                $creditCheck = true;
                $credito_cuotas = 0;
                $cumplimiento_porcentaje = 0;



                if (isset($Cliente->Credito_activo[0])) {
                    $creditos_activos = $Cliente->tieneCreditoVencido;
                    $credito_abonos   = $Cliente->Credito_activo[0]->saldo;
                    $credito_cuotas   = $Cliente->Credito_activo[0]->total;
                    //$credito_totals   = $Cliente->getCreditos[0]->total; 

                    $tieneCreditoVencido = count($creditos_activos);

                    //VERIFICA EL ESTADO DE SALUD DEL CLIENTE EN CASO QUE TENGA CREDITO VENCIDOS O EN MORA
                    $isCreditoVencido = ($tieneCreditoVencido > 0 ) ? true : false ;

                    if($isCreditoVencido===false){                        
                         // COMPRUEBA EL PORCENTAJE DE PAGOS QUE TIENEN SU PRIMER ABONO
                        //$Cumplimiento = ($credito_abonos > 0) ? ($credito_abonos / $credito_cuotas) * 100 : 0 ;
                        $cumplimiento_porcentaje = (($credito_cuotas - $credito_abonos) / $credito_cuotas) * 100;
                        $creditCheck = ($cumplimiento_porcentaje >= 50 && $credito_cuotas >= 6000)  ? true : false ;
                    }else{
                        $creditCheck = false;
                    }

                    
                } else{
                    $creditCheck = TRUE;
                }

                
                $array = [
                    "creditCheck"       => $creditCheck,
                    "MontoMaximo"       => $credito_cuotas * (60 / 100),
                    "cumplimiento_porcentaje"       => $cumplimiento_porcentaje,
                ];

            
            

                return response()->json($array);
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }


}
