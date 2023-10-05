<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CatDayWeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cat_diassemana')->insert([
            ['dia_semana' => 'LUNES', 'dia_semananum' => 1, 'activo' => 1],
            ['dia_semana' => 'MARTES', 'dia_semananum' => 2, 'activo' => 1],
            ['dia_semana' => 'MIÉRCOLES', 'dia_semananum' => 3, 'activo' => 1],
            ['dia_semana' => 'JUEVES', 'dia_semananum' => 4, 'activo' => 1],
            ['dia_semana' => 'VIERNES', 'dia_semananum' => 5, 'activo' => 1],
            ['dia_semana' => 'SÁBADO', 'dia_semananum' => 6, 'activo' => 1],
            ['dia_semana' => 'DOMINGO', 'dia_semananum' => 7, 'activo' => 1],
        ]);
    }
}
