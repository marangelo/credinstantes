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
            ['nombre_estado' => 'Al Dia', 'created_at' => now(),'updated_at' => now()],
            ['nombre_estado' => 'En Mora', 'created_at' => now(),'updated_at' => now()],
            ['nombre_estado' => 'Vencido', 'created_at' => now(),'updated_at' => now()],
            ['nombre_estado' => 'Inactivo', 'created_at' => now(),'updated_at' => now()],
        ]);
    }
}
