<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepuestoPrevia extends Model
{
    protected $guarded = [];

    public function repuesto()
    {
    	return $this->belongsTo('App\Repuesto');
    }

    public static function modal_edit($id){
    	$repuesto=RepuestoDetalle::find($id);
    	$html='';
    	$html.='<div class="modal fade" id="modal_repuesto_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header text-center">
			        <h5 class="modal-title " id="exampleModalLabel">Editar el repuestos
			        </h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <form id="form_repuesto_edit" role="form">
			          <div class="card-body">
			            <div class="row">
			              <div class="col-md-12">
			                <div class="row">
			                  <div class="col-md-8">
			                    <div class="form-group">
			                      <label for="">Nombre del repuesto</label>
			                      
			                      <input type="text" name="nombre" autocomplete="off" value="'.$repuesto->nombre.'" class="form-control">
			                    </div>
			                  </div>
			                  <div class="col-md-4">
			                    <div class="form-group">
			                      <label for="">Código</label>
			                      <input type="text" readonly value="'.$repuesto->repuesto->codigo.'" placeholder="" class="form-control">
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
			              						<input type="number" name="precio" value="'.$repuesto->precio.'" class="form-control e_precio_r">
			              					</div>
			              				</div>
			              				<div class="col-md-6">
			              					<div class="form-group">
			              						<label for="" class="control-label">Cantidad (*)</label>
			              						<input type="number" value="'.$repuesto->cantidad.'" name="cantidad" value="1" class="form-control e_cantidad_r">
			              					</div>
			              				</div>
			              			</div>
			              		</div>
			              		<div class="col-md-4">
			              			<div class="form-group">
			      						<label for="" class="control-label">Subtotal (*)</label>
			      						<input type="number" value="'.$repuesto->precio*$repuesto->cantidad.'" readonly class="form-control e_subto_r">
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
			        	<button type="button" id="edit_repuesto_previa" data-id="'.$repuesto->id.'" class="btn btn-success submitrepuesto">Actualizar</button>
			    	</div>
			      </div>
			      </form>
			    </div>
			  </div>
			</div>';
		return array(1,"exito",$html);
    }
}
