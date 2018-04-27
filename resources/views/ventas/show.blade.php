@extends(Auth::user()['Id_Perfil'] == 1 ? 'layout.layout' : 'layout.layoutVendedor')

@section('estilos')
<!-- Page Data -->

<!-- DataTables -->

<link rel="stylesheet" href="/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<link rel="stylesheet" href="/adminlte/bower_components/datatables.net-bs/css/responsive.bootstrap.min.css">



<style>
  .modal-header {
      background-color: STEELBLUE;
      color:white !important;
      font-size: 40px;
  }

  .example-modal .modal {
    position: relative;
    top: auto;
    bottom: auto;
    right: auto;
    left: auto;
    display: block;
    z-index: 1;
  }

  .example-modal .modal {
    background: transparent !important;

  }
  input[type=number]::-webkit-outer-spin-button,
  input[type=number]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
  }

  input[type=number] {
      -moz-appearance:textfield;
  }
</style>
<!-- /.Modal adminlte -->

@stop

@section('encabezado')
<h1>
  Ventas
  <small>Optional description</small>
</h1>
<ol class="breadcrumb">
  <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
  <li class="active">Here</li>
</ol>
@stop

@section('content')
<div class="row">
  <!--=====================================
      EL FORMULARIO
      ======================================-->

      <div class="col-lg-12 col-xs-12">

        <div class="box box-success">

          <div class="box-header with-border"></div>

          <form role="form" method="post" class="formularioVenta"  >
            <input type="hidden" name="_token" value="{!! csrf_token() !!}">

            <div class="box-body">




              <div class="row" >

                <div class="col-xs-4">





                    <input type="text" class="form-control"  value="{{ $venta->Serie }}" readonly="">




                </div>
                <div class="col-xs-4">





                    <input type="text" class="form-control"  value="{{ $venta->Numero }}" readonly="">



                </div>
                <div class="col-xs-4">

                  <input type="dateTime" class="form-control"  value="{{ $venta->Fecha }}" readonly="">


                </div>

              </div>

              <hr>

                <!--=====================================
                ENTRADA DEL TIPO COMPROBANTE
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-shopping-cart"></i></span>

                    <input type="text" class="form-control"  value="{{ $venta->Tipo_Comprobante->Nombre }}" readonly="">


                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL CLIENTE
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-users"></i></span>

                    <input type="text" class="form-control"  value="{{ $venta->Cliente->RazonSocial }}" readonly="">

                  </div>



                </div>

                <!--=====================================
                ENTRADA DEL IMPUESTO
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-users"></i></span>

                    <input type="text" class="form-control"  value="{{ $venta->Impuestos->Nombre }}" readonly="">

                  </div>



                </div>

                <!--=====================================
                ENTRADA DEL SERIE
                ======================================-->



                <input type="hidden" class="form-control" id="nuevaSerie" name="nuevaSerie" value="{!! $venta->Serie !!}" readonly>

                <!--=====================================
                ENTRADA DEL NUMERO
                ======================================-->



                <input type="hidden" class="form-control" id="nuevaVenta" name="nuevaVenta" value="{!! $venta->Numero !!}" readonly>


                <div class="row" >
                    <div class="col-xs-4" style="padding-right:0px">
                        <label >Descripcion</label>
                    </div>

                    <div class="col-xs-2" style="padding-right:0px">
                        <label >Cantidad</label>
                    </div>

                     <div class="col-xs-2" style="padding-right:0px">
                           <label >Desc %</label>
                     </div>

                     <div class="col-xs-2" style="padding-left:0px">
                         <label >Desc fijo</label>
                     </div>

                     <div class="col-xs-2" style="padding-left:0px">
                        <label >Total </label>
                     </div>
                </div>


                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================-->

                <div class="form-group row nuevoProducto">



                @if(!empty($detalle_ventas))

                @foreach($detalle_ventas as $detalle_venta)



                  <div class="row" style="padding:5px 15px">

    			          <!-- Descripcion del producto -->

        	          <div class="col-xs-4" style="padding-right:0px">

        	            <div class="input-group">

        	              <input type="text"  class="form-control" value="{!! $detalle_venta->Nombre !!} {!! $detalle_venta->UnidadMedida !!}" readonly >

        	            </div>

        	          </div>


        	          <!-- Cantidad del producto -->

        	          <div class="col-xs-2 ingresoCantidad" style="padding-right:0px">

          	             <input type="text"  class="form-control" value="{!! $detalle_venta->Cantidad !!}"  readonly>

        	          </div>

                    <!-- Descuento del producto -->

                    <div class="col-xs-2 ingresoDescuentoPorcentual" style="padding-right:0px">

                        <input type="text"   class="form-control" value="{!! $detalle_venta->DescuentoPorcentual !!}" readonly >

                    </div>

                    <div class="col-xs-2 ingresoDescuentoFijo" style="padding-left:0px">

                        <input type="text"  class="form-control" value="{!! $detalle_venta->DescuentoFijo !!}" readonly>

                    </div>


      	            <!-- Precio del producto -->

        	          <div class="col-xs-2 ingresoPrecio" style="padding-left:0px">

        	            <div class="input-group">

        	              <input type="text"  class="form-control" value="{!! $detalle_venta->Total !!}" readonly >

        	            </div>

                    </div>

                  </div>
                @endforeach
                @endif

                </div>




                <hr>
                <!--=====================================
                ENTRADA PARA EL DESCUENTO
                ======================================-->

                <div class="col-xs-12 pull-right">

                  <table class="table">

                    <thead>

                      <tr>
                        <th>Descuento %</th>
                        <th>Descuento Fijo</th>
                      </tr>

                    </thead>

                    <tbody>

                      <tr>

                        <td style="width: 40%">

                          <div class="input-group">

                            <input type="text"   class="form-control" value="{!! $venta->DescuentoPorcentual !!}" readonly="">



                            <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                          </div>

                        </td>

                         <td style="width: 60%">

                          <div class="input-group">

                            <span class="input-group-addon">S/.</i></span>

                            <input type="text"  class="form-control" value="{!! $venta->DescuentoFijo !!}" readonly="">




                          </div>

                        </td>

                      </tr>

                    </tbody>

                  </table>

                </div>

                <div class="row">

                  <!--=====================================
                  ENTRADA IMPUESTOS Y TOTAL
                  ======================================-->

                  <div class="col-xs-12 pull-right">

                    <table class="table">

                      <thead>

                        <tr>
                          <th>% IGV</th>
                          <th>Total</th>
                        </tr>

                      </thead>

                      <tbody>

                        <tr>

                          <td style="width: 40%">

                            <div class="input-group">

                              <input type="text"   class="form-control" value="{!! $venta->PorcentajeImpuesto !!}" readonly >


                              <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                            </div>


                          </td>

                           <td style="width: 60%">

                            <div class="input-group">

                              <span class="input-group-addon">S/.</i></span>

                              <input type="text"  class="form-control"  value="{!! $venta->Total !!}" readonly >




                            </div>

                          </td>

                        </tr>

                      </tbody>

                    </table>

                  </div>




                </div>


                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

                    <input type="text" class="form-control" id="nuevoVendedor" value="{!! $usuario->name !!}" readonly>


                  </div>

                </div>

              </div>

          </div>

          <div class="box-footer">



          </div>

        </form>


        </div>

      </div>




</div>
<!-- /.row -->

@stop




@section('js')


<script src="/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="/adminlte/bower_components/datatables.net-bs/js/dataTables.responsive.min.js"></script>
<script src="/adminlte/bower_components/datatables.net-bs/js/responsive.bootstrap.min.js"></script>

@stop

@section('jsope')



@stop
