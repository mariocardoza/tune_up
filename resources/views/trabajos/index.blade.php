@extends('layouts.master')

@section('cabecera')
<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
	    <h1 class="m-0 text-dark">Mano de obra</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
	    <ol class="breadcrumb float-sm-right">
	      <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
	      <li class="breadcrumb-item active">Mano de obra</li>
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
            <h3 class="card-title">Listado de mano de obra</h3>
            
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body table-responsive">
              <table class="table table-striped table-bordered" id="tabla">
              	<thead>
              		<tr>
              			<th>N°</th>
              			<th>Nombre</th>
                    <th class="float-right">Precio</th>
              			<th></th>
              		</tr>
              	</thead>
              	<tbody>
              		@foreach ($trabajos as $key => $t)
              			<tr>
              				<td>{{$key+1}}</td>
                      <td>{{$t->nombre}}</td>
              				<td class="float-right">${{number_format($t->precio,2)}}</td>
              				<td>
              					
              					<a href="javascript:void(0)" data-id="{{$t->id}}" class="btn btn-warning btn-sm edit"><i class="fas fa-edit"></i></a>
              					<a href="javascript:void(0)" data-id="{{$t->id}}" class="btn btn-danger btn-sm delete"><i class="fas fa-trash"></i></a>
              				</td>
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

<div class="modal fade" id="modal_trabajo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Nuevo mano de obra
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_trabajo" role="form">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Nombre de la mano de obra</label>
                      
                      <input type="text" name="nombre" placeholder="Digite el nombre de la mano de obra" class="form-control" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Código</label>
                      <input type="text" name="codigo" placeholder="Ingrese el código" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="" class="control-label">Precio (*)</label>
                          <input type="number" name="precio" class="form-control n_precio_r">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success submitrepuesto">Registrar</button>
      </div>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_etrabajo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Editar trabajo
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_etrabajo" role="form">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Nombre del trabajo</label>
                      
                      <input type="text" name="nombre" placeholder="Digite el nombre del trabajo" class="form-control nn" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Código</label>
                      <input type="text" name="codigo" placeholder="Ingrese el código" class="form-control cod">
                      <input type="hidden" placeholder="Ingrese el código" class="form-control elidd">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="" class="control-label">Precio (*)</label>
                          <input type="number" min="0" step="any" name="precio" class="form-control n_precio_r">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success">Registrar</button>
      </div>
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
		$("#tabla").DataTable({
	      "ordering": false,
	      dom: 'Bfrtip',
	      buttons: [
	        'pdf',
	      ],
	    });

	    //nuevo trabajo
	    $(document).on("click","#btn_nuevo",function(e){
	    	e.preventDefault();
	    	$("#modal_trabajo").modal("show");
	    });


	    //submit a form-trabajo
	    $(document).on("submit","#form_trabajo",function(e){
	    	e.preventDefault();
	    	var datos=$("#form_trabajo").serialize();
	    	modal_cargando();
			$.ajax({
				url:'trabajos/guardar2',
				type:'POST',
				dataType:'json',
				data:datos,

				success: function(json){
					if(json[0]==1){
						toastr.success("Trabajo registrado con éxito");
						location.reload();
					}else{
						swal.closeModal();
							toastr.error("Ocurrió un error");
						
					}
				},
				error: function(error){
					$.each(error.responseJSON.errors,function(index,value){
		      			toastr.error(value);
		      		});
		      		swal.closeModal();
				}
			});
	    });

	     //editar trabajo
    $(document).on("click",".edit",function(e){
      e.preventDefault();
      var id=$(this).attr("data-id");
      $.ajax({
        url:'trabajos/'+id+'/edit',
        type:'get',
        dataType:'json',
        success: function(json){
          if(json[0]==1){
            $(".n_precio_r").val(json[2].precio);
            $(".nn").val(json[2].nombre);
            $(".cod").val(json[2].codigo);
            $(".elidd").val(json[2].id);

            $("#modal_etrabajo").modal("show");
          }
        }
      });
    });

        //submit a form-trabajo
      $(document).on("submit","#form_etrabajo",function(e){
        e.preventDefault();
        var id=$(".elidd").val();
        var datos=$("#form_etrabajo").serialize();
        modal_cargando();
        $.ajax({
          url:'trabajos/'+id,
          type:'put',
          dataType:'json',
          data:datos,

          success: function(json){
            if(json[0]==1){
              toastr.success("Trabajo editado con éxito");
              location.reload();
            }else{
              swal.closeModal();
                toastr.error("Ocurrió un error");
              
            }
          },
          error: function(error){
            $.each(error.responseJSON.errors,function(index,value){
              toastr.error(value);
            });
            swal.closeModal();
          }
        	});
    	});

      //eliminar un repuesto
	$(document).on("click",".delete",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		swal.fire({
		  title: '¿Eliminar?',
		  text: "¿Está seguro de eliminar el trabajo?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si'
		}).then((result) => {
		  if (result.value) {
		  	modal_cargando();
		    $.ajax({
		    	url:'trabajos/'+id,
		    	type:'DELETE',
		    	dataType:'json',
		    	data:{borrar:1},
		    	success: function(json){
		    		if(json[0]==1){
		    			toastr.success("trabajo eliminado con éxito");
		    			swal.closeModal();
		    			location.reload();
		    		}else{
		    			toastr.error("Ocurrió un error");
		    			swal.closeModal();
		    		}
		    	}
		    });
		  }
		});
		
	});

	$(document).on("click",".restaurar",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		swal.fire({
		  title: '¿Reestaurar?',
		  text: "¿Está seguro de reestaurar el trabajo?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si'
		}).then((result) => {
		  if (result.value) {
		  	modal_cargando();
		    $.ajax({
		    	url:'trabajos/'+id,
		    	type:'DELETE',
		    	dataType:'json',
		    	data:{borrar:0},
		    	success: function(json){
		    		if(json[0]==1){
		    			toastr.success("trabajo reestaurarado con éxito");
		    			swal.closeModal();
		    			location.reload();
		    		}else{
		    			toastr.error("Ocurrió un error");
		    			swal.closeModal();
		    		}
		    	}
		    });
		  }
		});
		
	});
	});
</script>
@endsection