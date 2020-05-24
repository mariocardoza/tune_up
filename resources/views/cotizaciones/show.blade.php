@extends('layouts.master')


@section('content')
@php
	$repuestos=App\Repuesto::where('estado',1)->get();
	$trabajos=App\Trabajo::where('estado',1)->get();
@endphp
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-primary">
				<div class="card-header">
					<div class="row">
						<div class="col-md-4"><a id="primera" data-id="{{$primera}}" href="javascript:void(0)" title="Primera" class="btn btn-success"><i class="fas fa-angle-double-left"></i></a>&nbsp;<a id="anterior" title="Anterior" data-id="{{$anterior}}" href="javascript:void(0)" class="btn btn-success"><i class="fas fa-angle-left"></i></a></div>
						<div class="col-md-4"><h3 class="card-title">Cotización número: <b>{{$cotizacion->correlativo}}</b></h3></div>
						<div class="col-md-4">
							<a id="ultima" data-id="{{$ultima}}" href="javascript:void(0)" class="btn btn-success float-right" title="Última"><i class="fas fa-angle-double-right"></i></a>

							<a id="siguiente" title="Siguiente" data-id="{{$siguiente}}" href="javascript:void(0)" class="btn btn-success float-right"><i class="fas fa-angle-right"></i></a>&nbsp;&nbsp;
						</div>
					</div>
					
				</div>
				<div class="card-body">
					<form id="form_coti">
						<div class="row">
							<div class="col-md-6">
								<h4 class="text-center">Datos del cliente</h4>
								<div class="form-group">
									<label for="" class="control-label">Cliente</label>
									<select name="cliente_id" id="cliente_id" class="chosen-select">
										<option value="">Seleccione un cliente</option>
										@foreach($clientes as $c)
											@if($c->id==$cotizacion->cliente_id)
												<option selected data-sector="{{$c->sector}}" data-direccion="{{$c->direccion}}" value="{{$c->id}}">{{$c->nombre}}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Fecha</label>
											<input type="text" name="fecha" readonly class="form-control" value="{{$cotizacion->fecha->format('d/m/Y')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Sector</label>
											<input type="text" readonly class="form-control sector">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="control-label">Dirección</label>
									<textarea rows="2" readonly class="form-control direcc"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<h4 class="text-center">Datos del vehículo</h4>
								<div class="form-group">
									<label for="" class="control-label">Vehículo</label>
									<select name="vehiculo_id" id="vehiculo_id" class="chosen-select">
										<option value="">Seleccione</option>
									</select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Km Recepción</label>
											<input type="number" name="kilometraje" readonly="" value="{{$cotizacion->vehiculo->kilometraje}}" class="form-control kilometraje" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Km próxima</label>
											<input type="number" value="{{$cotizacion->vehiculo->km_proxima}}" name="km_proxima" class="form-control kmproxi" readonly>
										</div>
									</div>
								</div>
								<div class="row" id="datos_carro">
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Marca:</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Modelo:</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Año:</label>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">N° motor:</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card card-default">
									<div class="card-header">
										<h3 class="float-left">Detalle</h3>
										<div class="float-right">
											<button type="button" id="md_trabajos" class="btn btn-info"><i class="fas fa-plus"></i> Mano de obra</button>
											<button type="button" id="md_repuestos" class="btn btn-info"><i class="fas fa-plus"></i> Repuesto</button>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-12 table-responsive">
												<table width="100%" class="table-bordered" id="tabita">
													<thead>
														<tr>
															<th>Detalle</th>
															<th>Precio ($)</th>
															<th>Cantidad</th>
															<th>Subtotal ($)</th>
															<th>Acciones</th>
														</tr>
													</thead>
													<tbody>
														
													</tbody>
													<tfoot></tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="text-center">
									
									<a href="{{url('cotizaciones/pdfcotizacion/'.$cotizacion->id)}}" target="_blank" class="btn btn-success imprime"><i class="fas fa-print"></i> Imprimir</a>
									<button type="button" title="Eliminar cotizacion" data-id="{{$cotizacion->id}}" class="btn btn-danger eliminar_lacoti"><i class="fas fa-trash"></i> Eliminar</button>
									<button type="button" title="Enviar cotizacion por correo" data-id="{{$cotizacion->id}}" class="btn btn-success enviar_correo"><i class="fas fa-envelope"></i> Enviar</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!--- Modales -->
