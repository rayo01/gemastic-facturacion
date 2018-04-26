<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    //
    protected $table = 'ubigeos';

    protected $primaryKey ='ID';

    protected $fillable = ['CodDepartamento', 'CodProvincia','CodDistrito','Nombre'];
}
