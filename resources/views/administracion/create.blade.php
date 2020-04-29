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
                  <div class="active tab-pane" id="alcaldia" style="max-height: 580px; overflow-y: scroll; overflow-y: auto;">
                    <div class="panel-body">

            
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

    //Guardar o editar alcaldia


  	$(document).on("click", "#img_file", function (e) {
        $("#file_1").click();
    });

    $(document).on("change", "#file_1", function(event) {
        validar_archivo($(this));
    });

    $(document).on("click","#subir_imagen",function(e){
    	var elid=$("#elid").val();
    	insertar_imagen($("#file_1"),elid);
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

    //cambiar el porcentaje a las retenciones /// ISSS, AFP...
    $(document).on("click",".reten",function(e){
      e.preventDefault();
      var id=$(this).attr("data-id");
      var input=$(this).attr("data-reten");
      var elvalor=$("."+input).val();
      modal_cargando();
      $.ajax({
        url:'configuraciones/retenciones',
        type:'POST',
        dataType:'json',
        data:{id,porcentaje:elvalor},
        success: function(json){
          if(json[0]==1){
            toastr.success("Porcentaje de la retención actualizado con éxito");
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

  function validar_archivo(file){
  $("#img_file").attr("src","../img/photo.svg");//31.gif
      //var ext = file.value.match(/\.(.+)$/)[1];
       //Para navegadores antiguos
       if (typeof FileReader !== "function") {
          $("#img_file").attr("src",'../img/photo.svg');
          return;
       }
       var Lector;
       var Archivos = file[0].files;
       var archivo = file;
       var archivo2 = file.val();
       if (Archivos.length > 0) {

          Lector = new FileReader();

          Lector.onloadend = function(e) {
              var origen, tipo, tamanio;
              //Envia la imagen a la pantalla
              origen = e.target; //objeto FileReader
              //Prepara la información sobre la imagen
              tipo = archivo2.substring(archivo2.lastIndexOf("."));
              console.log(tipo);
              tamanio = e.total / 1024;
              console.log(tamanio);

              //Si el tipo de archivo es válido lo muestra, 

              //sino muestra un mensaje 

              if (tipo !== ".jpeg" && tipo !== ".JPEG" && tipo !== ".jpg" && tipo !== ".JPG" && tipo !== ".png" && tipo !== ".PNG") {
                  $("#img_file").attr("src",'../img/photo.svg');
                  $("#error_formato1").removeClass('hidden');
                  //$("#error_tamanio"+n).hide();
                  //$("#error_formato"+n).show();
                  console.log("error_tipo");
                  
              }
              else{
                  $("#img_file").attr("src",origen.result);
                  $("#error_formato1").addClass('hidden');
                  $(".elsub").show();
              }


         };
          Lector.onerror = function(e) {
          console.log(e)
         }
         Lector.readAsDataURL(Archivos[0]);
  }
}

function insertar_imagen(archivo,elid){
        var file =archivo.files;
        var formData = new FormData();
        formData.append('formData', $("#form_logo"));
        var data = new FormData();
         //Append files infos
         jQuery.each(archivo[0].files, function(i, file) {
            data.append('file-'+i, file);
         });

         console.log("data",data);
         $.ajax({  
            url: "configuraciones/logo",  
            type: "POST", 
            dataType: "json",  
            data: {data,elid},  
            cache: false,
            processData: false,  
            contentType: false, 
            context: this,
            success: function (json) {
                console.log(json);

            }
        });
    }
</script>
@endsection
