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
                
                $txtPassword = $request->input('Contrasena');
                $txtPassword = ($txtPassword != 'pwd-hide') ? $txtPassword : null ;


                $usuario        = $request->input('Usuario');
                $nombre         = $request->input('Nombre');
                $Phone          = $request->input('Phone');
                $passwprd       = Hash::make($txtPassword);
                $Estado         = $request->input('Estado');
                $id_rol         = $request->input('Permiso');
                $id_zona        = $request->input('Zona');
                $Comment        = $request->input('Commit');


                if ($Estado=="0") {
                    $obj = new Usuario();   
                    $obj->email      = $usuario;                
                    $obj->nombre        = $nombre;
                    $obj->Phone         = $Phone;
                    $obj->password      = $passwprd;
                    $obj->id_rol        = $id_rol;
                    $obj->id_zona       = $id_zona;
                    $obj->Comment       = $Comment;
                    $obj->activo        = 'S';                 
                    $response = $obj->save();
                } else {

                    $updateData = [
                        "email" => $usuario,
                        "Phone" => $Phone,
                        "nombre" => $nombre,
                        "Comment" => $Comment,
                        "id_rol" => $id_rol,
                        "id_zona" => $id_zona,
                    ];

                    if ($txtPassword !== null) {
                        $updateData["password"] = $passwprd;
                    }

                    $response = Usuario::where('id', $Estado)->update($updateData);

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
        try {
            $user = Auth::user();
            if (!$user) {
                return ['success' => false, 'message' => 'Usuario no autenticado'];
            }

            $currentPassword = $request->input('currentPassword');
            $newPassword = $request->input('newPassword');

            if (!Hash::check($currentPassword, $user->password)) {
                return ['success' => false, 'message' => 'Contraseña actual incorrecta'];
            }

            $user->password = Hash::make($newPassword);
            $saved = $user->save();

            return ['success' => $saved, 'message' => $saved ? 'Contraseña actualizada' : 'Error al guardar'];

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}