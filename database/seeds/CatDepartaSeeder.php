<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatDepartaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cat_departamento')->insert([
            ['id_departamento' => 1, 'nombre_departamento' => 'CARAZO', 'activo' => 1],
            ['id_departamento' => 2, 'nombre_departamento' => 'LEON', 'activo' => 1],
            ['id_departamento' => 3, 'nombre_departamento' => 'MASAYA', 'activo' => 1],
            ['id_departamento' => 4, 'nombre_departamento' => 'RIVAS', 'activo' => 1],
            ['id_departamento' => 6, 'nombre_departamento' => 'Granada', 'activo' => 1],
            
        ]);
    }
}
