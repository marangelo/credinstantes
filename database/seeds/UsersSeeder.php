<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nombre' => 'Wilmer Ramos',
            'email' => 'demo@demo.com',
            'password' => Hash::make('123456'), 
            'activo' => 'S',
            'Comment' => 'Será el encargado de realizar cualquier operación sobre el sistema, este tendrá todos los privilegios posibles, y será el encargado de dar de alta a otros usuarios, asignar sus roles, dar de baja, definir qué acción pueden tener sobre las ventanas (editar, eliminar, consultar, insertar).',
            'id_rol' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
