<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Inserta los registros
    DB::table('rol')->insert([
        ['id' => 1, 'descripcion' => 'Administrador', 'activo' => 'S', 'created_at' => '2022-02-22 10:47:52', 'updated_at' => '2022-08-29 09:15:49'],
        ['id' => 2, 'descripcion' => 'Cobrador', 'activo' => 'S', 'created_at' => '2023-09-29 10:30:12', 'updated_at' => '2023-09-29 10:30:12'],
        ['id' => 3, 'descripcion' => 'Operadores', 'activo' => 'S', 'created_at' => '2023-09-29 10:30:12', 'updated_at' => '2023-09-29 10:30:12'],
    ]);
    }
}
