<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\VentaFormRequest;
use App\Venta;
use App\Detalle_Venta;
use App\Nota_Credito;
use App\Detalle_Nota_Credito;
use App\Cliente;
use App\Tipo_Comprobante;
use App\Impuesto;
use App\Producto;
use App\Producto_Empaque;
use App\Numeracion_Serie;
use DB;

class Nota_CreditoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function nuevo(Request $request,$id)
    {
        /*$venta = Venta::where('ID', $id)->first();

        $serie_referencia = $venta->serie;
        $numero_referencia = $venta->numero;
        date_default_timezone_set('America/Lima');
        $Fecha = date('Y-m-d H:i:s');

        $numeracion_serie = Numeracion_Serie::where('ID_TipoComprobante', "07")->where('ID_Negocio', Auth::user()['Id_Negocio'])->first();

        $Nota_Credito= new Nota_Credito;



        $Nota_Credito->Serie=$numeracion_serie->CodigoSerie;
        $serie=$Nota_Credito->Serie;

        $Nota_Credito->Numero=$numeracion_serie->NumeroActual;
        $numero=$Nota_Credito->Numero;

        $Nota_Credito->Fecha=$Fecha;

        $Nota_Credito->FechaCreacion = $Fecha;
        $Nota_Credito->FechaModificacion = $Fecha;
        $Nota_Credito->ID_Usuario = Auth::id();

        $Nota_Credito->ID_Cliente = $venta->ID_Cliente;
        $Nota_Credito->MontoBruto = $venta->MontoBruto;
        $Nota_Credito->Impuesto = $venta->Impuesto;
        $Nota_Credito->Total = $venta->Total;
        $Nota_Credito->MontoReal = $venta->MontoReal;
        $Nota_Credito->DescuentoFijo= $venta->DescuentoFijo;
        $Nota_Credito->DescuentoPorcentual= $venta->DescuentoPorcentual;
        $Nota_Credito->Estado= $venta->Estado;
        $Nota_Credito->ID_MotivoAnulacion= "1";
        $Nota_Credito->ID_TipoComprobante= "07";
        $Nota_Credito->ID_Impuesto=$venta->ID_Impuesto;
        $Nota_Credito->ID_Negocio= Auth::user()['Id_Negocio'];
        $Nota_Credito->PorcentajeImpuesto= $venta->PorcentajeImpuesto;
        $Nota_Credito->Motivo= "Devolucion de productos";
        $Nota_Credito->save();

        if($numeracion_serie->NumeroActual==99999999)
        {
            $serie=substr($numeracion_serie->CodigoSerie, 2, 3);
            $serie=(string)($serie+1);
            $numeracion_serie->CodigoSerie=substr($numeracion_serie->CodigoSerie, 0, 2).str_pad($serie, 2, "0", STR_PAD_LEFT);
        }
        else
        {
          $suma=(string)($numeracion_serie->NumeroActual+1);
          $numeracion_serie->NumeroActual=str_pad($suma, 8, "0", STR_PAD_LEFT);
        }
        $numeracion_serie->FechaModificacion=$Fecha;
        $numeracion_serie->ID_Usuario=Auth::id();
        $numeracion_serie->save();


        $detalle_venta = Detalle_Venta::where('ID_Venta', $id);

        $id_nota_credito=Nota_Credito::where('nota_creditos.Serie', $serie)->where('nota_creditos.Numero', $numero)
        ->select('nota_creditos.id')->first();

  			foreach ($detalle_venta as $key => $detalle)
        {
            $detalle_nota_credito = new Detalle_Nota_Credito;
            $detalle_nota_credito->FechaCreacion = $Fecha;
            $detalle_nota_credito->FechaModificacion = $Fecha;
            $detalle_nota_credito->ID_Usuario = Auth::id();

            $detalle_nota_credito->ID = $id_nota_credito;
            $detalle_nota_credito->ID_Producto =  $detalle->ID_Producto;
            $detalle_nota_credito->ID_UnidadMedida =  $detalle->ID_UnidadMedida;
            $detalle_nota_credito->Cantidad =  $detalle->Cantidad;
            $detalle_nota_credito->PrecioUnitario =  $detalle->PrecioUnitario;
            $detalle_nota_credito->MontoBruto =  $detalle->MontoBruto;
            $detalle_nota_credito->DescuentoFijo =  $detalle->DescuentoFijo;
            $detalle_nota_credito->DescuentoPorcentual =  $detalle->DescuentoPorcentual;
            $detalle_nota_credito->MontoReal =  $detalle->MontoReal;
            $detalle_nota_credito->Impuesto =  $detalle->Impuesto;
            $detalle_nota_credito->Total =  $detalle->Total;
            $detalle_nota_credito->Estado =  "1";

            //deberia modificar el estado de la venta

            //modificar stock

            //buscar el producto en empqes y obtener su equivalencia
            $equivalencia=0;
            $producto = Producto::findOrFail($detalle->ID_Producto);
            if(count($producto)>0)
            {
              $equivalencia=1;
            }
            else {
              $producto_empaque = Producto_Empaque::where('ID_Producto',$detalle->ID_Producto)
              ->where('ID_UnidadMedida',  $detalle->ID_UnidadMedida)->first();
              $equivalencia=$producto_empaque->Equivalencia;
            }
            $producto->Stock=$producto->Stock+($detalle->Cantidad*$equivalencia);
            $producto->save();
            */
            return redirect('/ventas/create');

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
