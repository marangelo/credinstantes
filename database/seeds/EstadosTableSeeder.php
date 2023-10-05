<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_estados')->insert([
            ['nombre_estado' => 'Al Dia'],
            ['nombre_estado' => 'En Mora'],
            ['nombre_estado' => 'Vencido'],
            ['nombre_estado' => 'Inactivo'],
        ]);
    }
}
