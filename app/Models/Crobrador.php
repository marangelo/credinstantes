<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;
class Crobrador extends Model
{
 

    public static function getDesembolsados( Request $request)
    {
        
        $dtIni         = $request->input('dtIni');
        $dtEnd         = $request->input('dtEnd');

        $D1     = date('Y-m-01', strtotime($dtIni)). ' 00:00:00';
        $D2     = date('Y-m-t', strtotime($dtEnd)). ' 23:59:59';    
        $Cobra  = Auth::id();
        
        $ArrayClientesNuevos     = [] ;
        $ArrayReprestamo         = [] ;
        $ArrayReactivaciones     = [] ;
        $Loadarray               = [] ;
        $position_array          = 0 ;

        
        $Represtamo = Reloan::whereBetween('date_reloan', [$D1, $D2])->where('user_created',$Cobra)->get();        
        foreach ($Represtamo as $rc) {            
            $ArrayReprestamo[$position_array] = [
                'id_clientes'       => $rc->id_clientes,
                'Nombre'            => $rc->Clientes->nombre . " " . $rc->Clientes->apellidos,
                'Fecha'             => \Date::parse($rc->date_reloan)->format('D, M d, Y') ,
                'Monto'             => "C$ ".number_format($rc->amount_reloan,2),
                'Origen'            => 'RePrestamo',
            ];
            $Loadarray[$position_array] = $rc->loan_id;
            $position_array++;
        }

        $Creditos   = Credito::whereBetween('fecha_apertura', [$D1, $D2])->whereNotIn('id_creditos', $Loadarray)->where('asignado',$Cobra)->get();     
    
        foreach ($Creditos as $c) {
            $ArrayClientesNuevos[$position_array] = [
                'id_clientes'       => $c->id_clientes,
                'Nombre'            => $c->Clientes->nombre . " " . $c->Clientes->apellidos,
                'Fecha'             => \Date::parse($c->fecha_apertura)->format('D, M d, Y') ,
                'Monto'             => "C$ ".number_format($c->monto_credito,2),
                'Origen'            => 'Nuevo',
            ];
            $position_array++;
        }

        $Clientes_Reactivacion = ClientesReactivacion::whereBetween('fecha_reactivacion', [$D1, $D2])->where('user_created',$Cobra)->get();
        foreach ($Clientes_Reactivacion as $rc) {
            $ArrayReactivaciones[$position_array] = [
                'id_clientes'       => $rc->id_clientes,
                'Nombre'            => $rc->Clientes->nombre . " " . $rc->Clientes->apellidos,
                'Fecha'             => \Date::parse($rc->fecha_reactivacion)->format('D, M d, Y') ,
                'Monto'             => "C$ ".number_format($rc->monto_reactivacion,2),
                'Origen'            => 'Reactivacion',
            ];
            $position_array++;
        }

        $array_merge = array_merge($ArrayClientesNuevos ,$ArrayReprestamo, $ArrayReactivaciones);

        return $array_merge;
    }

}
