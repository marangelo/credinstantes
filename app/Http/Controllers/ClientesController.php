<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clientes;
use App\Models\Credito;

class ClientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reactivacion(Request $request)
    {
        $DaysLastPayment = Clientes::getDaysLastPayment(269); 
        

        if($DaysLastPayment >= 10){
            dd('El cliente tiene más de 10 días es. Reactivacion', $DaysLastPayment);
        } else {
            dd('El cliente tiene menos de 10 días es. Renovación', $DaysLastPayment);
        }

    }

    public function Holiday(Request $request)
    {
        // Check if a specific date is a holiday
        $Date = new \DateTime('2025-12-25');
        echo $Date->format('Y-m-d').'<br>' ;
        
        $isHoliday = Credito::isHoliday($Date);
        echo  $isHoliday;
    }
}
