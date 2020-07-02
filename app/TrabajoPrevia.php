<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrabajoPrevia extends Model
{
    protected $guarded = [];

    public function trabajo()
    {
    	return $this->belongsTo('App\Trabajo');
    }

    public static function modal_edit($id){
    	$trabajo=TrabajoDetalle::find($id);
    	$html='';
    	$html.='<div class="modal fade" id="modal_trabajo_edit" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header text-center">
			        <h5 class="modal-title " id="exampleModalLabel">Editar el trabajos 
			        </h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <form id="form_trabajo_edit" role="form">
			        	<input id="id_trabajo_previa" hidden value="'.$trabajo->id.'">
				        <div class="card-body">
					            <div class="row">
					              <div class="col-md-12">
					                <div class="row">
					                  <div class="col-md-8">
					                    <div class="form-group">
					                      <label for="">Nombre de la mano de obra</label>
					                      <input type="text" name="nombre" autocomplete="off" value="'.$trabajo->nombre.'" class="form-control">
					                    </div>
					                  </div>
					                  <div class="col-md-4">
					                    <div class="form-group">
					                      <label for="">Código</label>
					                      <input type="text" value="'.$trabajo->trabajo->codigo.'" readonly placeholder="" class="form-control">
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
					              						<input type="number" value="'.$trabajo->precio.'" name="precio" class="form-control">
					              					</div>
					              				</div>
					              			</div>
					              		</div>
					              		<div class="col-md-4">
					              			<div class="form-group">
					      						<label for="" class="control-label">Subtotal (*)</label>
					      						<input type="number" value="'.$trabajo->precio.'" readonly class="form-control">
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
			        	<button type="button" id="edit_trabajo_previa" class="btn btn-success ">Actualizar</button>
			    	</div>
			      </div>
			      </form>
			    </div>
			  </div>
			</div>';
		return array(1,"exito",$html);
    }
}
