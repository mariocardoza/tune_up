@extends('layouts.master')
<?php 
$marcas=[];$clientes=[];
$marcas=App\Marca::where('estado',1)->get();
$clientes=App\Cliente::where('estado',1)->get();
 ?>
@section('cabecera')
<div class="container-fluid">
	<div class="row mb-2">
	  <div class="col-sm-6">
	    <h1 class="m-0 text-dark">Vehículos</h1>
	  </div><!-- /.col -->
	  <div class="col-sm-6">
	    <ol class="breadcrumb float-sm-right">
	      <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
	      <li class="breadcrumb-item active">Vehículos</li>
	    </ol>
	  </div><!-- /.col -->
	</div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">
    	<div class="col-md-12">
            <button type="button" id="nuevo_vehiculo" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo</button>
        </div>
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Listado de vehículos</h3>
            
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body table-responsive">
              <table class="table table-striped table-bordered" id="tablaclientes">
              	<thead>
              		<tr>
              			<th>N°</th>
              			<th>Placa</th>
              			<th>Marca</th>
              			<th>Modelo</th>
              			<th>Propietario</th>
              			<th>Año</th>
              			<th></th>
              		</tr>
              	</thead>
              	<tbody>
              		@foreach ($vehiculos as $key => $v)
              			<tr>
                      <td>{{$key+1}}</td>
                      <td>{{$v->placa}}</td>
                      <td>{{$v->marca->marca}}</td>
                      <td>@if($v->modelo_id!=''){{$v->modelo->nombre}}@else -- @endif</td>
                      <td>{{$v->cliente->nombre}}</td>
                      <td>{{$v->anio}}</td>
              				<td>
                        <button type="button" id="edit_veh" data-id="{{$v->id}}" class="btn btn-warning">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" data-id="{{$v->id}}" id="quitar_veh"><i class="fas fa-trash"></i></button>
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

  <!-- Modal -->
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
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Cliente (*)</label>
                      <div class="row">
                        <div class="col-md-10">
                          <select name="cliente_id" id="cliente_id" class="chosen-select">
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $c)
                              <option value="{{$c->id}}">{{$c->nombre}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <!--button type="button" id="btn_modal_cliente" class="btn btn-primary"><i class="fas fa-plus"></i></button-->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">N° de placa (*)</label>
                      <input type="text" name="placa" style="text-transform:uppercase;" placeholder="Ingrese número de placa" autocomplete="off" class="form-control laplaca">
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
                      <label for="">Tipo de medida</label>
                      <select name="tipomedida" class="chosen-select" id="">
                        <option value="km">Kilómetro</option>
                        <option value="mi">Millas</option>
                      </select>
                    </div>
                  </div>
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

<div class="modal fade" id="modal_edit_v" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Editar vehículo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_evehiculo" role="form">
          <input type="hidden" class="elidv">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-12">
                <div class="form-group">
                  <label for="">Cliente (*)</label>
                  <div class="row">
                    <div class="col-md-10">
                      <select name="cliente_id" id="cliente_ide" class="chosen-select">
                        <option value="">Seleccione un cliente</option>
                        @foreach($clientes as $c)
                          <option value="{{$c->id}}">{{$c->nombre}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-2">
                      <!--button type="button" id="btn_modal_clientee" class="btn btn-primary"><i class="fas fa-plus"></i></button-->
                    </div>
                  </div>
                </div>
              </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">N° de placa (*)</label>
                      <input type="text" name="placa" style="text-transform:uppercase;" placeholder="Ingrese número de placa" autocomplete="off" class="form-control placa">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="">Año </label>
                      <input type="number" name="anio" placeholder="Ingrese el año" class="form-control anio">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Marca (*)</label>
                      <div class="row">
                        <div class="col-md-10">
                          <select name="marca_id" id="marca_ide" class="chosen-select">
                            <option value="">Seleccione una marca</option>
                            @foreach($marcas as $m)
                              <option value="{{$m->id}}">{{$m->marca}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <button type="button" id="btn_modal_marcae" class="btn btn-primary"><i class="fas fa-plus"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Modelo</label>
                      <div class="row">
                        <div class="col-md-10">
                          <select name="modelo_id" id="modelo_ide" class="chosen-select">
                            <option value="">Seleccione un modelo</option>
                          </select>
                        </div>
                        <div class="col-md-2">
                          <button class="btn btn-primary" type="button" id="btn_modal_modeloe"><i class="fas fa-plus"></i></button>
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
                      <label for="">Tipo de medida</label>
                      <select name="tipomedida" class="chosen-select" id="tipomedida">
                        <option value="km">Kilómetro</option>
                        <option value="mi">Millas</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="motor">N° de motor (*)</label>
                      <input type="text" name="motor" placeholder="Ingrese el número de motor" class="form-control motor">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="vin">N° VIN</label>
                      <input type="text" name="vin" placeholder="Ingrese el número de VIN" class="form-control vin">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="notas">Notas</label>
                      <textarea name="notas" rows="3" placeholder="Ingrese observaciones" class="form-control notas"></textarea>
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

<div class="modal fade" id="modal_marca_e" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Registrar marca</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_marcae" role="form">
          <div class="card-body">
            <div class="form-group">
              <label for="">Nombre (*)</label>
              <input type="text" name="marca" autocomplete="off" class="form-control">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none"><button type="button" class="btn btn-danger" id="cerrar_modal_marcae">Cerrar</button>
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

<div class="modal fade" id="modal_modeloe" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Registrar modelo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_modeloe" role="form">
          <div class="card-body">
            <div class="form-group">
              <label for="">Nombre (*)</label>
              <input type="text" name="nombre" class="form-control" autocomplete="off" placeholder="Digite el nombre del modelo">
            </div>

            <div class="form-group">
              <label for="">Marca</label>
              <input type="text" id="n_marcae" class="form-control" readonly>
              <input type="hidden" id="id_marcae" name="marca_id" class="form-control" readonly>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <div class="float-none"><button type="button" class="btn btn-danger" id="cerrar_modal_modeloe">Cerrar</button>
        <button type="submit" class="btn btn-success">Guardar</button></div>
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

		//modal para registrar un nuevo carro
    $(document).on("click","#nuevo_vehiculo",function(e){
    e.preventDefault();
    $("#modal_nuevo_v").modal("show");
  });


  //select de las marcas
  $(document).on("change","#marca_id",function(e){
    e.preventDefault();
    var id=$(this).val();
    $.ajax({
      url:'clientes/obtenermodelos/'+id,
      type:'GET',
      dataType:'json',
      success: function(json){
        if(json[0]==1){
          $("#modelo_id").empty();
          $("#modelo_id").html(json[2]);
          $(".chosen-select").chosen({'width':'100%'});
          $(".chosen-select").trigger('chosen:updated');
        }
      }
    });
  });

  $(document).on("blur",".laplaca",function(e){
    e.preventDefault();
    var placa=$(this).val();
    $.ajax({
            url:'vehiculos/porplaca',
            type:'get',
            dataType:'json',
            data:{placa},
            success: function(json){
              console.log(json);
              if(json[1]==null || json[0].length == 0){
                  
              }else{  
                swal.fire('Aviso','La placa ya existe','warning');
              }
            },error: function(error){
            }
          });

  });

  $(document).on("change","#marca_ide",function(e){
    e.preventDefault();
    var id=$(this).val();
    $.ajax({
      url:'clientes/obtenermodelos/'+id,
      type:'GET',
      dataType:'json',
      success: function(json){
        if(json[0]==1){
          $("#modelo_ide").empty();
          $("#modelo_ide").html(json[2]);
          $(".chosen-select").chosen({'width':'100%'});
          $(".chosen-select").trigger('chosen:updated');
        }
      }
    });
  });

  //editar vehiculo
  $(document).on("click","#edit_veh",function(e){
    e.preventDefault();
    var id=$(this).attr("data-id");
    modal_cargando();
    $.ajax({
      url:'vehiculos/'+id+'/edit',
      type:'GET',
      dataType:'json',
      success: function(json){
        if(json[0]==1){
          $(".elidv").val(json[2].id);
          $(".placa").val(json[2].placa);
          $(".anio").val(json[2].anio);
          $(".motor").val(json[2].motor);
          $(".vin").val(json[2].vin);
          $(".notas").val(json[2].notas);
          $("#marca_ide").val(json[2].marca_id);
          $("#tipomedida").val(json[2].tipomedida);
          $("#tipomedida").trigger('chosen:updated');
          $("#marca_ide").trigger('chosen:updated');
          $("#marca_ide").trigger("change");
          setTimeout(() => {
                $("#modelo_ide").val(json[2].modelo_id);
                $("#cliente_ide").val(json[2].cliente_id);
            $("#modelo_ide").trigger('chosen:updated');
            $("#cliente_ide").trigger('chosen:updated');
            $("#modal_edit_v").modal("show");
            swal.closeModal();
            }, 1000);
          
        }else{
          toastr.error("Ocurrió un error, Intente de nuevo");
          swal.closeModal();
        }
      }, error : function(error){
        toastr.error("Ocurrió un error, Intente de nuevo");
          swal.closeModal();
      }
    });
  });

    //guardar el vehiculo
  $(document).on("submit","#form_vehiculo",function(e){
    e.preventDefault();
    var datos=$("#form_vehiculo").serialize();
    $.ajax({
      url:'vehiculos',
      type:'POST',
      dataType:'json',
      data:datos,
      success: function(json){
        if(json[0]==1){
          toastr.success("Vehiculo registrado con éxito");
          location.reload();
        }else{
          toastr.error("Ocurrió un error, Intente otra vez");
          swal.closeModal();
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

  //submit editar vehiculo
    $(document).on("submit","#form_evehiculo",function(e){
    e.preventDefault();
    var datos=$("#form_evehiculo").serialize();
    var id=$(".elidv").val();
    modal_cargando();
    $.ajax({
      url:'vehiculos/'+id,
      type:'put',
      dataType:'json',
      data:datos,
      success: function(json){
        if(json[0]==1){
          toastr.success("Vehiculo editado con éxito");
          location.reload();
        }
        else{
          toastr.error("Ocurrió un error, Intente otra vez");
          swal.closeModal();
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
 

  //modal para guardar nueva marca
  $(document).on("click","#btn_modal_marca",function(e){
    e.preventDefault();
    $("#modal_nuevo_v").modal("hide");
    $("#modal_marca").modal("show");
  });

  $(document).on("click","#btn_modal_marcae",function(e){
    e.preventDefault();
    $("#modal_edit_v").modal("hide");
    $("#modal_marca_e").modal("show");
  });
  //cerrar el modal
  $(document).on("click","#cerrar_modal_marca",function(e){
    e.preventDefault();
    $("#modal_nuevo_v").modal("show");
    $("#modal_marca").modal("hide");
  });
  $(document).on("click","#cerrar_modal_marcae",function(e){
    e.preventDefault();
    $("#modal_edit_v").modal("show");
    $("#modal_marca_e").modal("hide");
  });

  //modal para guardar nuevo modelo
  $(document).on("click","#btn_modal_modelo",function(e){
    e.preventDefault();
    var id_marca=$("#marca_id").val();
    if(id_marca!=''){
      var marca=$("#marca_id option:selected").text();
      console.log(id_marca,marca);
      $("#n_marca").val(marca);
      $("#id_marca").val(id_marca);
      $("#modal_nuevo_v").modal("hide");
      $("#modal_modelo").modal("show");
    }else{
      swal.fire(
        '¡Aviso!',
        'Debe seleccionar una marca',
        'error'
      );
    }
  });

  $(document).on("click","#btn_modal_modeloe",function(e){
    e.preventDefault();
    var id_marca=$("#marca_ide").val();
    if(id_marca!=''){
      var marca=$("#marca_ide option:selected").text();
      console.log(id_marca,marca);
      $("#n_marcae").val(marca);
      $("#id_marcae").val(id_marca);
      $("#modal_edit_v").modal("hide");
      $("#modal_modeloe").modal("show");
    }else{
      swal.fire(
        '¡Aviso!',
        'Debe seleccionar una marca',
        'error'
      );
    }
  });

  //cerrar el modal
  $(document).on("click","#cerrar_modal_modelo",function(e){
    e.preventDefault();

    $("#modal_nuevo_v").modal("show");
    $("#modal_modelo").modal("hide");
  });

  //cerrar el modal
  $(document).on("click","#cerrar_modal_modeloe",function(e){
    e.preventDefault();

    $("#modal_edit_v").modal("show");
    $("#modal_modeloe").modal("hide");
  });

  //submit para el form modelo
  $(document).on("submit","#form_marca",function(e){
    e.preventDefault();
    var datos=$("#form_marca").serialize();
    $.ajax({
      url:'../marcas',
      type:'post',
      dataType:'json',
      data:datos,
      success: function(json){
        if(json[0]==1){
          $("#marca_id").append("<option selected value='"+json[2].id+"'>"+json[2].marca+"</option>");
          $("#marca_id").trigger("chosen:updated");
          toastr.success("Marca registrada con éxito");
          $("#modal_nuevo_v").modal("show");
          $("#modal_marca").modal("hide");
          $("#form_marca").trigger("reset");
        }
      },
      error: function(error){
        $.each(error.responseJSON.errors,function(index,value){
              toastr.error(value);
            });
      }
    });
  });

  $(document).on("submit","#form_marcae",function(e){
    e.preventDefault();
    var datos=$("#form_marcae").serialize();
    $.ajax({
      url:'marcas',
      type:'post',
      dataType:'json',
      data:datos,
      success: function(json){
        if(json[0]==1){
          $("#marca_ide").append("<option selected value='"+json[2].id+"'>"+json[2].marca+"</option>");
          $("#marca_ide").trigger("chosen:updated");
          toastr.success("Marca registrada con éxito");
          $("#modal_edit_v").modal("show");
          $("#modal_marcae").modal("hide");
          $("#form_marcae").trigger("reset");
        }
      },
      error: function(error){
        $.each(error.responseJSON.errors,function(index,value){
              toastr.error(value);
            });
      }
    });
  });

  //submit para el form modelo
  $(document).on("submit","#form_modelo",function(e){
    e.preventDefault();
    var datos=$("#form_modelo").serialize();
    $.ajax({
      url:'modelos',
      type:'post',
      dataType:'json',
      data:datos,
      success: function(json){
        if(json[0]==1){
          $("#modelo_id").append("<option selected value='"+json[2].id+"'>"+json[2].nombre+"</option>");
          $("#modelo_id").trigger("chosen:updated");
          toastr.success("Modelo registrado con éxito");
          $("#modal_nuevo_v").modal("show");
          $("#modal_modelo").modal("hide");
          $("#form_modelo").trigger("reset");
        }
      },
      error: function(error){
        $.each(error.responseJSON.errors,function(index,value){
              toastr.error(value);
            });
      }
    });
  });

  $(document).on("submit","#form_modeloe",function(e){
    e.preventDefault();
    var datos=$("#form_modeloe").serialize();
    $.ajax({
      url:'modelos',
      type:'post',
      dataType:'json',
      data:datos,
      success: function(json){
        if(json[0]==1){
          $("#modelo_ide").append("<option selected value='"+json[2].id+"'>"+json[2].nombre+"</option>");
          $("#modelo_ide").trigger("chosen:updated");
          toastr.success("Modelo registrado con éxito");
          $("#modal_edit_v").modal("show");
          $("#modal_modeloe").modal("hide");
          $("#form_modeloe").trigger("reset");
        }
      },
      error: function(error){
        $.each(error.responseJSON.errors,function(index,value){
              toastr.error(value);
            });
      }
    });
  });

  //quitar vehiculo
  $(document).on("click","#quitar_veh",function(e){
    e.preventDefault();
    var id=$(this).attr("data-id");
    swal.fire({
      title: '¿Está seguro?',
      text: "El vehiculo de eliminará",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url:'vehiculos/'+id,
          type:'delete',
          dataType:'json',
          success: function(json){
            if(json[0]==1){
              toastr.success("Vehículo eliminado con éxito");
              location.reload();
            }
          },error: function(error){
            toastr.error("Ocurrió un error");
          }
        });
      }
    });
  });
    
	});
</script>
@endsection