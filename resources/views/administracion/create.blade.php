@extends('layouts.master')

@section('cabecera')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0 text-dark">Administración</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{url('home')}}">Inicio</a></li>
        <li class="breadcrumb-item active">Administración</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
			<div class="card card-primary">
				<div class="card-body">
					<p>
            Esta sección es para modificar información básica del taller como pueden ser: la direccion, los números de teléfono o los porcentajes de IVA y Renta.
          </p>
				</div>
			</div>
    </div>
        <div class="col-md-8">
            <div class="card ">
               <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#alcaldia" data-toggle="tab">Datos de Taller</a></li>
                  <li class="nav-item"><a class="nav-link" href="#porcentajes" data-toggle="tab">Porcentajes</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="" style=" ">
                
                <div class="tab-content">
                  <div class="active tab-pane" id="alcaldia" >
                    <div class="panel-body">
                    <div class="row">
                      <form id="form_taller">
                        <div class="col-md-9">
                        <div class="form-group">
                          <label for="" class="control-label">Propietario</label>
                          <input type="text" class="form-control" name="propietario" value="{{$taller->propietario}}">
                        </div>
                        <div class="form-group">
                          <label for="" class="control-label">Dirección</label>
                          <textarea name="direccion" id="" rows="2" class="form-control">{{$taller->direccion}}</textarea>
                        </div>
                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="" class="control-label">Teléfono 1</label>
                              <input type="text" name="telefono1" class="form-control" value="{{$taller->telefono1}}">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="" class="control-label">Teléfono 2</label>
                              <input type="text" name="telefono2" class="form-control" value="{{$taller->telefono2}}">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label for="" class="control-label">Teléfono celular</label>
                              <input type="text" name="celular" class="form-control" value="{{$taller->celular}}">
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="" class="control-label">Correo electrónico</label>
                          <input type="text" class="form-control" name="email" value="{{$taller->email}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <button class="btn btn-success" type="submit">Modificar</button>
                      </div>
                      </form>
                    </div>
            
                    </div>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="porcentajes">
                    <div class="panel">
                      <div class="panel-body">
                        <div class="row">
                          @foreach($porcentajes as $p)
                          <div class="col-md-3">
                            <label for="" class="control-label">% {{$p->nombre}}</label>
                            <div class="input-group">
                              <input type="number" min="0" value="{{$p->porcentaje}}"  name="porcentaje" class="form-control {{$p->nombre_simple}}">
                              <span class="input-group-btn">
                                <button type="button" data-porcen="{{$p->nombre_simple}}" data-id="{{$p->id}}" class="btn btn-success porcen"><i class="fas fa-sync"></i></button>
                              </span>
                            </div>
                          </div>
                          @endforeach 
                        </div>
                      </div>
                    </div>
                  </div>

                 
                  
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
              </div>
            </div>
        </div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  $(document).ready(function(e){
        swal.closeModal();

    //Guardar o editar taller
    $(document).on("submit","#form_taller",function(e){
      e.preventDefault();
      var datos=$("#form_taller").serialize();
      modal_cargando();
      $.ajax({
        url:'administracion/1',
        type:'put',
        dataType:'json',
        data:datos,
        success: function(json){
          if(json[0]==1){
            toastr.success("Datos modificados con éxito");
            location.reload();
          }else{
            toastr.error("Ocurrió un error, intente otra vez");
            swal.closeModal();
          }
        },error: function(e){
          toastr.error("Ocurrió un error, intente otra vez");
          swal.closeModal();
        }
      });
    });

    ///cambiar el porcentaje
    $(document).on("click",".porcen",function(e){
      e.preventDefault();
      var id=$(this).attr("data-id");
      var input=$(this).attr("data-porcen");
      var elvalor=$("."+input).val();
      modal_cargando();
      $.ajax({
        url:'administracion/porcentajes',
        type:'POST',
        dataType:'json',
        data:{id,porcentaje:elvalor},
        success: function(json){
          if(json[0]==1){
            toastr.success("Porcentaje actualizado con éxito");
            location.reload();
          }else{
            swal.closeModal();
            toastr.error("Ocurrió un error");
          }
        },
        error: function(error){
          swal.closeModal();
        }
      });
    });

  });
</script>
@endsection
