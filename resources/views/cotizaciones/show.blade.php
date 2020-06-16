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
									@if($cotizacion->vehiculo->tipomedida=='km')
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Km Recepción</label>
											<input type="number" name="kilometraje" value="{{$cotizacion->kilometraje}}" class="form-control kimi" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Km próxima</label>
											<input type="number" value="{{$cotizacion->km_proxima}}" name="km_proxima" class="form-control kimiproxi" readonly="">
										</div>
									</div>
									@else
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Mi Recepción</label>
											<input type="number" name="kilometraje" value="{{$cotizacion->kilometraje}}" class="form-control millaje" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Mi próxima</label>
											<input type="number" value="{{$cotizacion->km_proxima}}" name="km_proxima" class="form-control miproxi" step="any" readonly>
										</div>
									</div>

									@endif
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
									@if($cotizacion->coniva=='no')
									<button type="button" title="Aplicar IVA" data-id="{{$cotizacion->id}}" class="btn btn-info aplicar_iva"><i class="fas fa-money"></i> Aplicar IVA</button>
									<button class="btn btn-success convertir" title="Convertir a Factura" data-id="{{$cotizacion->id}}" data-estado="2" type="button">FCC</button>
									<button class="btn btn-success convertir" title="Convertir a Factura de exportación" data-id="{{$cotizacion->id}}" data-estado="4" type="button">FE</button>
									@else
									<button type="button" title="Quitar IVA" data-id="{{$cotizacion->id}}" class="btn btn-info quitar_iva"><i class="fas fa-money"></i> Quitar IVA</button>
									<button class="btn btn-success convertir" title="Convertir a Crédito fiscal" data-id="{{$cotizacion->id}}" data-estado="3" type="button">FCF</button>
									@endif
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
<div class="modal fade" id="modal_convertir" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">¿Está seguro de convertir la cotización?
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	
        <form id="form_convertir" role="form">
        	<div class="row">
        		<div class="col-md-12">
        			<div class="form-group">
		        		<label for="">Fecha</label>
		        		<input type="text" name="fecha" class="form-control fecha" value="{{$cotizacion->fecha->format('d/m/Y')}}">
		        		<input type="hidden" name="id" class="form-control" value="{{$cotizacion->id}}">
		        		<input type="hidden" name="estado" class="convertir_estado">
		        	</div>
        		</div>
        	</div>
        </form>
          
      </div>
      <div class="modal-footer">
        <div class="float-none">
        	<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        	<button type="button" id="convierte" class="btn btn-success">Confirmar</button>
    	</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_enviar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">¿Enviar cotización por correo electrónico?
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	
        <form id="form_email" role="form">
        	<div class="row">
        		<div class="col-md-12">
        			<div class="form-group">
		        		<label for="">Correo electrónico</label>
		        		<input type="text" name="correo" class="form-control " value="{{$cotizacion->cliente->correo}}">
		        		<input type="hidden" name="id" class="form-control" value="{{$cotizacion->id}}">
		        		
		        	</div>
		        	<div class="form-group">
		        		<label for="">Correo adicional</label>
		        		<input type="text" name="adicional" class="form-control">
		        		
		        	</div>
        		</div>
        	</div>
        </form>
          
      </div>
      <div class="modal-footer">
        <div class="float-none">
        	<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        	<button type="button" id="enviar" class="btn btn-success">Enviar</button>
    	</div>
      </div>
    </div>
  </div>
