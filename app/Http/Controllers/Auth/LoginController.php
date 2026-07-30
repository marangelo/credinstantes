<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\LoginLog;
use App\Helpers\UserAgentParser;
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
                return 'Activos/0';
            break;

            case '3':
                return 'Dashboard';
            break;

            case '5':
                return 'Supervisor';
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
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/')->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
    public function login(Request $request) {

        $this->validateLogin($request);

        

        if ($this->hasTooManyLoginAttempts($request)) {
            

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        $user = $request->email;
        $queryResult = DB::table('users')->where('email', $user)->where('activo', 'S')->where('Lock', 1)->pluck('id');
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

                $agent = new UserAgentParser($request->userAgent());
                
                LoginLog::create([
                    'user_id'         => $queryResult->first(),
                    'ip'              => $request->ip(),
                    'browser'         => $agent->browser(),
                    'browser_version' => $agent->browserVersion(),
                    'platform'        => $agent->platform(),
                    'device'          => $agent->device(),
                    'device_model'    => $request->input('device_model'),
                    'device_brand'    => $request->input('device_brand'),
                    'android_id'      => $request->input('device_android_id'),
                ]);
                
                return $this->sendLoginResponse($request);
            }
        }
        return $this->sendFailedLoginResponse($request);
    }

    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect($this->redirectTo());
        }
        return response()
            ->view('Usuario.home')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
        
    }
}
