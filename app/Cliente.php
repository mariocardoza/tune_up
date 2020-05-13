<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $guarded = [];

    public function vehiculo()
    {
    	return $this->hasMany('App\Vehiculo','cliente_id')->where('estado',1);
    }

    public function cotizacion()
    {
    	return $this->hasMany('App\Cotizacione')->orderBy('tipo_documento','asc')->orderBy('created_at','desc');
    }

    public static function modal_editar($id)
    {
    	$html='';
    	$cliente=Cliente::find($id);
    	$html.='
		        	<div class="card-body">
		        		<div class="row">
		        			<div class="col-md-6">
		        				<div class="row">
		        					<div class="col-md-6">
		        						<div class="form-group">
				        					<label for="">Tipo de persona</label>
				        					<select name="tipo" id="" class="form-control">
				        						<option selected value="">Seleccione..</option>
				        						<option value="1">Natural</option>
				        						<option value="2">Jurídica</option>
				        					</select>
				        				</div>
				        				<div class="form-group">
				        					<label for="">Nombre</label>
				        					<input type="text" name="nombre" value="'.$cliente->nombre.'" autocomplete="off" class="form-control">
				        					<input type="hidden" id="idcl" value="'.$cliente->id.'">
				        				</div>
				        				<div class="form-group">
				        					<label for="">NIT</label>
				        					<input type="text" name="nit" value="'.$cliente->nit.'" autocomplete="off" class="form-control nit">
				        				</div>
				        				<div class="form-group">
				        					<label for="">E-mail</label>
				        					<input type="email" value="'.$cliente->correo.'" name="correo" autocomplete="off" class="form-control">
				        				</div>
		        					</div>
		        					<div class="col-md-6">
		        						<div class="form-group">
				        					<label for="">Sector</label>
				        					<select name="sector" id="elsector" class="form-control">';
				        						if($cliente->sector=='Contribuyentes'):
				        						$html.='<option selected value="Contribuyentes">Contribuyentes</option>
				        							<option value="Gran Contribuyente">Gran contribuyente</option>
				        							<option value="No Contribuyente">No contribuyente</option>';
				        						elseif($cliente->sector=='Gran Contribuyente'):
				        						$html.='<option  value="Contribuyentes">Contribuyentes</option>
				        							<option selected value="Gran Contribuyente">Gran contribuyente</option>
				        							<option value="No Contribuyente">No contribuyente</option>';
				        						elseif($cliente->sector=='No Contribuyente'):
				        						$html.='<option  value="Contribuyentes">Contribuyentes</option>
				        							<option value="Gran Contribuyente">Gran contribuyente</option>
				        							<option selected value="No Contribuyente">No contribuyente</option>';
				        						endif;
				        						
				        						
				        					$html.='</select>
				        				</div>
				        				';
				        				if($cliente->sector!='No Contribuyente'):
				        				$html.='<div class="contri" style="display: block;">
				        					<div class="form-group">
				        						<label for="">Registro de IVA</label>
				        						<input type="text" value="'.$cliente->reg_iva.'" name="reg_iva" class="form-control">
				        					</div>
				        					<div class="form-group">
				        						<label for="">Giro</label>
				        						<input type="text" name="giro" value="'.$cliente->giro.'" class="form-control">
				        					</div>
				        					<div class="form-group">
				        						<label for="">Contacto</label>
				        						<input type="text" name="nombre_contacto" value="'.$cliente->nombre_contacto.'" class="form-control">
				        					</div>		
				        				</div>';
				        			endif;
		        					$html.='</div>

		        				</div>
		        			</div>

		        			<div class="col-md-6">';
		        			if($cliente->sector=='No Contribuyente'):
		        				$html.='<div class="row nocontri">
		        					<div class="col-md-6">
		        						<div class="from-group">
		        							<label for="fecha_nacimiento">Fecha de nacimiento</label>
		        							<input type="text" name="fecha_nacimiento" class="form-control fecha">
		        						</div>
		        					</div>
		        					<div class="col-md-6">
		        						<div class="form-group">
		        							<label for="dui">DUI</label>
		        							<input type="text" name="dui" value="'.$cliente->dui.'" class="form-control dui">
		        						</div>
		        					</div>
		        				</div>';
		        			endif;
		        				$html.='<div class="row">
		        					<div class="col-md-4">
		        						<div class="form-group">
		        							<label for="telefono">Telefono oficina</label>
		        							<input type="text" name="telefono" value="'.$cliente->telefono.'" class="form-control telefono">
		        						</div>
		        					</div>
		        					<div class="col-md-4">
		        						<div class="form-group">
		        							<label for="telefono2">Telefono personal</label>
		        							<input type="text" name="telefono2" value="'.$cliente->telefono2.'" class="form-control telefono">
		        						</div>
		        					</div>
		        					<div class="col-md-4">
		        						<div class="form-group">
		        							<label for="fax">FAX</label>
		        							<input type="text" name="fax" value="'.$cliente->dui.'" class="form-control telefono">
		        						</div>
		        					</div>
		        				</div>
		        				<div class="row">
		        					<div class="col-md-12">
		        						<div class="form-group">
		        							<label for="direccion">Dirección</label>
		        							<textarea name="direccion" rows="3" class="form-control">'.$cliente->direccion.'</textarea>
		        						</div>
		        					</div>
		        				</div>
		        			</div>
		        		</div>
		        	</div>
		     ';

		return array(1,"exito",$html);
    }
}
