<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\VentaFormRequest;
use App\Movimiento;
use App\Detalle_Movimiento;
use App\Motivo_Movimiento;
use App\Almacen;
use App\Venta;
use App\Detalle_Venta;
use App\Cliente;
use App\Ubigeo;
use App\Tipo_Comprobante;
use App\Impuesto;
use App\Producto;
use App\Producto_Empaque;
use App\Numeracion_Serie;
use Yajra\DataTables\Facades\DataTables;
use DB;

class VentaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuario = Auth::user();
        $ventas = Venta::where('ID_Negocio',$usuario['Id_Negocio'])->get();
        return view('ventas.index',['ventas'=>$ventas]);
    }

    public function productos()
    {
        //$productos = Producto::select('ID','ID_UnidadMedida','Descripcion','Stock','Precio1','Precio2','Precio3');
        $productos_generales=DB::table('productos')
        ->where('productos.Estado', "1")
        ->join('unidad_medidas','productos.ID_UnidadMedida','=','unidad_medidas.ID')
        ->select('productos.ID','productos.Nombre','productos.Stock','unidad_medidas.Nombre as UnidadMedida','productos.Precio1 as Precio1','productos.Precio2 as Precio2','productos.Precio3 as Precio3');
        $productos_empaques=DB::table('producto_empaques')
        ->join('unidad_medidas','producto_empaques.ID_UnidadMedida','=','unidad_medidas.ID')
        ->join('productos','productos.ID','=','producto_empaques.ID_Producto')
        ->where('productos.Estado', "1")
        ->select(DB::raw("CONCAT(producto_empaques.ID_Producto,'-',unidad_medidas.ID) as ID"),'productos.Nombre',DB::raw('productos.Stock / producto_empaques.Equivalencia as Stock'),'unidad_medidas.Nombre as UnidadMedida','producto_empaques.Precio1 as Precio1','producto_empaques.Precio2 as Precio2','producto_empaques.Precio3 as Precio3');
        $productos=$productos_generales->union($productos_empaques);
        //echo json_encode($productos);
        return Datatables::of($productos)->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes=Cliente::where('Estado', 1)->get();
        $ubigeos = DB::table('ubigeos')->orderBy('Nombre')->get();
        $tipo_comprobantes=Tipo_Comprobante::all();
        $impuestos=Impuesto::all();
        $productos=Producto::all();
        $producto_empaques=Producto_Empaque::all();
        return view('ventas.create',['clientes'=>$clientes, 'ubigeos'=>$ubigeos,'tipo_comprobantes'=>$tipo_comprobantes,'impuestos'=>$impuestos,'productos'=>$productos,'producto_empaques'=>$producto_empaques]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listaProductos = json_decode($request->get('listaProductos'));

        if( !empty($listaProductos) )
        {

            $numeracion_serie = Numeracion_Serie::where('ID_TipoComprobante', $request->get('seleccionarTipoComprobante'))
            ->where('ID_Negocio', Auth::user()['Id_Negocio'])->first();
            $Venta= new Venta;

            date_default_timezone_set('America/Lima');
            $Fecha = date('Y-m-d H:i:s');

            $CodigoSerie = $numeracion_serie->CodigoSerie;
            $NumeroActual = $numeracion_serie->NumeroActual;
            $Venta->Serie = $CodigoSerie;
            $Venta->Numero = $NumeroActual;
            $Venta->Fecha = $Fecha;

            $Venta->FechaCreacion = $Fecha;
            $Venta->FechaModificacion = $Fecha;
            $Venta->ID_Usuario = Auth::id();

            $Venta->ID_Cliente = $request->get('seleccionarCliente');
            $Venta->MontoBruto =round( $request->get('MontoBruto'),4);
            $Venta->Impuesto= round($request->get('nuevoPrecioImpuesto'),4);
            $Venta->Total= round($request->get('Total'),4);
            $Venta->MontoReal= round($request->get('MontoReal'),4);
            $Venta->DescuentoFijo= round($request->get('nuevoDescuentoFijo'),4);
            $Venta->DescuentoPorcentual= round($request->get('nuevoDescuentoPorcentual'),4);
            $Venta->Estado= "1";
            $Venta->ID_MotivoAnulacion= "NIN";
            $Venta->ID_TipoComprobante= $request->get('seleccionarTipoComprobante');
            $Venta->ID_Impuesto=$request->get('seleccionarImpuesto');
            $Venta->ID_Negocio= Auth::user()['Id_Negocio'];
            $Venta->PorcentajeImpuesto= round($request->get('nuevoImpuestoVenta'),2);
            $Venta->save();

            $Movimiento= new Movimiento;
            $Movimiento->Fecha = $Fecha;

            $Movimiento->FechaCreacion = $Fecha;
            $Movimiento->FechaModificacion = $Fecha;
            $Movimiento->ID_Usuario = Auth::id();
            $Movimiento->ID_Motivo = 1;
            $Movimiento->Descripcion = "Salida de Productos";
            $Movimiento->Estado = 1;
            $Movimiento->ID_Almacen = 1;
            $Movimiento->save();

            $id_movimiento=$Movimiento->ID;


            //modificar numeracion serie
            //$numeracion_serie = Numeracion_Serie::where('numeracion_series.ID_TipoComprobante', $request->get('seleccionarTipoComprobante'))
            //->where('numeracion_series.ID_Negocio', Auth::user()['Id_Negocio'])->first();
            if($numeracion_serie->NumeroActual==99999999)
            {
                $serie=substr($numeracion_serie->CodigoSerie, 1, 3);
                $serie=(string)($serie+1);
                $numeracion_serie->CodigoSerie=substr($numeracion_serie->CodigoSerie, 0, 1).str_pad($serie, 3, "0", STR_PAD_LEFT);
            }

            else
            {
                //$suma=(string)($numeracion_serie->NumeroActual+1);
                //$numeracion_serie->NumeroActual=str_pad($suma, 8, "0", STR_PAD_LEFT);
                $suma=$numeracion_serie->NumeroActual+1;
                $numeracion_serie->NumeroActual=$suma;
            }

            $numeracion_serie->FechaModificacion=$Fecha;
            $numeracion_serie->ID_Usuario=Auth::id();
            $numeracion_serie->save();

            // Detalle de la venta
            $venta = Venta::where('Serie', $CodigoSerie)->where('Numero',$NumeroActual)->first();

      			foreach ($listaProductos as $key => $detalle)
            {
                //$detalle=(array)$detalles;
                $id_producto="";
                //$descontar_stock=0.0;
                $Venta_detalle = new Detalle_Venta;
                $Venta_detalle->ID_Venta = $venta->ID;

                $claves = explode("-",(string)$detalle->id);
                $equivalencia=0;

                if(count($claves)==1)
                {
                    $Venta_detalle->ID_Producto=$detalle->id;
                    $unidadmedida=Producto::where('productos.ID', $detalle->id)->select('productos.ID_UnidadMedida')->first();
                    $Venta_detalle->ID_UnidadMedida=$unidadmedida['ID_UnidadMedida'];
                    $id_producto=$detalle->id;
                    $equivalencia=1;
                }

                else
                {
                    $Venta_detalle->ID_Producto=$claves[0];
                    $Venta_detalle->ID_UnidadMedida=$claves[1];
                    $producto_empaque = Producto_Empaque::where('ID_Producto', $claves[0])->where('ID_UnidadMedida', $claves[1])->first();
                    //$descontar_stock=$detalle->cantidad*$producto_empaque->Equivalencia;
                    $equivalencia=$producto_empaque->Equivalencia;
                    $id_producto=$claves[0];
                }

                $Venta_detalle->FechaCreacion = $Fecha;
                $Venta_detalle->FechaModificacion = $Fecha;
                $Venta_detalle->ID_Usuario =Auth::id();

                $Venta_detalle->Cantidad=round($detalle->cantidad,2);
                $Venta_detalle->PrecioUnitario=round($detalle->precio,4);
                $Venta_detalle->MontoBruto=round($detalle->montoBruto,4);
                $Venta_detalle->DescuentoFijo=round($detalle->descuento_fijo,4);
                $Venta_detalle->DescuentoPorcentual=round($detalle->descuento_porcentual);
                $Venta_detalle->MontoReal=round($detalle->montoReal,4);
                $Venta_detalle->Impuesto=round($detalle->impuesto_lineal,4);
                $Venta_detalle->Total=round($detalle->total,4);
                $Venta_detalle->Estado="0";
                $Venta_detalle->save();

                //modificar stock
                $detalle_movimiento = Detalle_Movimiento::where('ID_Movimiento', $id_movimiento)->where('ID_Producto',  $id_producto)->first();
                if(count($detalle_movimiento)==0)
                {
                    $detalle_Movimiento = new Detalle_Movimiento;
                    $detalle_Movimiento->FechaCreacion = $Fecha;
                    $detalle_Movimiento->FechaModificacion = $Fecha;
                    $detalle_Movimiento->ID_Usuario = Auth::id();

                    $detalle_Movimiento->ID_Movimiento=$id_movimiento;
                    $detalle_Movimiento->ID_Producto=$id_producto;
                    $detalle_Movimiento->TipoMovimiento="SAL";
                    $detalle_Movimiento->Cantidad=round(($detalle->cantidad)*$equivalencia,4);
                    $detalle_Movimiento->save();
                }
                else//existe
                {
                  //$detalle_Movimiento = Detalle_Movimiento::where('ID_Movimiento', $id_movimiento)->where('ID_Producto',  $id_producto)->first();
                  //var_dump($detalle_Movimiento);
                  $cantidad_actual=$detalle_movimiento->Cantidad;
                  $añadir=round($detalle->cantidad*$equivalencia,4);
                  $detalle_movimiento->Cantidad=10;
                  $detalle_movimiento->save();
                }



                $producto = Producto::findOrFail($id_producto);
                $producto->Stock=round(($detalle->stock)*$equivalencia,4);
                $producto->save();

                //registrar movimiento
      			}
        }

        else
        {

            return redirect('/ventas/create')->with('alert', 'error');

        }

        return redirect('/ventas/create')->with('alert', 'success');

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
        $clientes=Cliente::all();
        $tipo_comprobantes=Tipo_Comprobante::all();
        $impuestos=Impuesto::all();
        $productos=Producto::all();
        $producto_empaques=Producto_Empaque::all();

        $venta = Venta::where('ID', $id)->first();
        //var_dump($venta);
        /*$detalle_ventas = Detalle_Venta::where('ID_Venta', $id)
        ->join('unidad_medidas','detalle_ventas.ID_UnidadMedida','=','unidad_medidas.ID')
        ->join('productos','productos.ID','=','detalle_ventas.ID_Producto')
        ->join('producto_empaques','productos.ID','=','producto_empaques.ID_Producto')
        ->select(DB::raw("CONCAT(detalle_ventas.ID_Producto,'-',unidad_medidas.ID) as ID"),'productos.Nombre as Nombre',DB::raw('productos.Stock / producto_empaques.Equivalencia as Stock'),'unidad_medidas.Nombre as UnidadMedida','producto_empaques.Precio1 as Precio1','producto_empaques.Precio2 as Precio2','producto_empaques.Precio3 as Precio3')
        ->first();*/

        //var_dump($detalle_ventas);
        $productos_generales=DB::table('detalle_ventas')
        ->where('detalle_ventas.ID_Venta',$id)
        ->join('productos','productos.ID','=','detalle_ventas.ID_Producto')
        ->join('unidad_medidas','detalle_ventas.ID_UnidadMedida','=','unidad_medidas.ID')

        ->select('productos.ID','productos.Nombre','detalle_ventas.Cantidad','detalle_ventas.PrecioUnitario','detalle_ventas.DescuentoFijo','detalle_ventas.DescuentoPorcentual','detalle_ventas.Impuesto','productos.Stock','unidad_medidas.Nombre as UnidadMedida','detalle_ventas.PrecioUnitario','detalle_ventas.Total')
        ->get();
        $productos_empaques=DB::table('detalle_ventas')
        ->where('detalle_ventas.ID_Venta',$id)
        ->join('producto_empaques','producto_empaques.ID_Producto','=','detalle_ventas.ID_Producto')
        ->where('detalle_ventas.ID_UnidadMedida','producto_empaques.ID_UnidadMedida')
        ->join('unidad_medidas','detalle_ventas.ID_UnidadMedida','=','unidad_medidas.ID')
        ->join('productos','productos.ID','=','detalle_ventas.ID_Producto')

        ->select(DB::raw("CONCAT(producto_empaques.ID_Producto,'-',unidad_medidas.ID) as ID"),'productos.Nombre','detalle_ventas.Cantidad','detalle_ventas.PrecioUnitario','detalle_ventas.DescuentoFijo','detalle_ventas.DescuentoPorcentual','detalle_ventas.Impuesto',DB::raw('productos.Stock / producto_empaques.Equivalencia as Stock'),'unidad_medidas.Nombre as UnidadMedida','detalle_ventas.PrecioUnitario','detalle_ventas.Total')
        ->get();
        $detalle_ventas=$productos_generales->union($productos_empaques);

        //var_dump($detalle_ventas);
        //return Datatables::of($productos)->make(true);
        return view('ventas.edit',['venta'=>$venta,'detalle_ventas'=>$detalle_ventas,'clientes'=>$clientes,'tipo_comprobantes'=>$tipo_comprobantes,'impuestos'=>$impuestos,'productos'=>$productos,'producto_empaques'=>$producto_empaques]);

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
        $venta = Venta::findOrFail($id);
        date_default_timezone_set('America/Lima');
        $Fecha = date('Y-m-d H:i:s');

        //$Venta->Serie = $request->get('nuevaSerie');
        //$Venta->Numero = $request->get('nuevaVenta');
        $Venta->Fecha = $Fecha;

        $Venta->FechaModificacion = $Fecha;
        $Venta->ID_Usuario = Auth::id();

        $Venta->ID_Cliente = $request->get('seleccionarCliente');
        $Venta->MontoBruto = $request->get('seleccionarCliente');
        $Venta->Impuesto= $request->get('nuevoPrecioImpuesto');
        $Venta->Total= $request->get('nuevoPrecioNeto');
        $Venta->MontoReal= $request->get('nuevoTotalVenta');
        $Venta->DescuentoFijo= $request->get('nuevoDescuentoFijo');
        $Venta->DescuentoPorcentual= $request->get('nuevoDescuentoPorcentual');
        //$Venta->Estado= "1";
        //$Venta->ID_MotivoAnulacion= "1";
        $Venta->ID_TipoComprobante= $request->get('seleccionarTipoComprobante');
        $Venta->ID_Impuesto=$request->get('seleccionarImpuesto');
        $Venta->ID_Negocio= Auth::user()['Id_Negocio'];
        $Venta->PorcentajeImpuesto= $request->get('nuevoImpuestoVenta');;
        $Venta->save();

        $listaProductos = json_decode($request->get('listaProductos'));
        //return redirect('/ventas/create')->withInput();
        //return back()->withInput();

        //$totalProductosComprados = array();
        $id_venta=Venta::where('ventas.Serie', $request->get('nuevaSerie'))->where('ventas.Numero', $request->get('nuevaVenta'))
        ->select('ventas.id')->first();

        foreach ($listaProductos as $key => $detalle)
        {
            //$detalle=(array)$detalles;
            $id_producto="";
            //$descontar_stock=0.0;

            //validar si existe o no existe
            $Venta_detalle = new Detalle_Venta;
            $Venta_detalle->ID_Venta=$id_venta->id;

            $claves = explode("-",(string)$detalle->id);
            if(count($claves)==1)
            {
                $Venta_detalle->ID_Producto=$detalle->id;
                $Venta_detalle->ID_UnidadMedida="1";
                //modificar stock
                //$descontar_stock=$detalle->cantidad;
                $id_producto=$detalle->id;

            }
            else
            {
                $Venta_detalle->ID_Producto=$claves[0];
                $Venta_detalle->ID_UnidadMedida=$claves[1];
                $producto_empaque = Producto_Empaque::where('ID_Producto', $claves[0])->where('ID_UnidadMedida', $claves[1])->first();
                //$descontar_stock=$detalle->cantidad*$producto_empaque->Equivalencia;
                $id_Producto=$claves[0];
            }

            $Venta_detalle->FechaCreacion = $Fecha;
            $Venta_detalle->FechaModificacion = $Fecha;
            $Venta_detalle->ID_Usuario = "1";

            $Venta_detalle->Cantidad=$detalle->cantidad;
            $Venta_detalle->PrecioUnitario=$detalle->precio;
            $Venta_detalle->MontoBruto=$detalle->total;
            $Venta_detalle->DescuentoFijo=$detalle->descuento_fijo;
            $Venta_detalle->DescuentoPorcentual=$detalle->descuento_porcentual;
            $Venta_detalle->MontoReal=$detalle->total;
            $Venta_detalle->Impuesto="0";
            $Venta_detalle->Total=$detalle->total;
            $Venta_detalle->Estado="0";
            $Venta_detalle->save();

            //modificar stock
            $producto = Producto::findOrFail($id_producto);
            $producto->Stock=$detalle->stock;
            $producto->save();

            //registrar movimiento
        }

        return redirect('/ventas/create');
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
    /**
     * Modifica el estado del registro especificado.
     *
     * @param  int  $id
     * @param  int  $estado
     * @return \Illuminate\Http\Response
     */
    public function modificarEstado($id, $estado)
    {
        $venta = Venta::findOrFail($id);
        date_default_timezone_set('America/Lima');
        $fechaActual = date('Y-m-d H:i:s');

        $venta->FechaModificacion = $fechaActual;
        $venta->ID_Usuario = Auth::id();
        $est = 1;
        if($estado == 1){
          $est = 0;
        }
        $venta->Estado = $est;
        $res=$venta->save();

        if($res){
          return response()->json(['Estado' => $est]);
        }
    }
}
