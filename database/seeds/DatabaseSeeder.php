<?php

use Illuminate\Database\Seeder;
use App\Seeders\CatDiassemanaSeeder;
use App\Seeders\CatDepartamentoSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CatDepartaSeeder::class, 
            CatDayWeekSeeder::class, 
            CatMunicipioSeeder::class, 
            RolSeeder::class,
            EstadosTableSeeder::class,
            UsersSeeder::class,
        ]);
    }
}
