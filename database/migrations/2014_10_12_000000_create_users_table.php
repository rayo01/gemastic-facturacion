<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfiles',function($table){
            $table->increments('ID');

            $table->dateTime('FechaCreacion')->required();
            $table->dateTime('FechaModificacion')->required();
            $table->unsignedInteger('ID_Usuario')->required();

            $table->string('Nombre',50)->required();//->nullable($value = false);
            $table->text('Descripcion')->required();//->nullable($value = false);
        });

        Schema::create('ubigeos', function($table) {
            $table->increments('ID');
            $table->string('CodDepartamento',2);
            $table->string('CodProvincia',2);
            $table->string('CodDistrito',2);
            $table->string('Nombre',100);
            $table->timestamps();
        });

        Schema::create('negocios',function($table){
            $table->increments('ID');

            $table->dateTime('FechaCreacion')->required();
            $table->dateTime('FechaModificacion')->required();
            $table->unsignedInteger('ID_Usuario')->required();

            $table->string('Ruc',15);
            $table->string('RazonSocial',250)->required();//->nullable($value = false);
            $table->string('Denominacion',150)->nullable();
            $table->string('Direccion',150)->required();//->nullable($value = false);
            $table->string('Telefono1',25)->nullable();
            $table->string('Telefono2',25)->nullable();
            $table->string('Email',100)->required();//->nullable($value = false);
            $table->string('Web',100)->nullable();
            $table->string('RepLegal',150)->nullable();
            $table->tinyInteger('Estado')->required();//->nullable($value = false);
            $table->unsignedInteger('ID_Ubigeo')->nullable();

            $table->foreign('ID_Ubigeo')->references('ID')->on('ubigeos');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedInteger('ID_Usuario')->required();
            $table->tinyInteger('Estado')->required();
            $table->unsignedInteger('Id_Perfil')->required();
            $table->unsignedInteger('Id_Negocio')->required();
            $table->string('UrlImagen',100)->nullable();


            $table->foreign('Id_Perfil')->references('ID')->on('perfiles');
            $table->foreign('Id_Negocio')->references('ID')->on('negocios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('negocios');
        Schema::drop('perfiles');
        Schema::dropIfExists('users');
        Schema::drop('ubigeos');
    }
}
