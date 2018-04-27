<?php

use Illuminate\Database\Seeder;

class Motivo_MovimientosTableSeeder extends Seeder
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
            'Descripcion' => 'Salida de productos por venta',
            'Estado' => '1',
        ]);
        DB::table('motivo_movimientos')->insert([
            'ID' => '2',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => '1', //used seed "UsersTableSeeder"
            'Nombre' => 'Entrada',
            'Descripcion' => 'Entrada de productos por nota de credito',
            'Estado' => '1',
        ]);
    }
}
