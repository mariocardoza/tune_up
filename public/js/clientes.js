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

	$(document).on("blur",".laplaca",function(e){
		e.preventDefault();
		var placa=$(this).val();
		$.ajax({
          	url:'../vehiculos/porplaca',
          	type:'get',
          	dataType:'json',
          	data:{placa},
          	success: function(json){
          		console.log(json);
          		if(json[1]==null || json[0].length == 0){
          				
          		}else{	
          			swal.fire('Aviso','La placa ya existe','warning');
          		}
          	},error: function(error){
          	}
          });

	});

	$(document).on("change","#marca_ide",function(e){
		e.preventDefault();
		var id=$(this).val();
		$.ajax({
			url:'../clientes/obtenermodelos/'+id,
			type:'GET',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#modelo_ide").empty();
					$("#modelo_ide").html(json[2]);
					$(".chosen-select").chosen({'width':'100%'});
					$(".chosen-select").trigger('chosen:updated');
				}
			}
		});
	});

	//editar vehiculo
	$(document).on("click","#edit_veh",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		modal_cargando();
		$.ajax({
			url:'../vehiculos/'+id+'/edit',
			type:'GET',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$(".elidv").val(json[2].id);
					$(".placa").val(json[2].placa);
					$(".anio").val(json[2].anio);
					$(".motor").val(json[2].motor);
					$(".vin").val(json[2].vin);
					$(".notas").val(json[2].notas);
					$("#marca_ide").val(json[2].marca_id);
					$("#tipomedida").val(json[2].tipomedida);
					$("#tipomedida").trigger('chosen:updated');
					$("#marca_ide").trigger('chosen:updated');
					$("#marca_ide").trigger("change");
					setTimeout(() => {
        				$("#modelo_ide").val(json[2].modelo_id);
						$("#modelo_ide").trigger('chosen:updated');
						$("#modal_edit_v").modal("show");
						swal.closeModal();
    				}, 1000);
					
				}else{
					toastr.error("Ocurrió un error, Intente de nuevo");
					swal.closeModal();
				}
			}, error : function(error){
				toastr.error("Ocurrió un error, Intente de nuevo");
					swal.closeModal();
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
				}else{
					toastr.error("Ocurrió un error, Intente otra vez");
					swal.closeModal();
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

	//submit editar vehiculo
		$(document).on("submit","#form_evehiculo",function(e){
		e.preventDefault();
		var datos=$("#form_evehiculo").serialize();
		var id=$(".elidv").val();
		modal_cargando();
		$.ajax({
			url:'../vehiculos/'+id,
			type:'put',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					toastr.success("Vehiculo editado con éxito");
					location.reload();
				}
				else{
					toastr.error("Ocurrió un error, Intente otra vez");
					swal.closeModal();
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
 

	//modal para guardar nueva marca
	$(document).on("click","#btn_modal_marca",function(e){
		e.preventDefault();
		$("#modal_nuevo_v").modal("hide");
		$("#modal_marca").modal("show");
	});

	$(document).on("click","#btn_modal_marcae",function(e){
		e.preventDefault();
		$("#modal_edit_v").modal("hide");
		$("#modal_marca_e").modal("show");
	});
	//cerrar el modal
	$(document).on("click","#cerrar_modal_marca",function(e){
		e.preventDefault();
		$("#modal_nuevo_v").modal("show");
		$("#modal_marca").modal("hide");
	});
	$(document).on("click","#cerrar_modal_marcae",function(e){
		e.preventDefault();
		$("#modal_edit_v").modal("show");
		$("#modal_marca_e").modal("hide");
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

	$(document).on("click","#btn_modal_modeloe",function(e){
		e.preventDefault();
		var id_marca=$("#marca_ide").val();
		if(id_marca!=''){
			var marca=$("#marca_ide option:selected").text();
			console.log(id_marca,marca);
			$("#n_marcae").val(marca);
			$("#id_marcae").val(id_marca);
			$("#modal_edit_v").modal("hide");
			$("#modal_modeloe").modal("show");
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

	//cerrar el modal
	$(document).on("click","#cerrar_modal_modeloe",function(e){
		e.preventDefault();

		$("#modal_edit_v").modal("show");
		$("#modal_modeloe").modal("hide");
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

	$(document).on("submit","#form_marcae",function(e){
		e.preventDefault();
		var datos=$("#form_marcae").serialize();
		$.ajax({
			url:'../marcas',
			type:'post',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					$("#marca_ide").append("<option selected value='"+json[2].id+"'>"+json[2].marca+"</option>");
					$("#marca_ide").trigger("chosen:updated");
					toastr.success("Marca registrada con éxito");
					$("#modal_edit_v").modal("show");
					$("#modal_marcae").modal("hide");
					$("#form_marcae").trigger("reset");
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

	$(document).on("submit","#form_modeloe",function(e){
		e.preventDefault();
		var datos=$("#form_modeloe").serialize();
		$.ajax({
			url:'../modelos',
			type:'post',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					$("#modelo_ide").append("<option selected value='"+json[2].id+"'>"+json[2].nombre+"</option>");
					$("#modelo_ide").trigger("chosen:updated");
					toastr.success("Modelo registrado con éxito");
					$("#modal_edit_v").modal("show");
					$("#modal_modeloe").modal("hide");
					$("#form_modeloe").trigger("reset");
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

	//quitar cliente
	$(document).on("click","#eliminar_cliente",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		swal.fire({
		  title: '¿Está seguro?',
		  text: "El cliente de eliminará",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si'
		}).then((result) => {
		  if (result.value) {
		    $.ajax({
		    	url:'../clientes/'+id,
		    	type:'delete',
		    	dataType:'json',
		    	success: function(json){
		    		if(json[0]==1){
		    			toastr.success("Cliente eliminado con éxito");
		    			location.href='../clientes';
		    		}
		    	},error: function(error){
		    		toastr.error("Ocurrió un error");
		    	}
		    });
		  }
		});
	});
});