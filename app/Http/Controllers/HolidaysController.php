<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holidays;
use App\Http\Requests;

use Auth;

class HolidaysController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function UIHoliDays()
    {         
        $Titulo     = "Feriados Nacionales";

        $HolidaysGroup = Holidays::selectRaw('YEAR(date_holiday) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->pluck('year')
            ->toArray();
        
        return view('Holidays.Table', compact('Titulo', 'HolidaysGroup'));
    }

    public function HolidaysList(Request $request)
    {
        $ini = $request->dtFeriados . "-01-01";
        $end = $request->dtFeriados . "-12-31";

        $Holidays = Holidays::whereBetween('date_holiday', [$ini, $end])->get()->toArray();

        return response()->json($Holidays);
    }

    public function HolidaysEdit(Request $request)
    {
        $Holiday = Holidays::where('id_holiday',$request->id_holiday)->get()->first();
        
        return response()->json($Holiday);
    }
    public function HolidaysDelete(Request $request)
    {
        $Holiday = Holidays::where('id_holiday',$request->id_holiday)->delete();
        
        return response()->json(['status' => 'success']);
    }

    public function HolidaysSave(Request $request)
    {
        if ($request->ajax()) {
            try {
                $ID = $request->input('_IdDtFeriado');

                if ($ID == '0') {
                    $response = Holidays::insert([
                        'date_holiday'  => $request->input('_Fecha'),
                        'Description'   => $request->input('_Descripcion'),
                        'created_by'    => Auth::id(),
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                } else {
                    $response = Holidays::where('id_holiday', $ID)->update([
                        'date_holiday'  => $request->input('_Fecha'),
                        'Description'   => $request->input('_Descripcion'),
                        'updated_at'    => now(),
                    ]);
                }

                return $response;
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public function UpdateHoliday($nYear)
    {
      dd($nYear);
    }


}
