@extends('layouts.master')

@section('cabecera')
<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
	    <h1 class="m-0 text-dark">Repuestos</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
	    <ol class="breadcrumb float-sm-right">
	      <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
	      <li class="breadcrumb-item active">Repuestos</li>
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
            <h3 class="card-title">Listado de repuestos</h3>
            
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
              		@foreach ($repuestos as $key => $r)
              			<tr>
              				<td>{{$key+1}}</td>
                      <td>{{$r->nombre}}</td>
              				<td class="float-right">${{number_format($r->precio,2)}}</td>
              				<td>
                        <a href="javascript:void(0)" data-id="{{$r->id}}" class="btn btn-warning btn-sm edit"><i class="fas fa-edit"></i></a>
              					<a href="javascript:void(0)" data-id="{{$r->id}}" class="btn btn-danger btn-sm delete"><i class="fas fa-trash"></i></a>
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

<div class="modal fade" id="modal_repuesto" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Nuevo repuesto
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_repuesto" role="form">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Nombre del repuesto</label>
                      
                      <input type="text" name="nombre" placeholder="Digite el nombre del repuestos" class="form-control" autocomplete="off">
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
                          <input type="number" name="precio" class="form-control">
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

<div class="modal fade" id="modal_erepuesto" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Editar repuesto
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_erepuesto" role="form">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Nombre del repuesto</label>
                      
                      <input type="text" name="nombre" placeholder="Digite el nombre del repuestos" class="form-control nn" autocomplete="off">
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
          <button type="submit" class="btn btn-success submitrepuesto">Registrar</button>
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

    //nuevo
    $(document).on("click","#btn_nuevo",function(e){
      e.preventDefault();
      $("#modal_repuesto").modal("show");
    });

    //submit a form-trabajo
      $(document).on("submit","#form_repuesto",function(e){
        e.preventDefault();
        var datos=$("#form_repuesto").serialize();
        modal_cargando();
      $.ajax({
        url:'repuestos/guardar2',
        type:'POST',
        dataType:'json',
        data:datos,

        success: function(json){
          if(json[0]==1){
            toastr.success("Repuesto registrado con éxito");
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

    //editar repuesto
    $(document).on("click",".edit",function(e){
      e.preventDefault();
      var id=$(this).attr("data-id");
      $.ajax({
        url:'repuestos/'+id+'/edit',
        type:'get',
        dataType:'json',
        success: function(json){
          if(json[0]==1){
            $(".n_precio_r").val(json[2].precio);
            $(".nn").val(json[2].nombre);
            $(".cod").val(json[2].codigo);
            $(".elidd").val(json[2].id);

            $("#modal_erepuesto").modal("show");
          }
        }
      });
    });

        //submit a form-trabajo
      $(document).on("submit","#form_erepuesto",function(e){
        e.preventDefault();
        var id=$(".elidd").val();
        var datos=$("#form_erepuesto").serialize();
        modal_cargando();
        $.ajax({
          url:'repuestos/'+id,
          type:'put',
          dataType:'json',
          data:datos,

          success: function(json){
            if(json[0]==1){
              toastr.success("Repuesto editado con éxito");
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
      text: "¿Está seguro de eliminar el repuesto?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        modal_cargando();
        $.ajax({
          url:'repuestos/'+id,
          type:'DELETE',
          dataType:'json',
          data:{borrar:1},
          success: function(json){
            if(json[0]==1){
              toastr.success("Repuesto eliminado con éxito");
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
      title: '¿Restaurar?',
      text: "¿Está seguro de restaurar el repuesto?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        modal_cargando();
        $.ajax({
          url:'repuestos/'+id,
          type:'DELETE',
          dataType:'json',
          data:{borrar:0},
          success: function(json){
            if(json[0]==1){
              toastr.success("Repuesto restaurado con éxito");
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