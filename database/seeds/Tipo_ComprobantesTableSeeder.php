<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tipo_ComprobantesTableSeeder extends Seeder
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

        DB::table('tipo_comprobantes')->insert([
            'ID' => '01',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'Abreviacion' => 'F',
            'Nombre' => 'FACTURA',
        ]);
        DB::table('tipo_comprobantes')->insert([
            'ID' => '03',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'Abreviacion' => 'BV',
            'Nombre' => 'BOLETA DE VENTA',
        ]);
        DB::table('tipo_comprobantes')->insert([
            'ID' => '07',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'Abreviacion' => 'N/C',
            'Nombre' => 'NOTA DE CREDITO',
        ]);
        DB::table('tipo_comprobantes')->insert([
            'ID' => '08',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'Abreviacion' => 'N/A',
            'Nombre' => 'NOTA DE DEBITO',
        ]);

    }
}
