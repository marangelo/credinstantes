<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{


    use AuthenticatesUsers;

    public function redirectTo() {

        $role = Auth::User()->id_rol;
    
        switch ($role) {
            case '1':
                return 'Dashboard';
            break;

            case '2':
                return 'Activos';
            break;

            case '3':
                return 'Activos';
            break;

            default:
                return '/';
            break;
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout () {        
        auth()->logout();
        return redirect('/');
    }
    public function login(Request $request) {

        $this->validateLogin($request);

        

        if ($this->hasTooManyLoginAttempts($request)) {
            

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        $user = $request->email;
        $queryResult = DB::table('users')->where('email', $user)->where('activo', 'S')->pluck('id');
        if (!$queryResult->isEmpty()) {
            if ($this->attemptLogin($request)) {

                $Info_usuario = Usuario::find($queryResult);
                $Rutas = '';

                foreach($Info_usuario as $user)
                {
                    $request->session()->put('name_session', $user->nombre);
                    $request->session()->put('name_rol', $user->RolName->descripcion);
                    $request->session()->put('rol', $user->id_rol);
                    $request->session()->put('Zona', $user->id_zona);
                }
                //$rol = DB::table('usuario_rol')->where('usuario_id', $queryResult)->pluck('rol_id');
                
                return $this->sendLoginResponse($request);
            }
        }
        return $this->sendFailedLoginResponse($request);
    }

    public function showLoginForm()
    {
        return view('Usuario.home');
        
    }
}
