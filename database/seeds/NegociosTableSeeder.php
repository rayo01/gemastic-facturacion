<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NegociosTableSeeder extends Seeder
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

        DB::table('negocios')->insert([
            'ID' => 1,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1,
            'Ruc' => 'editar ruc',
            'RazonSocial' => 'editar razon social',
            'Denominacion' => 'editar denominacion',
            'Direccion' => 'editar direccion',
            'Telefono1' => 'editar telefono1',
            'Telefono2' => 'editar telefono2',
            'email' => 'editar email',
            'Web' => 'editar url pagina web',
            'RepLegal' => 'editar representante legal',
            'Estado' => 1,
            //'ID_Ubigeo' => 1,
        ]);
    }
}
