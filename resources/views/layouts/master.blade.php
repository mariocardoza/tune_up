
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TUNE-UP</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/app.css?cod='.date('Yidisus'))}}">
  <link rel="stylesheet" href="{{asset('css/ionicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/font.css')}}">
  <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{asset('css/bootstrap-datepicker3.min.css')}}">
  <style>
    .form-control{
      text-transform:uppercase;
    }
  </style>
</head>
@php 
  $uc=$uf=$ucf=$ue=0;
  $ultimacoti=\App\Cotizacione::where('tipo_documento',1)->get();
  if(count($ultimacoti)>0) {$uc=$ultimacoti->last()->id;}

  $ultimafactura=\App\Cotizacione::where('tipo_documento',2)->get();
  if(count($ultimafactura)>0) { $uf=$ultimafactura->last()->id;}

  $ultimacredito=\App\Cotizacione::where('tipo_documento',3)->get();
  if(count($ultimacredito)>0) { $ucf=$ultimacredito->last()->id;}

  $ultimaexpor=\App\Cotizacione::where('tipo_documento',4)->get();
  if(count($ultimaexpor)>0) { $ue=$ultimaexpor->last()->id;}
@endphp
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <!-- Message Start -->
            <div class="media">
              <i class="fas fa-user"></i>
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Cerrar sesión
                </h3>
              </div>
            </div>
            <!-- Message End -->
          </a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
     
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('home')}}" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">TuneUp</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth()->user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="{{url('/home')}}" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Inicio
                
              </p>
            </a>
            
          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('clientes')}}" class="nav-link {{ (Route::currentRouteName() == 'clientes.index' ? 'active':null )}}">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Clientes
              </p>
            </a>
            
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ Route::currentRouteName() == 'cotizaciones.show' ? 'active': Route::currentRouteName() == 'cotizaciones.create' ? 'active': null}} ">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Cotizaciones
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('cotizaciones/'.$uc)}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ver</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('cotizaciones/create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Nueva</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ Route::currentRouteName() == 'facturas.show' ? 'active': Route::currentRouteName() == 'facturas.create' ? 'active': null}} ">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Consumidor Final
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('facturas/'.$uf)}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ver</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('facturas/create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Nueva</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ Route::currentRouteName() == 'creditos.show' ? 'active': Route::currentRouteName() == 'creditos.create' ? 'active': null}} ">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Crédito fiscal
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('creditos/'.$ucf)}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ver</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('creditos/create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Nueva</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ Route::currentRouteName() == 'exportaciones.show' ? 'active': Route::currentRouteName() == 'exportaciones.create' ? 'active': null}} ">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Exportación
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('exportaciones/'.$ue)}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ver</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('exportaciones/create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Nueva</p>
                </a>
              </li>
            </ul>
          </li>
          

          <li class="nav-item has-treeview">
            <a href="{{url('administracion')}}" class="nav-link {{ Route::currentRouteName() == 'administracion.index' ? 'active':null}}">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Administración
              </p>
            </a>
            
          </li>

           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Informes
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link reportevehiculo">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Por vehículo</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link ivapagar">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Reporte de IVA a pagar</p>
                </a>
              </li>
            </ul>
          </li>
          
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Utilitarios
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('trabajos')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mano de obra</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('repuestos')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Repuestos</p>
                </a>
              </li>
            </ul>
          </li>
         
        </ul>

      </nav>

      <!-- /.sidebar-menu -->
    </div>

    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      @yield('cabecera')<!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      @yield('content')
      <!-- Default box -->
      
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<!-- modales aqui -->
<div class="modal fade" id="modal_reporte_carro" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Reporte de trabajos por vehiculos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_buscaplaca">
          <div class="form-group">
            <label for="" class="control-label">Digite el número de placa</label>
            <input type="text" class="form-control" id="laplaquita" autocomplete="off" placeholder="Digite la placa">
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none"><button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Buscar</button></div>
      </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_reporte_iva" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Reporte de IVA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_buscaiva">
          <div class="form-group">
            <label for="" class="control-label">Digite la fecha de inicio</label>
            <input type="text" class="form-control fecha" id="fecha1" autocomplete="off" placeholder="Digite la fecha 1">
          </div>
          <div class="form-group">
            <label for="" class="control-label">Digite la fecha fin</label>
            <input type="text" class="form-control fecha" id="fecha2" autocomplete="off" placeholder="Digite la fecha 2">
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none"><button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Buscar</button></div>
      </form>
      </div>
    </div>
  </div>
</div>

<!-- fin modales -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; {{date("Y")}} <a href="javascript:void(0)">mrcardoza</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('js/app.js?cod='.date('Yidisus'))}}"></script>
<script src="{{asset('js/datatables.min.js?cod='.date('Yidisus'))}}"></script>
<script src="{{asset('js/toastr.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/bootstrap-datepicker.es.min.js')}}"></script>

<script src="{{asset('js/generales.js?cod='.date('Yidisus'))}}"></script>
<!-- Bootstrap 4 -->
@yield('scripts')
</body>
</html>
