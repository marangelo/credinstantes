<?php

namespace App\Models;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model {
    protected $table = "users";

    
    public function RolName(){
        return $this->hasOne(Roles::class,'id','id_rol');
    }
    public function Zona(){
        return $this->hasOne(Zonas::class,'id_zona','id_zona');
    }


    public static function getUsuarios()
    {
        return Usuario::where('activo','S')->get();
    }

   
    public static function SaveUsuario(Request $request) {
        if ($request->ajax()) {
            try {

                $usuario        = $request->input('Usuario');
                $nombre         = $request->input('Nombre');
                $passwprd       = Hash::make($request->input('Contrasena'));
                $Estado         = $request->input('Estado');
                $id_rol         = $request->input('Permiso');
                $id_zona        = $request->input('Zona');

                $Comment        = $request->input('Commit');


                if ($Estado=="0") {
                    $obj = new Usuario();   
                    $obj->email      = $usuario;                
                    $obj->nombre        = $nombre;
                    $obj->password      = $passwprd;
                    $obj->id_rol        = $id_rol;
                    $obj->id_zona       = $id_zona;
                    $obj->Comment       = $Comment;
                    $obj->activo        = 'S';                 
                    $response = $obj->save();
                } else {
                    $response =   Usuario::where('id',  $Estado)->update([
                        "email" => $usuario,
                        "nombre" => $nombre,
                        "Comment" => $Comment,
                        "id_rol" => $id_rol,
                        "id_zona" => $id_zona,
                    ]);
                }

                return response()->json($response);
                
            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }
    }
    public static function DeleteUsuario(Request $request)
    {
        if ($request->ajax()) {
            try {

                $id     = $request->input('id');
                
                $response =   Usuario::where('id',  $id)->update([
                    "activo" => 'N',
                ]);

                return response()->json($response);


            } catch (Exception $e) {
                $mensaje =  'Excepción capturada: ' . $e->getMessage() . "\n";
                return response()->json($mensaje);
            }
        }

    }

    public static function updatePassword(Request $request)
    {
        $user = Auth::user();
        $currentPassword = $request->input('currentPassword');
        $newPassword = $request->input('newPassword');
    
        if (!Hash::check($currentPassword, $user->password)) {
            return response()->json(['success' => false]);
        }
    
        $user->password = Hash::make($newPassword);
        $user->save();
    
        return response()->json(['success' => true]);
    }
}