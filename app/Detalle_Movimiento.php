<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle_Movimiento extends Model
{
    //
    //
    protected $table = 'detalle_movimientos';

    //protected $primaryKey =['ID_Producto','ID_UnidadMedida'];
    //protected $primaryKey ='ID_Producto';


    protected $primaryKey = array('ID_Movimiento','ID_Producto','ID_UnidadMedida');

    /**
    *
    *
    * @var boolean*/
    public $timestamps = false;
    public $incrementing = false;
    public function Movimiento()
    {
        return $this->belongsTo('App\Movimiento','ID_Movimiento','ID');
    }
    public function Producto()
    {
        return $this->belongsTo('App\Producto','ID_Producto','ID');
    }
    public function Unidad_Medida()
    {
        return $this->belongsTo('App\Unidad_Medida','ID_UnidadMedida','ID');
    }

}
