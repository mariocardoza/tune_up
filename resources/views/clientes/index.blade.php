@extends('layouts.master')

@section('cabecera')
<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
	    <h1 class="m-0 text-dark">Clientes</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
	    <ol class="breadcrumb float-sm-right">
	      <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
	      <li class="breadcrumb-item active">Clientes</li>
	    </ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
    	<div class="col-md-12">
            <button type="button" id="btn_nuevo" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo</button>
        </div>
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Listado de clientes</h3>
            
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body table-responsive">
              <table class="table table-striped table-bordered" id="tablaclientes">
              	<thead>
              		<tr>
              			<th>Cod</th>
              			<th>Sector</th>
              			<th>Nombres</th>
              			<th>Teléfono</th>
              			<th>DUI</th>
              			<th>NIT</th>
              			<th></th>
              		</tr>
              	</thead>
              	<tbody>
              		@foreach ($clientes as $key => $c)
              			<tr>
              				<td>CL{{$key+1}}</td>
              				<th>{{$c->sector}}</th>
              				<td>{{$c->nombre}}</td>
              				<td>{{$c->telefono}}</td>
              				<td>{{$c->dui}}</td>
              				<td>{{$c->nit}}</td>
              				<th>
              					<a href="{{ url('clientes/'.$c->id)}}" class="btn btn-info"><i class="fas fa-eye"></i></a>
              				</th>
              			</tr>
              		@endforeach
              	</tbody>
              </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- Form Element sizes -->
        
        <!-- /.card -->

      </div>
      <!--/.col (left) -->
      <!-- right column -->
    
      <!--/.col (right) -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->

  <!-- Modal -->
<div class="modal fade" id="modal_nuevo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Registrar cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_cliente" role="form">
        	<div class="card-body">
        		<div class="row">
        			<div class="col-md-6">
        				<div class="row">
        					<div class="col-md-6">
        						<div class="form-group">
		        					<label for="">Tipo de persona</label>
		        					<select name="tipo" id="" class="form-control">
		        						<option selected value="">Seleccione..</option>
		        						<option value="1">Natural</option>
		        						<option value="2">Jurídica</option>
		        					</select>
                      <input type="hidden" name="estado" value="1">
		        				</div>
		        				<div class="form-group">
		        					<label for="">Nombre</label>
		        					<input type="text" name="nombre" autocomplete="off" class="form-control">
		        				</div>
		        				<div class="form-group">
		        					<label for="">NIT</label>
		        					<input type="text" name="nit" autocomplete="off" class="form-control nit">
		        				</div>
		        				<div class="form-group">
		        					<label for="">E-mail</label>
		        					<input type="email" name="correo" autocomplete="off" class="form-control">
		        				</div>
        					</div>
        					<div class="col-md-6">
        						<div class="form-group">
		        					<label for="">Sector</label>
		        					<select name="sector" id="elsector" class="form-control">
		        						<option value="Contribuyentes">Contribuyentes</option>
		        						<option value="Gran Contribuyente">Gran contribuyente</option>
		        						<option selected value="No Contribuyente">No contribuyente</option>
		        					</select>
		        				</div>
		        				<div class="contri" style="display: none;">
		        					<div class="form-group">
		        						<label for="">Registro de IVA</label>
		        						<input type="text" name="reg_iva" class="form-control">
		        					</div>
		        					<div class="form-group">
		        						<label for="">Giro</label>
		        						<input type="text" name="giro" class="form-control">
		        					</div>
		        					<div class="form-group">
		        						<label for="">Contacto</label>
		        						<input type="text" name="nombre_contacto" class="form-control">
		        					</div>		
		        				</div>
        					</div>
        				</div>
        			</div>

        			<div class="col-md-6">
        				<div class="row nocontri">
        					<div class="col-md-6">
        						<div class="from-group">
        							<label for="fecha_nacimiento">Fecha de nacimiento</label>
        							<input type="date" name="fecha_nacimiento" class="form-control">
        						</div>
        					</div>
        					<div class="col-md-6">
        						<div class="form-group">
        							<label for="dui">DUI</label>
        							<input type="text" name="dui" class="form-control dui">
        						</div>
        					</div>
        				</div>
        				<div class="row">
        					<div class="col-md-4">
        						<div class="form-group">
        							<label for="telefono">Telefono oficina</label>
        							<input type="text" name="telefono" class="form-control telefono">
        						</div>
        					</div>
        					<div class="col-md-4">
        						<div class="form-group">
        							<label for="telefono2">Telefono personal</label>
        							<input type="text" name="telefono2" class="form-control telefono">
        						</div>
        					</div>
        					<div class="col-md-4">
        						<div class="form-group">
        							<label for="fax">FAX</label>
        							<input type="text" name="fax" class="form-control telefono">
        						</div>
        					</div>
        				</div>
        				<div class="row">
        					<div class="col-md-12">
        						<div class="form-group">
        							<label for="direccion">Dirección</label>
        							<textarea name="direccion" rows="3" class="form-control"></textarea>
        						</div>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
      </div>
      <div class="modal-footer">
        <center><button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button></center>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
	$(document).ready(function(e){
        swal.closeModal();

		$("#tablaclientes").DataTable({
      "ordering": false,
      dom: 'Bfrtip',
      buttons: [
        'pdf',
      ],
    });

		//modal para registrar un nuevo cliente
		$(document).on("click","#btn_nuevo",function(e){
			e.preventDefault();
			$("#modal_nuevo").modal("show");
		});

		//evento change para el select del sector
		$(document).on("change","#elsector",function(e){
			e.preventDefault();
			var esto=$(this).val();
			if(esto == 'Contribuyentes'){
				$(".contri").show();
				$(".nocontri").hide();
			}
			else if(esto=="Gran Contribuyente"){
				$(".contri").show();
				$(".nocontri").hide();
			}else{
				$(".contri").hide();
				$(".nocontri").show();
			}
		});

		//submit al formulario de nuevo cliente
		$(document).on("submit","#form_cliente",function(e){
			e.preventDefault();
			var datos=$("#form_cliente").serialize();
			$.ajax({
				url:'clientes',
				type:'POST',
				dataType:'json',
				data:datos,
				success: function(json){
					if(json[0]==1){
						toastr.success("Cliente registrado con éxito");
						location.href='clientes/'+json[2];
					}else{
						toastr.error("Ocurrió un error");
					}
				},
				error: function(error){
					$.each(error.responseJSON.errors,function(index,value){
      			toastr.error(value);
      		});
				}
			});
		});


    
	});
</script>
@endsection