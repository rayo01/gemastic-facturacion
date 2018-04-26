<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    //
    //
    protected $table = 'movimientos';

    protected $primaryKey ='ID';

    /**
     * indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function Almacen()
    {
        return $this->belongsTo('App\Almacen', 'ID_Almacen', 'ID');
    }
    
}
