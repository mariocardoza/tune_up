<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cotización</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

 <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/app.css?cod='.date('Yidisus'))}}">
  <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/font.css')}}">
  <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{asset('css/bootstrap-datepicker3.min.css')}}">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline">
            
            <!-- /.card-header -->
            <div class="card-body p-0">
              <!-- /.mailbox-read-info -->
            
              <!-- /.mailbox-controls -->
              <div class="mailbox-read-message">
                <h2>TUNE UP SERVICE</h2>
                <i class="fas fa-remove"></i>
                <p style="font-weight: bold;">BUEN DÍA: {{$cotizacion->cliente->nombre}}</p>
                <p style="font-weight: bold;">ESTA ES LA COTIZACIÓN PARA SU VEHÍCULO:</p>
                <p><span style="font-weight: bold;">PLACA:</span> {{$cotizacion->vehiculo->placa}}</p>
                <p><span style="font-weight: bold;">MARCA:</span> {{$cotizacion->vehiculo->marca->marca}}</p>
                <p><span style="font-weight: bold;">MODELO:</span> @if($cotizacion->vehiculo->modelo!=''){{ $cotizacion->vehiculo->modelo->nombre }} @endif</p>
                <p><span style="font-weight: bold;">AÑO:</span> {{ $cotizacion->vehiculo->anio }}</p>
                <div class="table-responsive">
                  <table width="100%" rules="all" class="table table-bordered"  style="max-width: 100%; padding: 10px; margin:0 auto; border-collapse: collapse;">
                  <thead>
                    <tr>
                      <th width="5%">N°</th>
                      <th width="60%">DESCRIPCIÓN</th>
                      <th width="10%">PRECIO ($)</th>
                      <th width="10%">CANTIDAD</th>
                      <th width="15%">TOTAL ($)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $contador=0; ?>
                    @foreach($cotizacion->trabajodetalle as $t)
                    <?php $contador++; ?>
                    <tr>
                      <td>{{$contador}}</td>
                      <td>{{$t->trabajo->nombre}}</td>
                      <td style="text-align: right;">{{number_format($t->precio,2)}}</td>
                      <td style="text-align: right;">{{number_format($t->cantidad)}}</td>
                      <td style="text-align: right;">{{number_format($t->precio*$t->cantidad,2)}}</td>
        
                    </tr>
                    @endforeach
                    @foreach($cotizacion->repuestodetalle as $r)
                    <?php $contador++; ?>
                    <tr>
                      <td>{{$contador}}</td>
                      <td>{{$r->repuesto->nombre}}</td>
                      <td style="text-align: right;">{{number_format($r->precio,2)}}</td>
                      <td style="text-align: right;">{{number_format($r->cantidad)}}</td>
                      <td style="text-align: right;">{{number_format($r->precio*$r->cantidad,2)}}</td>
        
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                </div>
                
                <br>
                <p style="text-align: right;">SUBTOTAL: ${{number_format($cotizacion->subtotal,2)}}</p>
                <p style="text-align: right;">IVA: ${{number_format($cotizacion->iva,2)}}</p>
                <p style="text-align: right;">TOTAL: ${{number_format($cotizacion->total,2)}}</p>

              </div>
              <!-- /.mailbox-read-message -->
            </div>

            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="{{asset('js/app.js?cod='.date('Yidisus'))}}"></script>
<script src="{{asset('js/datatables.min.js?cod='.date('Yidisus'))}}"></script>
<script src="{{asset('js/toastr.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.es.min.js')}}"></script>
<script src="{{asset('js/generales.js?cod='.date('Yidisus'))}}"></script>

</body>
</html>
