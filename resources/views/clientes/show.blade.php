@extends('layouts.master')

@section('cabecera')
<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
	    <h1 class="m-0 text-dark">Perfil del cliente</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
	    <ol class="breadcrumb float-sm-right">
	      <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
	      <li class="breadcrumb-item "><a href="{{url('clientes')}}">Clientes</a></li>
	      <li class="breadcrumb-item active">Perfil</li>
	    </ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
@php 
$marcas=\App\Marca::where('estado',1)->get();
@endphp
<div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('images/perfil_avatar.jpg')}}"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{$cliente->nombre}}</h3>

                <p class="text-muted text-center"><b>{{$cliente->sector}}</b></p>

                <ul class="list-group mb-3">
                  <li class="list-group-item">
                    <b>Estado</b> <div class="float-right">Activo</div>
                  </li>
                  <li class="list-group-item">
                    <b>Sector</b> <a class="float-right">{{$cliente->sector}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>NIT</b> <a class="float-right">{{$cliente->nit}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>E-mail</b> <a class="float-right">{{$cliente->correo}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Fecha de nacimiento</b> <a class="float-right">{{$cliente->fecha_nacimiento}}</a>
                  </li>
                   <li class="list-group-item">
                    <b>Tel. oficina</b> <a class="float-right">{{$cliente->telefono}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Tel. personal</b> <a class="float-right">{{$cliente->telefono2}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>FAX</b> <a class="float-right">{{$cliente->fax}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Dirección</b> <a class="float-right">{{$cliente->direccion}}</a>
                  </li>
                </ul>
				<div class="row">
					<div class="col-md-6">
						<a href="javascript:void(0)" data-id="{{$cliente->id}}" id="edit_cliente" class="btn btn-primary btn-block"><b>Editar</b></a>
					</div>
					<div class="col-md-6">
						<a href="javascript:void(0)" data-id="{{$cliente->id}}" id="eliminar_cliente" class="btn btn-danger btn-block"><b>Eliminar</b></a>
					</div>
				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#tabvehiculos" data-toggle="tab">Vehículos</a></li>
                  <li class="nav-item"><a class="nav-link" href="#historial" data-toggle="tab">Historial</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="tabvehiculos">
                    <!-- Post -->
                    <button class="btn btn-primary float-right" type="button" id="nuevo_vehiculo">Nuevo</button><br>
                    <div class="post">
                      <table class="table tabla-bordered" id="tablavehiculos">
                      	<thead>
                      		<tr>
                      			<th>N° placa</th>
                      			<th>Marca</th>
                      			<th>Modelo</th>
                      			<th>N° motor</th>
                      			<th>
                      				Acciones
                      			</th>
                      		</tr>
                      	</thead>
                      	<tbody>
                      		@foreach($cliente->vehiculo as $v)
                      			<tr>
                      				<td>{{$v->placa}}</td>
                      				<td>{{$v->marca->marca}}</td>
                      				<td>{{$v->modelo->nombre}}</td>
                      				<td>{{$v->motor}}</td>
                      				<td>
                      					<a href="javascript:void(0)" data-id="{{$v->id}}" id="edit_veh" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                      					<a href="javascript:void(0)" data-id="{{$v->id}}" id="quitar_veh" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                      				</td>
                      			</tr>
                      		@endforeach
                      	</tbody>
                      </table>
        
           	        </div>
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="historial">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-danger">
                          10 Feb. 2014
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-envelope bg-primary"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 12:05</span>

                          <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                          <div class="timeline-body">
                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                            quora plaxo ideeli hulu weebly balihoo...
                          </div>
                          <div class="timeline-footer">
                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-user bg-info"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                          <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend request
                          </h3>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-comments bg-warning"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                          <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                          <div class="timeline-body">
                            Take me to your leader!
                            Switzerland is small and neutral!
                            We are more like Germany, ambitious and misunderstood!
                          </div>
                          <div class="timeline-footer">
                            <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline time label -->
                      <div class="time-label">
                        <span class="bg-success">
                          3 Jan. 2014
                        </span>
                      </div>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div>
                        <i class="fas fa-camera bg-purple"></i>

                        <div class="timeline-item">
                          <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                          <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                          <div class="timeline-body">
                            <img src="http://placehold.it/150x100" alt="...">
                            <img src="http://placehold.it/150x100" alt="...">
                            <img src="http://placehold.it/150x100" alt="...">
                            <img src="http://placehold.it/150x100" alt="...">
                          </div>
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <div>
                        <i class="far fa-clock bg-gray"></i>
                      </div>
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
  
<div class="modal fade" id="modal_nuevo_v" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Registrar vehículo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_vehiculo" role="form">
          <input type="hidden" name="cliente_id" value="{{$cliente->id}}">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">N° de placa (*)</label>
                      <input type="text" name="placa" placeholder="Ingrese número de placa" autocomplete="off" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Año </label>
                      <input type="number" name="anio" placeholder="Ingrese el año" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Marca (*)</label>
                      <div class="row">
                        <div class="col-md-10">
                          <select name="marca_id" id="marca_id" class="chosen-select">
                            <option value="">Seleccione una marca</option>
                            @foreach($marcas as $m)
                              <option value="{{$m->id}}">{{$m->marca}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <button type="button" id="btn_modal_marca" class="btn btn-primary"><i class="fas fa-plus"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Modelo</label>
                      <div class="row">
                        <div class="col-md-10">
                          <select name="modelo_id" id="modelo_id" class="chosen-select">
                            <option value="">Seleccione un modelo</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <button class="btn btn-primary" type="button" id="btn_modal_modelo"><i class="fas fa-plus"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="row ">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="motor">N° de motor (*)</label>
                      <input type="text" name="motor" placeholder="Ingrese el número de motor" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="vin">N° VIN</label>
                      <input type="text" name="vin" placeholder="Ingrese el número de VIN" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="notas">Notas</label>
                      <textarea name="notas" rows="3" placeholder="Ingrese observaciones" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none"><button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button></div>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_marca" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Registrar marca</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_marca" role="form">
          <div class="card-body">
            <div class="form-group">
              <label for="">Nombre (*)</label>
              <input type="text" name="marca" autocomplete="off" class="form-control">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none"><button type="button" class="btn btn-danger" id="cerrar_modal_marca">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button></div>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_modelo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Registrar modelo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_modelo" role="form">
          <div class="card-body">
            <div class="form-group">
              <label for="">Nombre (*)</label>
              <input type="text" name="nombre" class="form-control" autocomplete="off" placeholder="Digite el nombre del modelo">
            </div>

            <div class="form-group">
              <label for="">Marca</label>
              <input type="text" id="n_marca" class="form-control" readonly>
              <input type="hidden" id="id_marca" name="marca_id" class="form-control" readonly>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none"><button type="button" class="btn btn-danger" id="cerrar_modal_modelo">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button></div>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_editar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header text-center">
            <h5 class="modal-title " id="exampleModalLabel">Editar cliente</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="form_ecliente" role="form">
              <div id="cuerpoaqui"></div>
          </div>
          <div class="modal-footer">
            <center><button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Guardar</button></center>
          </div>
          </form>
        </div>
      </div>
    </div>

<div id="aqui_modal"></div>

@endsection

@section('scripts')
<script src="{{asset('js/clientes.js?cod='.date('Yidisus'))}}"></script>
<script>
	$(document).ready(function(e){
    var cliente_id='<?php echo $cliente->id ?>';

		$("#tablavehiculos").DataTable();


	});
</script>
@endsection