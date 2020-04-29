$(document).ready(function(e){
	//modal para registrar un vehiculo
	
	$(document).on("click","#nuevo_vehiculo",function(e){
		e.preventDefault();
		$("#modal_nuevo_v").modal("show");
	});

	//select de las marcas
	$(document).on("change","#marca_id",function(e){
		e.preventDefault();
		var id=$(this).val();
		$.ajax({
			url:'../clientes/obtenermodelos/'+id,
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

	//edit cliente
	$(document).on("click","#edit_cliente",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		$.ajax({
			url:'../clientes/'+id+'/edit',
			type:'get',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#cuerpoaqui").empty();
					$("#cuerpoaqui").html(json[2]);
					$("#modal_editar").modal("show");
				}
			}
		})
	});

	//editar el cliente
	$(document).on("submit","#form_ecliente",function(e){
		e.preventDefault();
		var datos=$("#form_ecliente").serialize();
		var id=$("#idcl").val();
		$.ajax({
			url:'../clientes/'+id,
			type:'put',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					toastr.success("cliente fue editado con éxito");
					location.reload();
				}
			},
			error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		});
	});

	//guardar el vehiculo
	$(document).on("submit","#form_vehiculo",function(e){
		e.preventDefault();
		var datos=$("#form_vehiculo").serialize();
		$.ajax({
			url:'../vehiculos',
			type:'POST',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					toastr.success("Vehiculo registrado con éxito");
					location.reload();
				}
			},
			error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		});
	});

	//modal para guardar nueva marca
	$(document).on("click","#btn_modal_marca",function(e){
		e.preventDefault();
		$("#modal_nuevo_v").modal("hide");
		$("#modal_marca").modal("show");
	});
	//cerrar el modal
	$(document).on("click","#cerrar_modal_marca",function(e){
		e.preventDefault();
		$("#modal_nuevo_v").modal("show");
		$("#modal_marca").modal("hide");
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
	//cerrar el modal
	$(document).on("click","#cerrar_modal_modelo",function(e){
		e.preventDefault();

		$("#modal_nuevo_v").modal("show");
		$("#modal_modelo").modal("hide");
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

	//submit para el form modelo
	$(document).on("submit","#form_modelo",function(e){
		e.preventDefault();
		var datos=$("#form_modelo").serialize();
		$.ajax({
			url:'../modelos',
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
		    	url:'../vehiculos/'+id,
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