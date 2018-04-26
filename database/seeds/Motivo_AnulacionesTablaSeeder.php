<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Motivo_AnulacionesTablaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        date_default_timezone_set('America/Lima');
        $datetime = date("Y-m-d H:i:s");

        DB::table('motivo_anulaciones')->insert([
            'ID' => 'NIN',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1,
            'Nombre' => 'ninguno',
            'Descripcion' => '',

            //'ID_Ubigeo' => 1,
        ]);
    }
}
