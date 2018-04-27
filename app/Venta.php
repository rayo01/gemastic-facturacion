<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    //
    protected $table = 'ventas';

    protected $primaryKey ='ID';

    /**
     * indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function Cliente()
    {
        return $this->belongsTo('App\Cliente', 'ID_Cliente', 'ID');
    }
    public function Tipo_Comprobante()
    {
        return $this->belongsTo('App\Tipo_Comprobante', 'ID_TipoComprobante', 'ID');
    }

    public function Impuestos()
    {
        return $this->belongsTo('App\Impuesto', 'ID_Impuesto', 'ID');
    }


}
