<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Motivo_MovimientosTablaSeeder extends Seeder
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

        DB::table('motivo_movimientos')->insert([
            'ID' => '1',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => '1', //used seed "UsersTableSeeder"
            'Nombre' => 'Salida',
            'Descripcion' => 'Salida de productos',
            'Estado' => '1',
        ]);
    }
}