<div class="modal fade" id="modal_repuesto" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Repuestos
			<button type="button" id="btn_nuevorepuesto" class="btn btn-info">Nuevo</button>
        	<button style="display: none;" type="button" id="btn_volverrepuestos" class="btn btn-info">Atrás</button>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="losrepuestos">
      		<div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Buscar repuesto</label>
                      <select name="" id="elselect_r" class="chosen-select">
                      	<option value="">Seleccione</option>
                      	@foreach($repuestos as $r)
                      		<option data-precio="{{$r->precio}}" data-codigo="{{$r->codigo}}" value="{{$r->id}}">{{$r->nombre}}</option>
                      	@endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Código</label>
                      <input type="text" placeholder="" readonly="" class="form-control codir">
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
              						<input type="number" id="n_precio_r" name="precio" class="form-control n_precio_r">
              					</div>
              				</div>
              				<div class="col-md-6">
              					<div class="form-group">
              						<label for="" class="control-label">Cantidad (*)</label>
              						<input type="number" value="1" id="n_cantidad_r" class="form-control n_cantidad_r">
              					</div>
              				</div>
              			</div>
              		</div>
              		<div class="col-md-4">
              			<div class="form-group">
      						<label for="" class="control-label">Subtotal (*)</label>
      						<input type="number" readonly class="form-control n_subto_r">
      					</div>
              		</div>
              	</div>
              </div>
            </div>
          </div>
      	</div>
        <form id="form_repuesto" style="display: none;" role="form">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="">Nombre del repuesto</label>
                      
                      <input type="text" name="nombre" class="form-control">
                      <input type="hidden" name="cotizacion_id" value="{{$cotizacion->id}}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Código</label>
                      <input type="text" name="codigo" placeholder="Ingrese el año" class="form-control">
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
              						<input type="number" name="precio" class="form-control n_precio_rr">
              					</div>
              				</div>
              				<div class="col-md-6">
              					<div class="form-group">
              						<label for="" class="control-label">Cantidad (*)</label>
              						<input type="number" name="cantidad" value="1" class="form-control n_cantidad_rr">
              					</div>
              				</div>
              			</div>
              		</div>
              		<div class="col-md-4">
              			<div class="form-group">
      						<label for="" class="control-label">Subtotal (*)</label>
      						<input type="number" readonly class="form-control n_subto_rr">
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
        	<button type="button" id="btn_agregar_repuesto" class="btn btn-success">Agregar</button>
        	<button type="submit" style="display: none;" class="btn btn-success submitrepuesto">Registrar</button>
    	</div>
      </div>
      </form>
    </div>
  </div>
</div>

<div id="modal_aqui"></div>

<div class="modal fade" id="modal_trabajo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Trabajos 
        	<button type="button" id="btn_nuevotrabajo" class="btn btn-info">Nuevo</button>
        	<button style="display: none;" type="button" id="btn_volvertrabajos" class="btn btn-info">Atrás</button>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="existente">
      		<div class="card-body">
	            <div class="row">
	              <div class="col-md-12">
	                <div class="row">
	                  <div class="col-md-8">
	                    <div class="form-group">
	                      <label for="">Buscar trabajo</label>
	                      <select name="" id="elselect_t" class="chosen-select">
	                      	<option value="">Seleccione</option>
	                      	@foreach($trabajos as $t)
                      			<option data-codigo="{{$t->codigo}}" data-precio="{{$t->precio}}" value="{{$t->id}}">{{$t->nombre}}</option>
                      		@endforeach
	                      </select>
	                    </div>
	                  </div>
	                  <div class="col-md-4">
	                    <div class="form-group">
	                      <label for="">Código</label>
	                      <input type="text" readonly placeholder="" class="form-control codi">
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
	              						<input type="number" id="n_precio_t" class="form-control n_precio_t">
	              					</div>
	              				</div>
	              			</div>
	              		</div>
	              		<div class="col-md-4">
	              			<div class="form-group">
	      						<label for="" class="control-label">Subtotal (*)</label>
	      						<input type="number" readonly class="form-control n_subto_t">
	      					</div>
	              		</div>
	              	</div>
	              </div>
	            </div>
          	</div>
      	</div>
        <form style="display: none;" id="form_trabajo" role="form">
          <div class="card-body">
	            <div class="row">
	              <div class="col-md-12">
	                <div class="row">
	                  <div class="col-md-8">
	                    <div class="form-group">
	                      <label for="">Nombre de la mano de obra</label>
	                      <input type="text" name="nombre" class="form-control">
	                      <input type="hidden" name="cotizacion_id" value="{{$cotizacion->id}}">
	                    </div>
	                  </div>
	                  <div class="col-md-4">
	                    <div class="form-group">
	                      <label for="">Código</label>
	                      <input type="text" name="codigo" placeholder="" class="form-control">
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
	              						<input type="number" name="precio" class="form-control n_precio_tr">
	              					</div>
	              				</div>
	              			</div>
	              		</div>
	              		<div class="col-md-4">
	              			<div class="form-group">
	      						<label for="" class="control-label">Subtotal (*)</label>
	      						<input type="number" readonly class="form-control n_subto_tr">
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
        	<button type="button" id="btn_agregar_trabajo" class="btn btn-success">Agregar</button>
        	<button type="submit" style="display: none;" class="btn btn-success submittrabajo">Registrar</button>
    	</div>
      </div>
      </form>
    </div>
  </div>
