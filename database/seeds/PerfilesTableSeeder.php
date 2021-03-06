<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerfilesTableSeeder extends Seeder
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

        DB::table('perfiles')->insert([
            'ID' => 1,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'Nombre' => 'Administrador',
            'Descripcion' => 'Control total de la aplicacion',
        ]);
        DB::table('perfiles')->insert([
            'ID' => 2,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'Nombre' => 'Vendedor',
            'Descripcion' => 'Control del area de ventas y productos',
        ]);
        DB::table('perfiles')->insert([
            'ID' => 3,
            'FechaCreacion' => $datetime,
            'FechaModificacion' => $datetime,
            'ID_Usuario' => 1, //used seed "UsersTableSeeder"
            'Nombre' => 'Consultor',
            'Descripcion' => 'Acceso a la informacion del area de ventas y productos, sin permisos de edicion',
        ]);

    }
}
