<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;

use App\Http\Requests\VentaFormRequest;
use App\Venta;
use App\Detalle_Venta;
use App\Nota_Debito;
use App\Detalle_Nota_Debito;
use App\Cliente;
use App\Tipo_Comprobante;
use App\Impuesto;
use App\Producto;
use App\Producto_Empaque;
use App\Numeracion_Serie;
use DB;

class Nota_DebitoController extends Controller
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
        $venta = Venta::where('ID', $id)->first();

        date_default_timezone_set('America/Lima');
        $Fecha = date('Y-m-d H:i:s');

        $numeracion_serie = Numeracion_Serie::where('ID_TipoComprobante', "08")->where('ID_Negocio', Auth::user()['Id_Negocio'])->first();

        $Nota_Debito= new Nota_Debito;

        $Nota_Debito->Serie=$numeracion_serie->CodigoSerie;
        $serie=$Nota_Debito->Serie;//serie de la nota de credito

        $Nota_Debito->Numero=$numeracion_serie->NumeroActual;
        $numero=$Nota_Debito->Numero;

        $Nota_Debito->Fecha=$Fecha;
        $Nota_Debito->SerieReferencia=$venta->Serie;
        $Nota_Debito->NumeroReferencia=$venta->Numero;

        $Nota_Debito->FechaCreacion = $Fecha;
        $Nota_Debito->FechaModificacion = $Fecha;
        $Nota_Debito->ID_Usuario = Auth::id();

        $Nota_Debito->ID_Cliente = $venta->ID_Cliente;
        $Nota_Debito->MontoBruto = $venta->MontoBruto;
        $Nota_Debito->Impuesto = $venta->Impuesto;
        $Nota_Debito->Total = $venta->Total;
        $Nota_Debito->MontoReal = $venta->MontoReal;
        $Nota_Debito->DescuentoFijo= $venta->DescuentoFijo;
        $Nota_Debito->DescuentoPorcentual= $venta->DescuentoPorcentual;
        $Nota_Debito->Estado= $venta->Estado;
        $Nota_Debito->ID_MotivoAnulacion= "NIN";
        $Nota_Debito->ID_TipoComprobante= "08";
        $Nota_Debito->ID_Impuesto=$venta->ID_Impuesto;
        $Nota_Debito->ID_Negocio= Auth::user()['Id_Negocio'];
        $Nota_Debito->PorcentajeImpuesto= $venta->PorcentajeImpuesto;
        $Nota_Debito->Motivo= "";
        $Nota_Debito->save();

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

        $id_nota_debito=Nota_Debito::where('nota_debitos.Serie', $serie)->where('nota_debitos.Numero', $numero)
        ->select('nota_debitos.ID')->first();

        foreach ($detalle_venta as $key => $detalle)
        {
            $detalle_nota_debito = new Detalle_Nota_Debito;
            $detalle_nota_debito->FechaCreacion = $Fecha;
            $detalle_nota_debito->FechaModificacion = $Fecha;
            $detalle_nota_debito->ID_Usuario = Auth::id();

            $detalle_nota_debito->ID = $id_nota_debito->ID;
            $detalle_nota_debito->ID_Producto =  $detalle->ID_Producto;
            $detalle_nota_debito->ID_UnidadMedida =  $detalle->ID_UnidadMedida;
            $detalle_nota_debito->Cantidad =  $detalle->Cantidad;
            $detalle_nota_debito->PrecioUnitario =  $detalle->PrecioUnitario;
            $detalle_nota_debito->MontoBruto =  $detalle->MontoBruto;
            $detalle_nota_debito->DescuentoFijo =  $detalle->DescuentoFijo;
            $detalle_nota_debito->DescuentoPorcentual =  $detalle->DescuentoPorcentual;
            $detalle_nota_debito->MontoReal =  $detalle->MontoReal;
            $detalle_nota_debito->Impuesto =  $detalle->Impuesto;
            $detalle_nota_debito->Total =  $detalle->Total;
            $detalle_nota_debito->Estado =  "1";
            $detalle_nota_debito->save();
        }

        //return redirect('/ventas');
        return response()->json(['hola'=>'hola']);

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

        $numeracion_serie = Numeracion_Serie::where('ID_TipoComprobante', "08")->where('ID_Negocio', Auth::user()['Id_Negocio'])->first();

        $Nota_Debito= new Nota_Debito;



        $Nota_Debito->Serie=$numeracion_serie->CodigoSerie;
        $serie=$Nota_Debito->Serie;//serie de la nota de credito

        $Nota_Debito->Numero=$numeracion_serie->NumeroActual;
        $numero=$Nota_Debito->Numero;

        $Nota_Debito->Fecha=$Fecha;
        $Nota_Debito->SerieReferencia=$venta->Serie;
        $Nota_Debito->NumeroReferencia=$venta->Numero;

        $Nota_Debito->FechaCreacion = $Fecha;
        $Nota_Debito->FechaModificacion = $Fecha;
        $Nota_Debito->ID_Usuario = Auth::id();

        $Nota_Debito->ID_Cliente = $venta->ID_Cliente;
        $Nota_Debito->MontoBruto = $venta->MontoBruto;
        $Nota_Debito->Impuesto = $venta->Impuesto;
        $Nota_Debito->Total = $venta->Total;
        $Nota_Debito->MontoReal = $venta->MontoReal;
        $Nota_Debito->DescuentoFijo= $venta->DescuentoFijo;
        $Nota_Debito->DescuentoPorcentual= $venta->DescuentoPorcentual;
        $Nota_Debito->Estado= $venta->Estado;
        $Nota_Debito->ID_MotivoAnulacion= "NIN";
        $Nota_Debito->ID_TipoComprobante= "08";
        $Nota_Debito->ID_Impuesto=$venta->ID_Impuesto;
        $Nota_Debito->ID_Negocio= Auth::user()['Id_Negocio'];
        $Nota_Debito->PorcentajeImpuesto= $venta->PorcentajeImpuesto;
        $Nota_Debito->Motivo= "";
        $Nota_Debito->save();



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

        $id_nota_debito=Nota_Debito::where('nota_debitos.Serie', $serie)->where('nota_debitos.Numero', $numero)
        ->select('nota_debitos.id')->first();

  			foreach ($detalle_venta as $key => $detalle)
        {
            $detalle_nota_debito = new Detalle_Nota_Debito;
            $detalle_nota_debito->FechaCreacion = $Fecha;
            $detalle_nota_debito->FechaModificacion = $Fecha;
            $detalle_nota_debito->ID_Usuario = Auth::id();

            $detalle_nota_debito->ID = $id_nota_debito;
            $detalle_nota_debito->ID_Producto =  $detalle->ID_Producto;
            $detalle_nota_debito->ID_UnidadMedida =  $detalle->ID_UnidadMedida;
            $detalle_nota_debito->Cantidad =  $detalle->Cantidad;
            $detalle_nota_debito->PrecioUnitario =  $detalle->PrecioUnitario;
            $detalle_nota_debito->MontoBruto =  $detalle->MontoBruto;
            $detalle_nota_debito->DescuentoFijo =  $detalle->DescuentoFijo;
            $detalle_nota_debito->DescuentoPorcentual =  $detalle->DescuentoPorcentual;
            $detalle_nota_debito->MontoReal =  $detalle->MontoReal;
            $detalle_nota_debito->Impuesto =  $detalle->Impuesto;
            $detalle_nota_debito->Total =  $detalle->Total;
            $detalle_nota_debito->Estado =  "1";
            $detalle_nota_debito->save();

        }

        return redirect('/ventas');
    }
}
