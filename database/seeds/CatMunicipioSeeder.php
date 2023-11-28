<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatMunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cat_municipio')->insert([
            ['id_municipio' => 1, 'id_departamento' => 1, 'nombre_municipio' => 'JINOTEPE', 'activo' => 1],
            ['id_municipio' => 3, 'id_departamento' => 1, 'nombre_municipio' => 'Nandaime', 'activo' => 1],
        ]);
    }
}
