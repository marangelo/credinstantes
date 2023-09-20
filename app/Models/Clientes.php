<?php

namespace App\Models;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clientes extends Model
{
    #protected $connection = 'sqlsrv';
    public $timestamps = false;
    protected $table = "Tbl_Clientes";
    protected $primaryKey = 'id_clientes';

    public function getMunicipio()
    {
        return $this->hasOne(Municipios::class, 'id_municipio','id_municipio');
    }

    public static function getClientes()
    {
        return Clientes::where('activo',1)->orderBy('id_clientes', 'asc')->where('activo',1)->get();
    }
    public function getCreditos()
    {
        return $this->hasMany(Credito::class, 'id_clientes','id_clientes')->where('activo',1);
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

                $creditos_activos = $Cliente->tieneCreditoVencido;
                $credito_abonos   = $Cliente->getCreditos[0]->abonosCount();
                $credito_cuotas   = $Cliente->getCreditos[0]->numero_cuotas;
                $credito_totals   = $Cliente->getCreditos[0]->total; 

                $tieneCreditoVencido = count($creditos_activos);

                //VERIFICA EL ESTADO DE SALUD DEL CLIENTE EN CASO QUE TENGA CREDITO VENCIDOS O EN MORA
                $isCreditoVencido = ($tieneCreditoVencido > 0 ) ? true : false ;

                if($isCreditoVencido===false){
                     // COMPRUEBA EL PORCENTAJE DE PAGOS QUE TIENEN SU PRIMER ABONO
                    $Cumplimiento = ($credito_abonos > 0) ? ($credito_abonos / $credito_cuotas) * 100 : 0 ;

                    $creditCheck = ($Cumplimiento >= 50 && $credito_totals >= 6000)  ? true : false ;
                }else{
                    $creditCheck = false;
                }

                $array = [
                    "creditCheck"       => $creditCheck,
                    "MontoMaximo"       => $credito_totals * (60 / 100),
                ];

                

            
            

                return response()->json($array);
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }


}