</div>

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
                      <input type="text" placeholder=""  readonly="" class="form-control codir">
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
              						<input type="number" step="any" id="n_precio_r" name="precio" class="form-control n_precio_r">
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
                      
                      <input type="text" autocomplete="off" name="nombre" class="form-control">
                      <input type="hidden" name="cotizacion_id" value="{{$cotizacion->id}}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="">Código</label>
                      <input type="text" name="codigo" autocomplete="off" placeholder="Ingrese el año" class="form-control">
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
              						<input type="number" step="any" name="precio" class="form-control n_precio_rr">
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
	              						<input type="number" step="any" id="n_precio_t" class="form-control n_precio_t">
	              					</div>
	              				</div>
	              			</div>
	              		</div>
	              		<div class="col-md-4">
	              			<div class="form-group">
	      						<label for="" class="control-label">Subtotal (*)</label>
	      						<input type="number" step="any" readonly class="form-control n_subto_t">
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
	                      <input type="text" autocomplete="off" name="nombre" class="form-control">
	                      <input type="hidden" name="cotizacion_id" value="{{$cotizacion->id}}">
	                    </div>
	                  </div>
	                  <div class="col-md-4">
	                    <div class="form-group">
	                      <label for="">Código</label>
	                      <input type="text" autocomplete="off" name="codigo" placeholder="" class="form-control">
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
	              						<input type="number" step="any" name="precio" class="form-control n_precio_tr">
	              					</div>
	              				</div>
	              			</div>
	              		</div>
	              		<div class="col-md-4">
	              			<div class="form-group">
	      						<label for="" class="control-label">Subtotal (*)</label>
	      						<input type="number" step="any" readonly class="form-control n_subto_tr">
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
		swal.closeModal();
		obtenerguardados(elid);
		info_carro(v_id);

		//modal convertir cotizacion
		$(document).on("click",".convertir",function(e){
			e.preventDefault();
			var estado=$(this).attr("data-estado");
			$(".convertir_estado").val(estado);
			$("#modal_convertir").modal("show");
		});

		//convertir cotizacion
		$(document).on("click","#convierte",function(e){
			e.preventDefault();
			var datos=$("#form_convertir").serialize();
			modal_cargando();
			$.ajax({
				url:'../cotizaciones/convertir',
				type:'post',
				dataType:'json',
				data:datos,
				success: function(json){
					if(json[0]==1){
						toastr.success("Cotización convertida con éxito");
						location.href=json[2];
					}else{
						toastr.error("Ocurrió un error");
						swal.closeModal();
					}
				},
				error: function(e){
					toastr.error("Ocurrió un error");
					swal.closeModal();
				}
			});
		});

		//enviar cotizacion por correo
		$(document).on("click",".enviar_correo",function(e){
			e.preventDefault();
			$("#modal_enviar").modal("show");
		});

		$(document).on("click","#enviar",function(e){
			e.preventDefault();
			var datos=$("#form_email").serialize();
			modal_cargando();
			$.ajax({
				url:'enviar',
				type:'post',
				data:datos,
				dataType:'json',
				
            	processData: true,
             //xhrFields is what did the trick to read the blob to pdf
            	
				success: function(response, status, xhr){
					
                	if(response){
                		toastr.success("Cotización enviada con éxito");
                		swal.closeModal();
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

		//aplicar iva a la cotizacion
		$(document).on("click",".aplicar_iva",function(e){
			e.preventDefault();
			var id=$(this).attr("data-id");
			$.ajax({
				url:'../cotizaciones/eliva/'+id,
				type:'post',
				dataType:'json',
				data:{aplicariva:'si'},
				success: function(json){
					if(json[0]==1){
						toastr.success("IVA aplicado con éxito");
						location.reload();
					}else{
						toastr.success('Ocurrió un error, Intente nuevamente');
					}
				},error: function(e){
					toastr.success('Ocurrió un error, Intente nuevamente');
				}
			});
		});

		//quitar iva a la cotizacion
		$(document).on("click",".quitar_iva",function(e){
			e.preventDefault();
			var id=$(this).attr("data-id");
			$.ajax({
				url:'../cotizaciones/eliva/'+id,
				type:'post',
				dataType:'json',
				data:{aplicariva:'no'},
				success: function(json){
					if(json[0]==1){
						toastr.success("IVA quitado con éxito");
						location.reload();
					}else{
						toastr.success('Ocurrió un error, Intente nuevamente');
					}
				},error: function(e){
					toastr.success('Ocurrió un error, Intente nuevamente');
				}
			});
		});
	});

	function obtenerguardados(id){
		modal_cargando();
		$.ajax({
			url:'../cotizaciones/guardadas/'+id,
			type:'get',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#tabita>tbody").empty();
					$("#tabita>tbody").html(json[2]);
					$("#tabita>tfoot").html(json[3]);
					swal.closeModal();
				}else{
					swal.closeModal();
				}
			},
			error:function(e){
				swal.closeModal();
			}
		});
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