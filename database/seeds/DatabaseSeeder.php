<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(NegociosTableSeeder::class);
        $this->call(PerfilesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(Tipo_ComprobantesTableSeeder::class);
        $this->call(Numeracion_SeriesTableSeeder::class);
        $this->call(ImpuestosTableSeeder::class);
        $this->call(Motivo_AnulacionesTablaSeeder::class);
        $this->call(Motivo_MovimientosTableSeeder::class);
        $this->call(AlmacenesTableSeeder::class);
        
    }
}
