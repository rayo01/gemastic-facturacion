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
use App\Movimiento;
use App\Detalle_Movimiento;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function nuevo($id)
    {
        $venta = Venta::where('ID', $id)->first();

        date_default_timezone_set('America/Lima');
        $Fecha = date('Y-m-d H:i:s');

        $numeracion_serie = Numeracion_Serie::where('ID_TipoComprobante', "07")->where('ID_Negocio', Auth::user()['Id_Negocio'])->first();

        $Nota_Credito= new Nota_Credito;

        $Nota_Credito->Serie=$numeracion_serie->CodigoSerie;
        $serie=$Nota_Credito->Serie;//serie de la nota de credito

        $Nota_Credito->Numero=$numeracion_serie->NumeroActual;
        $numero=$Nota_Credito->Numero;

        $Nota_Credito->Fecha=$Fecha;
        $Nota_Credito->SerieReferencia=$venta->Serie;
        $Nota_Credito->NumeroReferencia=$venta->Numero;

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
        $Nota_Credito->ID_MotivoAnulacion= "NIN";
        $Nota_Credito->ID_TipoComprobante= "07";
        $Nota_Credito->ID_Impuesto=$venta->ID_Impuesto;
        $Nota_Credito->ID_Negocio= Auth::user()['Id_Negocio'];
        $Nota_Credito->PorcentajeImpuesto= $venta->PorcentajeImpuesto;
        $Nota_Credito->Motivo= "Devolucion de productos";
        $res = $Nota_Credito->save();

        //registrar movimiento
        $Movimiento= new Movimiento;
        $Movimiento->Fecha = $Fecha;

        $Movimiento->FechaCreacion = $Fecha;
        $Movimiento->FechaModificacion = $Fecha;
        $Movimiento->ID_Usuario = Auth::id();
        $Movimiento->ID_Motivo = 2;
        $Movimiento->Descripcion = "devolucion de Productos";
        $Movimiento->Estado = 1;
        $Movimiento->ID_Almacen = 1;
        $Movimiento->save();

        $id_movimiento=$Movimiento->ID;

        if($numeracion_serie->NumeroActual==99999999)
        {
            $serie=substr($numeracion_serie->CodigoSerie, 1, 3);
            $serie=(string)($serie+1);
            $numeracion_serie->CodigoSerie=substr($numeracion_serie->CodigoSerie, 0, 1).str_pad($serie, 3, "0", STR_PAD_LEFT);
        }
        else
        {
            $suma=$numeracion_serie->NumeroActual+1;
            $numeracion_serie->NumeroActual=$suma;
        }

        $numeracion_serie->FechaModificacion=$Fecha;
        $numeracion_serie->ID_Usuario=Auth::id();
        $numeracion_serie->save();

        $detalle_venta = Detalle_Venta::where('ID_Venta', $id)->get();

        $id_nota_credito=Nota_Credito::where('nota_creditos.Serie', $serie)->where('nota_creditos.Numero', $numero)
        ->select('nota_creditos.ID')->first();

        foreach ($detalle_venta as $key => $detalle)
        {
            $detalle_nota_credito = new Detalle_Nota_Credito;
            $detalle_nota_credito->FechaCreacion = $Fecha;
            $detalle_nota_credito->FechaModificacion = $Fecha;
            $detalle_nota_credito->ID_Usuario = Auth::id();

            $detalle_nota_credito->ID = $id_nota_credito->ID;
            $detalle_nota_credito->ID_Producto =  $detalle->ID_Producto;
            $id_producto=$detalle->ID_Producto;
            $detalle_nota_credito->ID_UnidadMedida =  $detalle->ID_UnidadMedida;
            $id_unidadmedida=$detalle->ID_UnidadMedida;
            $detalle_nota_credito->Cantidad =  $detalle->Cantidad;
            $cantidad=$detalle->Cantidad;
            $detalle_nota_credito->PrecioUnitario =  $detalle->PrecioUnitario;
            $detalle_nota_credito->MontoBruto =  $detalle->MontoBruto;
            $detalle_nota_credito->DescuentoFijo =  $detalle->DescuentoFijo;
            $detalle_nota_credito->DescuentoPorcentual =  $detalle->DescuentoPorcentual;
            $detalle_nota_credito->MontoReal =  $detalle->MontoReal;
            $detalle_nota_credito->Impuesto =  $detalle->Impuesto;
            $detalle_nota_credito->Total =  $detalle->Total;
            $detalle_nota_credito->Estado =  "1";
            $detalle_nota_credito->save();

            $equivalencia=$detalle->Equivalencia;

            //deberia modificar el estado de la venta
            $detalle_Movimiento = new Detalle_Movimiento;
            $detalle_Movimiento->FechaCreacion = $Fecha;
            $detalle_Movimiento->FechaModificacion = $Fecha;
            $detalle_Movimiento->ID_Usuario = Auth::id();

            $detalle_Movimiento->ID_Movimiento=$id_movimiento;
            $detalle_Movimiento->ID_Producto=$id_producto;
            $detalle_Movimiento->ID_UM=$id_unidadmedida;
            $detalle_Movimiento->TipoMovimiento="ING";
            $detalle_Movimiento->Cantidad=round($cantidad*$equivalencia,4);
            $detalle_Movimiento->save();

            //modificar stock

            //buscar el producto en empqes y obtener su equivalencia

            $producto = Producto::findOrFail($detalle->ID_Producto);
            $producto->Stock=$producto->Stock+round($cantidad*$equivalencia,4);
            $producto->save();
        }

        //return redirect('/ventas');
        //if($res){
        return response()->json(['Estado' => 'est']);
        //}
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

        $venta = Venta::where('ID', $id)->first();

        date_default_timezone_set('America/Lima');
        $Fecha = date('Y-m-d H:i:s');

        $numeracion_serie = Numeracion_Serie::where('ID_TipoComprobante', "07")->where('ID_Negocio', Auth::user()['Id_Negocio'])->first();

        $Nota_Credito= new Nota_Credito;



        $Nota_Credito->Serie=$numeracion_serie->CodigoSerie;
        $serie=$Nota_Credito->Serie;//serie de la nota de credito

        $Nota_Credito->Numero=$numeracion_serie->NumeroActual;
        $numero=$Nota_Credito->Numero;

        $Nota_Credito->Fecha=$Fecha;
        $Nota_Credito->SerieReferencia=$venta->Serie;
        $Nota_Credito->NumeroReferencia=$venta->Numero;

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
        $Nota_Credito->ID_MotivoAnulacion= "NIN";
        $Nota_Credito->ID_TipoComprobante= "07";
        $Nota_Credito->ID_Impuesto=$venta->ID_Impuesto;
        $Nota_Credito->ID_Negocio= Auth::user()['Id_Negocio'];
        $Nota_Credito->PorcentajeImpuesto= $venta->PorcentajeImpuesto;
        $Nota_Credito->Motivo= "Devolucion de productos";
        $Nota_Credito->save();

        //registrar movimiento
        $Movimiento= new Movimiento;
        $Movimiento->Fecha = $Fecha;

        $Movimiento->FechaCreacion = $Fecha;
        $Movimiento->FechaModificacion = $Fecha;
        $Movimiento->ID_Usuario = Auth::id();
        $Movimiento->ID_Motivo = 2;
        $Movimiento->Descripcion = "devolucion de Productos";
        $Movimiento->Estado = 1;
        $Movimiento->ID_Almacen = 1;
        $Movimiento->save();

        $id_movimiento=$Movimiento->ID;

        if($numeracion_serie->NumeroActual==99999999)
        {
            $serie=substr($numeracion_serie->CodigoSerie, 1, 3);
            $serie=(string)($serie+1);
            $numeracion_serie->CodigoSerie=substr($numeracion_serie->CodigoSerie, 0, 1).str_pad($serie, 3, "0", STR_PAD_LEFT);
        }
        else
        {
          $suma=$numeracion_serie->NumeroActual+1;
          $numeracion_serie->NumeroActual=$suma;
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
            $id_producto=$detalle->ID_Producto;
            $detalle_nota_credito->ID_UnidadMedida =  $detalle->ID_UnidadMedida;
            $detalle_nota_credito->Cantidad =  $detalle->Cantidad;
            $cantidad=$detalle->Cantidad;
            $detalle_nota_credito->PrecioUnitario =  $detalle->PrecioUnitario;
            $detalle_nota_credito->MontoBruto =  $detalle->MontoBruto;
            $detalle_nota_credito->DescuentoFijo =  $detalle->DescuentoFijo;
            $detalle_nota_credito->DescuentoPorcentual =  $detalle->DescuentoPorcentual;
            $detalle_nota_credito->MontoReal =  $detalle->MontoReal;
            $detalle_nota_credito->Impuesto =  $detalle->Impuesto;
            $detalle_nota_credito->Total =  $detalle->Total;
            $detalle_nota_credito->Estado =  "1";
            $detalle_nota_credito->save();

            $equivalencia=$detalle->Equivalencia;

            //deberia modificar el estado de la venta
            $detalle_Movimiento = new Detalle_Movimiento;
            $detalle_Movimiento->FechaCreacion = $Fecha;
            $detalle_Movimiento->FechaModificacion = $Fecha;
            $detalle_Movimiento->ID_Usuario = Auth::id();

            $detalle_Movimiento->ID_Movimiento=$id_movimiento;
            $detalle_Movimiento->ID_Producto=$id_producto;
            $detalle_Movimiento->TipoMovimiento="ING";
            $detalle_Movimiento->Cantidad=round($cantidad*$equivalencia,4);
            $detalle_Movimiento->save();


            //modificar stock

            //buscar el producto en empqes y obtener su equivalencia

            $producto = Producto::findOrFail($detalle->ID_Producto);
            $producto->Stock=$producto->Stock+round($cantidad*$equivalencia,4);
            $producto->save();
        }

        return redirect('/ventas');
    }
}