</div>


@endsection
@section('scripts')
<script src="{{asset('js/cotizaciones_show.js?cod='.date('Yidisus'))}}"></script>

<script>
	var elid='<?php echo $cotizacion->id; ?>';
	var v_id='<?php echo $cotizacion->vehiculo->id; ?>';
	$(document).ready(function(e){
		
		obtenerguardados(elid);
		info_carro(v_id);

		//cambiar el select de cliente
		$("#cliente_id").trigger("change");
		//eliminar la coti
		$(document).on("click",".eliminar_lacoti",function(e){
			e.preventDefault();
			var id=$(this).attr("data-id");
			swal.fire({
			  title: '¿Eliminar?',
			  text: "¿Está seguro de eliminar esta cotización?",
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Si'
			}).then((result) => {
			  if (result.value) {
			  	modal_cargando();
			    $.ajax({
			    	url:'../cotizaciones/'+id,
			    	type:'DELETE',
			    	dataType:'json',
			    	data:{id},
			    	success: function(json){
			    		if(json[0]==1){
			    			toastr.success("Cotización eliminado con éxito");
			    			swal.closeModal();
			    			location.href="../cotizaciones/create";
			    		}else{
			    			toastr.error("Ocurrió un error");
			    			swal.closeModal();
			    		}
			    	}
			    });
			  }
			});
		});
		//enviar por correo
		$(document).on("click",".enviar_correro",function(e){
			e.preventDefault();
			var id=$(this).attr("data-id");
			
  			
			$.ajax({
				url:'email/'+id,
				type:'get',
				cache: false,
				contentType: false,
            	processData: false,
             //xhrFields is what did the trick to read the blob to pdf
            	xhrFields: {
                	responseType: 'blob'
            	},
				success: function(response, status, xhr){
					var filename = "";                   
                var disposition = xhr.getResponseHeader('Content-Disposition');

                 if (disposition) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                } 
                var linkelem = document.createElement('a');
                try {
                    var blob = new Blob([response], { type: 'application/octet-stream' });                        

                    if (typeof window.navigator.msSaveBlob !== 'undefined') {
                        //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                    	//var test ="C:\\xampp\\htdocs\\taller_t\\public\\";
						//var URL = test.replace(/\\/g,'\');
						//console.log(test);
                        var URL =window.URL || window.webkitURL;
                        var downloadUrl = URL.createObjectURL(blob);

                        if (filename) { 
                            // use HTML5 a[download] attribute to specify filename
                            var a = document.createElement("a");

                            // safari doesn't support this yet
                            if (typeof a.download === 'undefined') {
                                window.location = downloadUrl;
                            } else {
                                a.href = downloadUrl;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.target = "_blank";
                                a.click();
                            }

                            $.ajax({
                            	url:'enviar',
                            	type:'get',
                            	data:{filename},
                            	success: function(json){

                            	}
                            });

                        } else {
                            window.location = downloadUrl;
                        }
                    }   

                } catch (ex) {
                    console.log(ex);
                } 
				}
			});
		});

		//imprimir frame
		$(document).on("click",".imprime",function(e){
			e.preventDefault();
			      $(".modal").modal("hide");

			var url = $(this).attr('href');
        	$('#verpdf').attr('src', url);
        	//$('#verpdf').reload();
        	$("#modal_pdf").modal("show");
		});
	});

	function obtenerguardados(id){
		$.ajax({
			url:'../cotizaciones/guardadas/'+id,
			type:'get',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#tabita>tbody").empty();
					$("#tabita>tbody").html(json[2]);
					$("#tabita>tfoot").html(json[3]);
				}
			}
		})
	}

	function info_carro(id){
		console.log(id);
		$.ajax({
			url:'../vehiculos/info/'+id,
			type:'get',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#datos_carro").empty();
					$("#datos_carro").html(json[2]);
					if(json[3]>0){
						$(".kilometraje").val(json[3])
						var proxi=json[3]+5000;
						$(".kmproxi").val(proxi);
					}
				}
			}
		});
	}
</script>
@endsection