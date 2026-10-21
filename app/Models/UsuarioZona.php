<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Zonas asignadas a un usuario Promotor (un promotor puede atender varias zonas)
class UsuarioZona extends Model
{
    protected $table = "tbl_usuario_zonas";
    protected $primaryKey = 'id_usuario_zona';
    public $timestamps = false;

    // Zonas asignadas al usuario si es Promotor (rol 4).
    // null = no es promotor (sin restricción); [] = promotor sin zonas (no ve ningún cliente).
    public static function ZonasPromotor($User)
    {
        if (!$User || $User->id_rol != 4) {
            return null;
        }

        return UsuarioZona::where('id_usuario', $User->id)->pluck('id_zona')->map(function ($z) {
            return (int) $z;
        })->toArray();
    }

    public static function Sincronizar($IdUsuario, array $Zonas)
    {
        UsuarioZona::where('id_usuario', $IdUsuario)->delete();

        $rows = [];
        foreach (array_unique($Zonas) as $IdZona) {
            $rows[] = ['id_usuario' => $IdUsuario, 'id_zona' => (int) $IdZona];
        }

        if ($rows) {
            UsuarioZona::insert($rows);
        }
    }
}
