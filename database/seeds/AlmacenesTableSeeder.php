<?php

use Illuminate\Database\Seeder;

class AlmacenesTableSeeder extends Seeder
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

        DB::table('almacenes')->insert([
            'ID' => '1',
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => '1', //used seed "UsersTableSeeder"
            'Nombre' => 'Almacen 1',
            'Direccion' => 'av la cultura',
            'Telefono1' => '324324',
            'Telefono2' => '123233',
            'Estado' => '1',
        ]);
    }
}
