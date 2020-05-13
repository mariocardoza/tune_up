@extends('layouts.master')


@section('content')
@php
	$repuestos=App\Repuesto::where('estado',1)->get();
	$trabajos=App\Trabajo::where('estado',1)->get();
@endphp
<style>
	.trr{
	  height: 5px;
	}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card card-primary">
				<div class="card-header">
					<h3 class="card-title">Registrar crédito fiscal</h3>
					<div class="float-right">
						<button type="button" class="btn btn-danger buscaplaca">Buscar</button>
					</div>
				</div>
				<div class="card-body">
					<form id="form_coti">
						<div class="row">
							<div class="col-md-6">
								<h4 class="text-center">Datos del cliente</h4>
								<div class="form-group">
									<label for="" class="control-label">Cliente</label>
									<input type="hidden" name="cotizacion_id" id="cotizacion_id" value="0">
									<select name="cliente_id" id="cliente_id" class="chosen-select">
										<option value="">Seleccione un cliente</option>
										@foreach($clientes as $c)
											<option data-sector="{{$c->sector}}" data-direccion="{{$c->direccion}}" value="{{$c->id}}">{{$c->nombre}}</option>
										@endforeach
									</select>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Fecha</label>
											<input type="text" name="fecha" class="form-control fecha" value="{{date('d/m/Y')}}">
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
								<!--div class="form-group">
									<label for="">¿IVA?</label>
									<select name="" id="eliva" class="chosen-select">
										<option selected="" value="no">No</option>
										<option value="si">Si</option>
									</select>
								</div-->
									<input type="hidden" name="total" value="" id="txttotal">
									<input type="hidden" name="subtotal" value="" id="txtsubtotal">
								
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
											<input type="number" name="kilometraje" class="form-control kilometraje" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="control-label">Km próxima</label>
											<input type="number" name="km_proxima" class="form-control kmproxi" readonly>
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
											<div class="col-md-12">
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
									<button type="submit" class="btn btn-success">Registrar</button>
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
                      		<option data-codigo="{{$r->codigo}}" data-precio="{{$r->precio}}" value="{{$r->id}}">{{$r->nombre}}</option>
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
              						<input type="number" id="n_precio_r" name="precio" class="form-control precio_r">
              					</div>
              				</div>
              				<div class="col-md-6">
              					<div class="form-group">
              						<label for="" class="control-label">Cantidad (*)</label>
              						<input type="number" value="1" id="n_cantidad_r" class="form-control cantidad_r">
              					</div>
              				</div>
              			</div>
              		</div>
              		<div class="col-md-4">
              			<div class="form-group">
      						<label for="" class="control-label">Subtotal (*)</label>
      						<input type="number" readonly class="form-control subto_r">
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
              						<input type="number" name="precio" class="form-control n_precio_r">
              					</div>
              				</div>
              				<div class="col-md-6">
              					<div class="form-group">
              						<label for="" class="control-label">Cantidad (*)</label>
              						<input type="number" name="cantidad" value="1" class="form-control n_cantidad_r">
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
	                      <input type="text" readonly readonly class="form-control codi">
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
	                      <input type="text" name="nombre" class="form-control nont">
	                    </div>
	                  </div>
	                  <div class="col-md-4">
	                    <div class="form-group">
	                      <label for="">Código</label>
	                      <input type="text" name="codigo" placeholder="" class="form-control codt">
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

<div class="modal fade" id="modal_placa" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title " id="exampleModalLabel">Buscar por placa 
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

		<div class="form-group">
			<input type="text" class="form-control txtplaca">
		</div>      
      </div>
    </div>
  </div>
</div>

@endsection
@section('scripts')
<script src="{{secure_asset('js/facturas.js?cod='.date('Yidisus'))}}"></script>

<script>
	var v_id="";
	var total=0.0;
	$(document).ready(function(e){

		obtenerprevias();
	});

	function obtenerprevias(){
		var cotizacion_id=$("#cotizacion_id").val();
		if(cotizacion_id>0){
			$.ajax({
			url:'../cotizaciones/previas/'+cotizacion_id,
			type:'get',
			dataType:'json',
			success: function(json){
					if(json[0]==1){
						$("#tabita>tbody").empty();
						$("#tabita>tbody").html(json[2]);
						$("#tabita>tfoot").html(json[3]);
						total=json[4];
						$("#txttotal").val(json[4]);
						$("#txtsubtotal").val(json[4]);
					}
				}
			});
		}else{
			$("#tabita>tbody").empty();
			$(".trr").text("$0.00");
			$(".thiva").text("$0.00");
			$(".thivar").text("$0.00");
			$(".thst").text("$0.00");
			$(".thtotal").text("$0.00");
		}
	}
</script>
@endsection