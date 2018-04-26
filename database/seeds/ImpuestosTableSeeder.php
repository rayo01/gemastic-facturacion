<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImpuestosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('America/Lima');
        $datetime = date("Y-m-d H:i:s");

        DB::table('impuestos')->insert([
            'ID' => '1000',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1,
            'Nombre' => 'IGV',
            'Porcentaje' => 0.18,
        ]);
    }
}
