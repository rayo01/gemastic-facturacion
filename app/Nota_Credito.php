<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nota_Credito extends Model
{
    //
    protected $table = 'nota_creditos';

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
    public function Impuesto()
    {
        return $this->belongsTo('App\Impuesto', 'ID_Impuesto', 'ID');
    }
}
