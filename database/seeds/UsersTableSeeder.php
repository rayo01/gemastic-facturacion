<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
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

        DB::table('users')->insert([
            'id' => 1,
            'created_at' => $datetime,
            'updated_at' => $datetime,
            'name' => 'Admin1',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('secret'),
            'Estado' => '1',
            'Id_Perfil' => '1', //foreign key 'perfiles'
            'Id_Negocio' => '1', //foreign key 'negocios'
            'UrlImagen' => 'www.admin1.com',
            'ID_Usuario' => '1',
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'created_at' => $datetime,
            'updated_at' => $datetime,
            'name' => 'Operador1',
            'email' => 'operador1@gmail.com',
            'password' => bcrypt('operador'),
            'Estado' => '1',
            'Id_Perfil' => '2', //foreign key 'perfiles'
            'Id_Negocio' => '1', //foreign key 'negocios'
            'UrlImagen' => 'www.operador1.com',
            'ID_Usuario' => '1',
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'created_at' => $datetime,
            'updated_at' => $datetime,
            'name' => 'Consultor1',
            'email' => 'consultor1@gmail.com',
            'password' => bcrypt('consultor'),
            'Estado' => '1',
            'Id_Perfil' => '3', //foreign key 'perfiles'
            'Id_Negocio' => '1', //foreign key 'negocios'
            'UrlImagen' => 'www.admin1.com',
            'ID_Usuario' => '1',
        ]);
    }
}
