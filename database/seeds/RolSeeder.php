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
        ['id' => 1, 'descripcion' => 'Administrador', 'activo' => 'S', 'created_at' => now(),'updated_at' => now()],
        ['id' => 2, 'descripcion' => 'Cobrador', 'activo' => 'S', 'created_at' => now(),'updated_at' => now()],
        ['id' => 3, 'descripcion' => 'Operadores', 'activo' => 'S', 'created_at' => now(),'updated_at' => now()],
    ]);
    }
}
