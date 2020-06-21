<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Cotización</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">


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

</body>
</html>
