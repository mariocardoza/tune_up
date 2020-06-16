@extends('layouts.master')

@section('cabecera')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0 text-dark">Respaldos</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">Respaldos</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-md-12">
            <button type="button" id="nuevo_backup" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo</button>
        </div>
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Respaldos</h3>
            
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body table-responsive">
              @if (count($respaldos))

                <table class="table table-striped table-bordered" id="example2">
                    <thead>
                    <tr>
                       <th>N°</th>
                        <th>Nombre</th>
                        <th>Tamaño</th>
                        <th>Fecha de creación</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($respaldos as $key => $respaldo)
                        <tr>
                          <td>{{$key+1}}</td>
                          <td>{{$respaldo['nombre']}}</td>
                          <td>{{tamaniohumano($respaldo['tamanio'])}}</td>
                          <td>{{fechaCastellano(date ("Y-m-d", $respaldo['fecha']))}}, {{date ("H:i:s.", $respaldo['fecha'])}}</td>
                          <td>
                            <div class="btn-group">
                              <a id="restaurar" title="Restaurar" data-archivo="{{$respaldo['nombre']}}" href="{{ url('backups/restaurar/'.$respaldo['nombre']) }}" class="btn btn-primary"><i class="fas fa-sync"></i></a>
                              <a href="{{ url('backups/descargar/'.$respaldo['nombre']) }}" class="btn btn-success"><i class="fas fa-download"></i></a>
                              <a id="eliminar" data-archivo="{{$respaldo['nombre']}}" title="Eliminar" href="{{ url('backups/eliminar/'.$respaldo['nombre']) }}" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
            @else
                <div class="well">
                    <h4>No existen respaldos</h4>
                </div>
            @endif
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
</div>
  
@endsection
@section('scripts')
<script>
  $(document).ready(function(e){
    swal.closeModal();
    //crear un nuevo backup
    $(document).on("click","#nuevo_backup",function(e){
      e.preventDefault();
      modal_cargando();
      $.ajax({
        url:'backups/create',
        type:'get',
        dataType:'json',
        success: function(json){
          if(json[0]==1){
            toastr.success("El respaldo creado con éxito");
            location.reload();
          }else{
            swal.closeModal();
            toastr.error("Ocurrió un error al realizar el respaldo");
          }
        },
        error: function (error){
          swal.closeModal();
            toastr.error("Ocurrió un error al realizar el respaldo");
        }
      });
    });


    //restaurar la base de datos
    $(document).on("click","#restaurar",function(e){
      e.preventDefault();
      var archivo=$(this).attr("data-archivo");
      modal_cargando();
      $.ajax({
        url:'backups/restaurar/'+archivo,
        type:'get',
        dataType:'json',
        success: function(json){
          if(json[0]==1){
            toastr.success("El respaldo ha sido restaurado con éxito");
            location.reload();
          }else{
            swal.closeModal();
            toastr.error("Ocurrió un error al restaurar el respaldo");
          }
        },
        error: function (error){
          swal.closeModal();
            toastr.error("Ocurrió un error al restaurar el respaldo");
        }
      });
    });


    //eliminar el respaldo
    $(document).on("click","#eliminar",function(e){
      e.preventDefault();
      var archivo=$(this).attr("data-archivo");
      modal_cargando();
      $.ajax({
        url:'backups/eliminar/'+archivo,
        type:'get',
        dataType:'json',
        success: function(json){
          if(json[0]==1){
            toastr.success("El respaldo ha sido eliminado con éxito");
            location.reload();
          }else{
            swal.closeModal();
            toastr.error("Ocurrió un error al eliminar el respaldo");
          }
        },
        error: function (error){
          swal.closeModal();
            toastr.error("Ocurrió un error al eliminar el respaldo");
        }
      });
    });
  });
</script>
@endsection