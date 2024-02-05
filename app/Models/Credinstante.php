<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Credinstante extends Model {
    public $timestamps = false;
    protected $table = "Tbl_Credinstante";    
    protected $primaryKey = 'id_credinstante';

    public static function Remover(Request $request)
    {
        if ($request->ajax()) {
            try {

                $id         = $request->input('IdElem');
                $tb         = $request->input('vTable');
                $nmCamp     = $request->input('nmCamp');

                if($tb==="tbl_clientes"){                    
                    $cliente = Clientes::find($id);
                    $cliente->getCreditos->each(function ($credito) {                    
                        $credito->getAbonos()->update([
                            'activo' => 0
                        ]);
                    });
                    $cliente->getCreditos()->update([
                        'activo' => 0
                    ]);
                }

                if($tb==="tbl_creditos"){                    
                    $Credito = Credito::find($id);
                    $Credito->getAbonos()->update([
                        'activo' => 0
                    ]);
                }

                $deleted = DB::table($tb)->where($nmCamp, $id)->update([
                    'activo' => 0
                ]);

                return response()->json($deleted);


            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function LockUser(Request $request)
    {
        if ($request->ajax()) {
            try {

                $id     = $request->input('IdUser');

                $User   = Usuario::find($id);
                            
                $response =   Usuario::where('id',  $id)->update([
                    "Lock" => ($User->Lock > 0) ? 0: 1 ,
                ]);

                return response()->json($response);


            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }

}
