<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Numeracion_SeriesTableSeeder extends Seeder
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

        DB::table('numeracion_series')->insert([
            'ID' => 1,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'CodigoSerie' => 'F001',
            'NumeroActual' => 1,
            'ID_TipoComprobante' => '01', //foreign key 'Tipo_Comprobante' table (facturas)
            'ID_Negocio' => 1, //foreign key 'Negocios' table
        ]);

        DB::table('numeracion_series')->insert([
            'ID' => 2,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'CodigoSerie' => 'B001',
            'NumeroActual' => 1,
            'ID_TipoComprobante' => '03', //foreign key 'Tipo_Comprobante' table (boletas)
            'ID_Negocio' => 1, //foreign key 'Negocios' table
        ]);
        DB::table('numeracion_series')->insert([
            'ID' => 3,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'CodigoSerie' => 'C001',
            'NumeroActual' => 1,
            'ID_TipoComprobante' => '07', //foreign key 'Tipo_Comprobante' table (nota de credito)
            'ID_Negocio' => 1, //foreign key 'Negocios' table
        ]);
        DB::table('numeracion_series')->insert([
            'ID' => 4,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'CodigoSerie' => 'D001',
            'NumeroActual' => 1,
            'ID_TipoComprobante' => '08', //foreign key 'Tipo_Comprobante' table (nota de debito)
            'ID_Negocio' => 1, //foreign key 'Negocios' table
        ]);

    }
}
