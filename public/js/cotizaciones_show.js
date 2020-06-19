$(document).ready(function(e){
	//evento change para obtener los clientes
	$(document).on("change","#cliente_id",function(e){
		e.preventDefault();
		var id=$(this).val();
		var sector=$('option:selected',this).attr("data-sector");
		var direccion=$("option:selected",this).attr("data-direccion");
		$(".sector").val(sector);
		$(".direcc").val(direccion);
		obtenervehiculos(id,v_id);
	});

	//eventro change para select de vehiculos
	$(document).on("change","#vehiculo_id",function(e){
		e.preventDefault();
		var id=$(this).val();
		console.log(id);
		$.ajax({
			url:'../vehiculos/info/'+id,
			type:'get',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#datos_carro").empty();
					$("#datos_carro").html(json[2]);
					
				}
			}
		});
	});

	//eventro change para el trabajo
	$(document).on("change","#elselect_t",function(e){
		e.preventDefault();
		var id=$(this).val();
		var codigo=$('option:selected',this).attr("data-codigo");
		var precio=$('option:selected',this).attr("data-precio");

		$("#n_precio_t").val(precio);
		$(".codi").val(codigo);

		$(".n_precio_t").trigger("input");
		console.log(id);
		
	});

	//eventro change para el repuesto
	$(document).on("change","#elselect_r",function(e){
		e.preventDefault();
		var id=$(this).val();
		var codigo=$('option:selected',this).attr("data-codigo");
		var precio=$('option:selected',this).attr("data-precio");

		$("#n_precio_r").val(precio);
		$(".codir").val(codigo);

		$(".n_precio_r").trigger("input");
		console.log(id);
		
	});

	//modal para registrar o agregar un repuesto
	$(document).on("click","#md_repuestos",function(e){
		e.preventDefault();
		$("#modal_repuesto").modal("show");
	});
	//modal para registrar o agregar una mano de obra o trabajo
	$(document).on("click","#md_trabajos",function(e){
		e.preventDefault();
		$("#modal_trabajo").modal("show");
	});

	//boton para registrar una nueva mano de obra o trabajo
	$(document).on("click","#btn_nuevotrabajo",function(e){
		e.preventDefault();
		$("#btn_nuevotrabajo").hide();
		$("#btn_volvertrabajos").show();
		$(".submittrabajo").show();
		$("#btn_agregar_trabajo").hide();
		$("#form_trabajo").show();
		$("#existente").hide();
	});

	//regresar o cancelar registrar nuevo trabajo o mano de obra
	$(document).on("click","#btn_volvertrabajos",function(e){
		e.preventDefault();
		$("#btn_nuevotrabajo").show();
		$("#btn_volvertrabajos").hide();
		$(".submittrabajo").hide();
		$("#btn_agregar_trabajo").show();
		$("#form_trabajo").hide();
		$("#existente").show();
	});

	//boton para registrar un nuevo repuesto
	$(document).on("click","#btn_nuevorepuesto",function(e){
		e.preventDefault();
		$("#btn_nuevorepuesto").hide();
		$("#btn_volverrepuestos").show();
		$(".submitrepuesto").show();
		$("#btn_agregar_repuesto").hide();
		$("#form_repuesto").show();
		$("#losrepuestos").hide();
		$(".n_cantidad_r").val(1);
		$(".n_precio_r").val("");
		$(".n_subto_r").val("");
	});

	//regresar o cancelar registrar nuevo trabajo o mano de obra
	$(document).on("click","#btn_volverrepuestos",function(e){
		e.preventDefault();
		$("#btn_nuevorepuesto").show();
		$("#btn_volverrepuestos").hide();
		$(".submitrepuesto").hide();
		$("#btn_agregar_repuesto").show();
		$("#form_repuesto").hide();
		$("#losrepuestos").show();
		$(".cantidad_r").val(1);
		$(".precio_r").val("");
		$(".subto_r").val("");
	});

	//submit de los repuestos
	$(document).on("submit","#form_repuesto",function(e){
		e.preventDefault();
		var datos=$("#form_repuesto").serialize();
		$.ajax({
			url:'../repuestos/guardar',
			type:'POST',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo aplicado con éxito");
					$("#form_repuesto").trigger("reset");
					$( "#btn_volverrepuestos" ).trigger( "click" );
					obtenerguardados(elid);
				}else{
					toastr.error("Ocurrió un error");
				}
			},
			error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		});
	});

	//submit de los trabajos o la mano de obra
	$(document).on("submit","#form_trabajo",function(e){
		e.preventDefault();
		var datos=$("#form_trabajo").serialize();
		$.ajax({
			url:'../trabajos/guardar',
			type:'POST',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo aplicado con éxito");
					$("#form_trabajo").trigger("reset");
					$( "#btn_volvertrabajos" ).trigger( "click" );
					obtenerguardados(elid);
				}else{
					toastr.error("Ocurrió un error");
				}
			},
			error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		});
	});

	//submit para editar un trabajo previo
	$(document).on("click","#edit_trabajo_previa",function(e){
		e.preventDefault();
		var id=$("#id_trabajo_previa").val();
		var datos=$("#form_trabajo_edit").serialize();
		$.ajax({
			url:'../trabajodetalles2/'+id,
			type:'put',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo editado con éxito");
					$("#form_trabajo_edit").trigger("reset");
					$("#modal_trabajo_edit").modal("hide");
					obtenerguardados(elid);
				}else{
					toastr.error("Ocurrió un error");
				}
			},
			error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		});
	});

	//submit para el formulario
	$(document).on("submit","#form_coti",function(e){
		e.preventDefault();

		var datos=$("#form_coti").serialize();
		$.ajax({
			url:'../cotizaciones',
			type:'post',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){

				}
			}
		});
	});

	//agregar un trabajo
	$(document).on("click","#btn_agregar_trabajo",function(e){
		var trabajo_id=$("#elselect_t").val();
		var precio=$("#n_precio_t").val();
		var cantidad=1;
		$.ajax({
			url:'../trabajodetalles/guardar',
			type:'POST',
			dataType:'json',
			data:{trabajo_id,precio,cantidad,cotizacion_id:elid},
			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo aplicado con éxito");
					$( "#btn_volvertrabajos" ).trigger( "click" );
					$("#elselect_t").trigger("chosen:updated");
					$("#n_precio_t").val("");
					$(".n_subto_t").val("");
					obtenerguardados(elid);
				}else{
					toastr.error("Ocurrió un error");
				}
			},error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		})
	});

	//agregar un repuesto
	$(document).on("click","#btn_agregar_repuesto",function(e){
		var repuesto_id=$("#elselect_r").val();
		var precio=$("#n_precio_r").val();
		var cantidad=$("#n_cantidad_r").val();
		$.ajax({
			url:'../repuestodetalles/guardar',
			type:'POST',
			dataType:'json',
			data:{repuesto_id,precio,cantidad,cotizacion_id:elid},
			success: function(json){
				if(json[0]==1){
					toastr.success("Repuesto aplicado con éxito");
					$( "#btn_volverrepuestos" ).trigger( "click" );
					obtenerguardados(elid);
					$("#elselect_r").trigger("chosen:updated");
					$("#n_precio_r").val("");
					$("#n_precio_r").val("");
					$(".n_subto_r").val("");
				}else{
					toastr.error("Ocurrió un error");
				}
			},error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		});
	});

	//submit para el repuesto edit previa
	$(document).on("click","#edit_repuesto_previa",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		var datos=$("#form_repuesto_edit").serialize();
		$.ajax({
			url:'../repuestodetalles2/'+id,
			type:'put',
			dataType:'json',
			data:datos,
			success: function(json){
				if(json[0]==1){
					toastr.success("Repuesto editado con éxito");
					$("#form_repuesto_edit").trigger("reset");
					$("#modal_repuesto_edit").modal("hide");
					obtenerguardados(elid);
				}else{
					toastr.error("Ocurrió un error");
				}
			},
			error: function(error){
				$.each(error.responseJSON.errors,function(index,value){
	      			toastr.error(value);
	      		});
			}
		});
	});

	//modal editar trabajo previo
	$(document).on("click","#editar_trabajo",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		$.ajax({
			url:'../trabajodetalles/'+id+'/edit2',
			type:'get',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#modal_aqui").empty();
					$("#modal_aqui").html(json[2]);
					$("#modal_trabajo_edit").modal("show");
				}
			}
		});
	});

	// modal editar repuesto previo
	$(document).on("click","#editar_repuesto",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		$.ajax({
			url:'../repuestodetalles/'+id+'/edit2',
			type:'get',
			dataType:'json',
			success: function(json){
				if(json[0]==1){
					$("#modal_aqui").empty();
					$("#modal_aqui").html(json[2]);
					$("#modal_repuesto_edit").modal("show");
				}
			}
		});
	});

	//eliminar un repuesto
	$(document).on("click","#eliminar_repuesto",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		swal.fire({
		  title: '¿Eliminar?',
		  text: "¿Está seguro de eliminar el repuesto de la cotización?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si'
		}).then((result) => {
		  if (result.value) {
		    modal_cargando();
		    $.ajax({
		    	url:'../repuestodetalles/destroy2/'+id,
		    	type:'DELETE',
		    	dataType:'json',
		    	data:{'cotizacion_id':elid},
		    	success: function(json){
		    		if(json[0]==1){
		    			toastr.success("Repuesto eliminado con éxito");
		    			swal.closeModal();
		    			location.reload();
		    		}else{
		    			toastr.error("Ocurrió un error");
		    			swal.closeModal();
		    		}
		    	}
		    });
		  }
		});
		
	});

	//eliminar un trabajo
	$(document).on("click","#eliminar_trabajo",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		swal.fire({
		  title: '¿Eliminar?',
		  text: "¿Está seguro de eliminar el trabajo de la cotización?",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si'
		}).then((result) => {
		  if (result.value) {
		    modal_cargando();
		    $.ajax({
		    	url:'../trabajodetalles/destroy2/'+id,
		    	type:'DELETE',
		    	dataType:'json',
		    	data:{'cotizacion_id':elid},
		    	success: function(json){
		    		if(json[0]==1){
		    			toastr.success("Trabajo eliminado con éxito");
		    			swal.closeModal();
		    			location.reload();
		    		}else{
		    			toastr.error("Ocurrió un error");
		    			swal.closeModal();
		    		}
		    	}
		    });
		  }
		});
	});

	//anterior
	$(document).on("click","#anterior",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		if(id>0){
			location.href='../cotizaciones/'+id;
		}
	});

	//siguiente
	$(document).on("click","#siguiente",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		if(id>0){
			location.href='../cotizaciones/'+id;
		}
	});

	//ultima
	$(document).on("click","#ultima",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		if(id>0){
			location.href='../cotizaciones/'+id;
		}
	});

	//primera
	$(document).on("click","#primera",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		if(id>0){
			location.href='../cotizaciones/'+id;
		}
	});



	//actualizar el campo subtotal para repuestos
	$(document).on("input",".n_precio_r,.n_cantidad_r",function(e){
		e.preventDefault();
		var precio=parseFloat($(".n_precio_r").val());
		var cantidad=parseInt($(".n_cantidad_r").val());
		var subto=precio*cantidad;
		$(".n_subto_r").val(subto);
	});

	$(document).on("input",".n_precio_rr,.n_cantidad_rr",function(e){
		e.preventDefault();
		var precio=parseFloat($(".n_precio_rr").val());
		var cantidad=parseInt($(".n_cantidad_rr").val());
		var subto=precio*cantidad;
		$(".n_subto_rr").val(subto);
	});

	$(document).on("input",".n_precio_t",function(e){
		e.preventDefault();
		var precio=parseFloat($(".n_precio_t").val());
		var subto=precio;
		$(".n_subto_t").val(subto);
	});

	$(document).on("input",".n_precio_tr",function(e){
		e.preventDefault();
		var precio=parseFloat($(".n_precio_tr").val());
		var subto=precio;
		$(".n_subto_tr").val(subto);
	});

	$(document).on("input",".e_precio_t",function(e){
		e.preventDefault();
		var precio=parseFloat($(".e_precio_t").val());
		var subto=precio;
		$(".e_subto_t").val(subto);
	});

	$(document).on("input",".e_precio_r,.e_cantidad_r",function(e){
		e.preventDefault();
		var precio=parseFloat($(".e_precio_r").val());
		var cantidad=parseInt($(".e_cantidad_r").val());
		var subto=precio*cantidad;
		$(".e_subto_r").val(subto);
	});

	$(document).on("input",".precio_r,.cantidad_r",function(e){
		e.preventDefault();
		var precio=parseFloat($(".precio_r").val());
		var cantidad=parseInt($(".cantidad_r").val());
		var subto=precio*cantidad;
		$(".subto_r").val(subto);
	});

	//submit de kilometraje
	$(document).on("blur",".kimi",function(e){
		e.preventDefault();
		var kilometraje=0;var km_proxima=0;
		kilometraje=$(this).val();
		km_proxima=$(".kimiproxi").val();
		$.ajax({
			url:'../cotizaciones/cambiarkm',
			type:'post',
			dataType:'json',
			data:{kilometraje,km_proxima,cotizacion_id:elid},
			success: function(json){
				if(json[0]==1){
					console.log("kilometraje cambiado");
				}else{
					console.log("no se cambio el kilometraje");
				}
			}
		});
	});

	//submit de millaje
	$(document).on("blur",".millaje",function(e){
		e.preventDefault();
		var kilometraje=0;var km_proxima=0;
		kilometraje=$(this).val();
		km_proxima=$(".miproxi").val();
		$.ajax({
			url:'../cotizaciones/cambiarkm',
			type:'post',
			dataType:'json',
			data:{kilometraje,km_proxima,cotizacion_id:elid},
			success: function(json){
				if(json[0]==1){
					console.log("kilometraje cambiado");
				}else{
					console.log("no se cambio el kilometraje");
				}
			}
		});
	});

	//calcular kilometraje
	$(document).on("input",".kimi",function(e){
		e.preventDefault();
		var actual=parseFloat($(this).val());
		var proximo=actual+5000;
		$(".kimiproxi").val(proximo.toFixed(2));
	});

	//calcular millaje
	$(document).on("input",".millaje",function(e){
		e.preventDefault();
		var actual=parseFloat($(this).val());
		var proximo=actual+3106.856;
		$(".miproxi").val(proximo.toFixed(2));
	});

});

function obtenervehiculos(id,actual=""){
	$.ajax({
		url:'../cotizaciones/vehiculos/'+id,
		type:'get',
		dataType:'json',
		data:{actual},
		success: function(json){
			if(json[0]==1){
				$("#vehiculo_id").empty();
				$("#vehiculo_id").html(json[2]);
				$("#vehiculo_id").chosen({'width':'100%'});
				$("#vehiculo_id").trigger("chosen:updated");
			}
		}
	});
}