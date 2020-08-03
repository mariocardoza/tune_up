$(document).ready(function(e){
	var eliva=false;
	var iva=0.0;
	var totaltotal=0.0;
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
					$(".kilometrajes").empty();
					$(".kilometrajes").html(json[4]);
					$(".kilometraje").val("")
						//var proxi=json[3]+5000;
					$(".kmproxi").val("");
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

	//evento change para el iva
	$(document).on("change","#eliva",function(e){
		e.preventDefault();
		var sel=$(this).val();
		if(sel=='si'){
			eliva=true;
			iva=iva+(total*0.13);
			totaltotal=total+iva;
			$(".thiva").text("$"+iva.toFixed(2));
			$(".thtotal").text("$"+totaltotal.toFixed(2));
			$("#txtiva").val(iva);
			$("#txttotal").val(totaltotal);
			$("#txtsubtotal").val(total);
			console.log(total);
		}else{
			eliva=false;
			iva=0;
			totaltotal=total;
			$(".thiva").text("$"+iva.toFixed(2));
			$(".thtotal").text("$"+totaltotal.toFixed(2));
			$("#txtiva").val(iva);
			$("#txttotal").val(totaltotal);
			$("#txtsubtotal").val(total);
			console.log(iva);
		}
		
	});

	//eventro change para el repuesto
	$(document).on("change","#elselect_r",function(e){
		e.preventDefault();
		var id=$(this).val();
		var codigo=$('option:selected',this).attr("data-codigo");
		var precio=$('option:selected',this).attr("data-precio");

		$("#n_precio_r").val(precio);
		$(".codir").val(codigo);

		$(".precio_r").trigger("input");
		console.log(id);
		
	});

	//modal para registrar o agregar un repuesto
	$(document).on("click","#md_repuestos",function(e){
		e.preventDefault();
		$("#n_cantidad_r").val(1);
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
		var cotizacion_id=$("#cotizacion_id").val();
		var cliente_id=$('#cliente_id').val();
		var vehiculo_id=$('#vehiculo_id').val();
		var fecha=$(".fecha").val();
		var kilometraje=$(".kilometraje").val();
		var km_proxima=$(".kmproxi").val();
		var coniva=$("#eliva").val();
		modal_cargando();
		$.ajax({
			url:'../repuestos',
			type:'POST',
			dataType:'json',
			data:datos+'&cotizacion_id='+cotizacion_id+'&vehiculo_id='+vehiculo_id+'&cliente_id='+cliente_id+'&fecha='+
			fecha+'&kilometraje='+kilometraje+'&km_proxima='+km_proxima+'&tipo_documento=4&imprimir_veh=si&coniva='+coniva,
			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo aplicado con éxito");
					$("#form_repuesto").trigger("reset");
					$( "#btn_volverrepuestos" ).trigger( "click" );
					$("#cotizacion_id").val(json[2]);
					obtenerprevias();
					swal.closeModal();
				}else{
					if(json[0]==2){
						toastr.info(json[1]);
						swal.closeModal();
					}else{
						toastr.error("Ocurrió un error");
						swal.closeModal();
					}
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

	//submit de los trabajos o la mano de obra
	$(document).on("submit","#form_trabajo",function(e){
		e.preventDefault();
		var nombre=$(".nont").val();
		var codigo=$(".codt").val();
		var precio=$(".n_precio_tr").val();
		var cantidad=1;
		var cliente_id=$('#cliente_id').val();
		var vehiculo_id=$('#vehiculo_id').val();
		var fecha=$(".fecha").val();
		var kilometraje=$(".kilometraje").val();
		var km_proxima=$(".kmproxi").val();
		var cotizacion_id=$("#cotizacion_id").val();
		var coniva=$("#eliva").val();
		modal_cargando();
		$.ajax({
			url:'../trabajos',
			type:'POST',
			dataType:'json',
			data:{nombre,precio,cantidad,cliente_id,imprimir_veh:'si',vehiculo_id,fecha,kilometraje,km_proxima,coniva,tipo_documento:4,cotizacion_id},

			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo aplicado con éxito");
					$("#form_trabajo").trigger("reset");
					$( "#btn_volvertrabajos" ).trigger( "click" );
					$("#cotizacion_id").val(json[2]);
					obtenerprevias();
					swal.closeModal();
				}else{
					swal.closeModal();
					if(json[0]==2){
						toastr.info(json[1]);
					}else{
						toastr.error("Ocurrió un error");
					}
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

	//submit para editar un trabajo previo
	$(document).on("click","#edit_trabajo_previa",function(e){
		e.preventDefault();
		var id=$("#id_trabajo_previa").val();
		var datos=$("#form_trabajo_edit").serialize();
		var cotizacion_id=$("#cotizacion_id").val();
		modal_cargando();
		$.ajax({
			url:'../trabajodetalles/'+id,
			type:'put',
			dataType:'json',
			data:datos+'&cotizacion_id='+cotizacion_id,
			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo editado con éxito");
					$("#form_trabajo_edit").trigger("reset");
					$("#modal_trabajo_edit").modal("hide");
					obtenerprevias();
					swal.closeModal();
				}else{
					toastr.error("Ocurrió un error");
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

	//submit para el formulario
	$(document).on("submit","#form_coti",function(e){
		e.preventDefault();
		/*var html='<button class="btn btn-primary btn-lg siiva">Si</button>'+
		'&nbsp;<button class="btn btn-danger btn-lg noiva">No</button>'+
		'&nbsp;<button class="btn btn-secondary btn-lg canceliva">Cancelar</button>';
		swal.fire({
		  title: '¿Imprimir con IVA?', 
		  html: html,
		  showConfirmButton: false
		});	*/
		var cotizacion=$("#cotizacion_id").val();
		if(cotizacion>0){
			toastr.success("Exportacion registrada con éxito");
			location.href=cotizacion;
		}else{
			toastr.error("No se han agregado items a la factura");
		}
	});

	//guardar con iva
	$(document).on("click",".siiva",function(e){
		e.preventDefault();
		var tipo_documento=$("#eltipocoti").val();
		var datos=$("#form_coti").serialize();
		$.ajax({
			url:'../cotizaciones',
			type:'post',
			dataType:'json',
			data:datos+'&eliva=si&tipo_documento='+tipo_documento,
			success: function(json){
				if(json[0]==1){
					toastr.success("cotización registrada con éxito");
					location.href='../cotizaciones/'+json[2];
				}
			}
		});
	});

	//guardar sin iva
	$(document).on("click",".noiva",function(e){
		e.preventDefault();
		var tipo_documento=$("#eltipocoti").val();
		var datos=$("#form_coti").serialize();
		$.ajax({
			url:'../cotizaciones',
			type:'post',
			dataType:'json',
			data:datos+'&eliva=no&tipo_documento='+tipo_documento,
			success: function(json){
				if(json[0]==1){
					toastr.success("cotización registrada con éxito");
					location.href='../cotizaciones/'+json[2];

				}
			}
		});
	});

	//guardar un carro
	$(document).on("click",".sicarro",function(e){
		e.preventDefault();
		location.href='../clientes';
	});

	//no guardar un carro
	$(document).on("click",".nocarro",function(e){
		e.preventDefault();
		swal.closeModal();
	});

	//buscar por placa
	$(document).on("click",".buscaplaca",function(e){
		e.preventDefault();
		$("#modal_placa").modal("show");
		$(".txtplaca").focus();
	});

	//enter al campo placa
	$(document).on("keypress",".txtplaca",function(e){
		if(e.which == 13) {
			modal_cargando();
			var placa=$(this).val();
          $.ajax({
          	url:'../vehiculos/porplaca',
          	type:'get',
          	dataType:'json',
          	data:{placa},
          	success: function(json){
          		console.log(json);
          		if(json[1]==null || json[0].length == 0){
          			swal.closeModal();
          			var html='<h5>¿Desea guardar el vehículo ahora?</h5>'+
          			'<button class="btn btn-primary btn-lg sicarro">Si</button>'+
					'&nbsp;<button class="btn btn-danger btn-lg nocarro">No</button>';
					swal.fire({
					  title: 'Placa no encontrada', 
					  html: html,
					  showConfirmButton: false
					});	
          		}else{
          			$("#cliente_id").val(json[2].id);
          			$("#cliente_id").trigger("chosen:updated");
          			$("#cliente_id").trigger( "change" );
          			setTimeout(function(){ 
          				$("#vehiculo_id").val(json[1].id);
	          			$("#vehiculo_id").trigger("chosen:updated");
	          			$("#vehiculo_id").trigger( "change" );
	          			swal.closeModal();
	          			$("#modal_placa").modal("hide");
						$(".txtplaca").val("");
          			 }, 5000);
          			
          		}
          	},error: function(error){
          		swal.closeModal();
          	}
          });
        }
		
	});

	//cancelar el iva
	$(document).on("click",".canceliva",function(e){
		e.preventDefault();
		swal.closeModal();
	});

	//agregar un trabajo
	$(document).on("click","#btn_agregar_trabajo",function(e){
		var trabajo_id=$("#elselect_t").val();
		var precio=$("#n_precio_t").val();
		var cantidad=1;
		var cliente_id=$('#cliente_id').val();
		var vehiculo_id=$('#vehiculo_id').val();
		var fecha=$(".fecha").val();
		var kilometraje=$(".kilometraje").val();
		var km_proxima=$(".kmproxi").val();
		var cotizacion_id=$("#cotizacion_id").val();
		var coniva=$("#eliva").val();
		modal_cargando();
		$.ajax({
			url:'../trabajodetalles',
			type:'POST',
			dataType:'json',
			data:{trabajo_id,precio,cantidad,imprimir_veh:'si',cliente_id,vehiculo_id,fecha,kilometraje,km_proxima,coniva,tipo_documento:4,cotizacion_id},
			success: function(json){
				if(json[0]==1){
					toastr.success("Trabajo aplicado con éxito");
					$( "#btn_volvertrabajos" ).trigger( "click" );
					$("#cotizacion_id").val(json[2]);
					obtenerprevias();
					swal.closeModal();
				}else{
					
					swal.closeModal();
					if(json[0]==2){
						toastr.info(json[1]);
					}else{
						toastr.error("Ocurrió un error");
					}
				}
			},
			error: function(error){
				swal.closeModal();
				$.each(error.responseJSON.errors, function(i,v){
					toastr.error(v);
				});
			}
		})
	});

	//agregar un repuesto
	$(document).on("click","#btn_agregar_repuesto",function(e){
		var repuesto_id=$("#elselect_r").val();
		var precio=$("#n_precio_r").val();
		var cantidad=$("#n_cantidad_r").val();
		var cliente_id=$('#cliente_id').val();
		var vehiculo_id=$('#vehiculo_id').val();
		var fecha=$(".fecha").val();
		var kilometraje=$(".kilometraje").val();
		var km_proxima=$(".kmproxi").val();
		var cotizacion_id=$("#cotizacion_id").val();
		var coniva=$("#eliva").val();
		modal_cargando();
		$.ajax({
			url:'../repuestodetalles',
			type:'POST',
			dataType:'json',
			data:{repuesto_id,precio,cantidad,imprimir_veh:'si',cliente_id,vehiculo_id,fecha,kilometraje,km_proxima,coniva,tipo_documento:4,cotizacion_id},
			success: function(json){
				if(json[0]==1){
					toastr.success("Repuesto aplicado con éxito");
					$( "#btn_volverrepuestos" ).trigger( "click" );
					$("#cotizacion_id").val(json[2]);
					obtenerprevias();
					$("#n_cantidad_r").val(1);
					swal.closeModal();
				}else{
					swal.closeModal();
					if(json[0]==2){
						toastr.info(json[1]);
					}else{
						toastr.error("Ocurrió un error");
					}
				}
			},
			error: function(error){
				swal.closeModal();
				$.each(error.responseJSON.errors, function(i,v){
					toastr.error(v);
				});
			}
		});
	});

	//submit para el repuesto edit previa
	$(document).on("click","#edit_repuesto_previa",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		var cotizacion_id=$("#cotizacion_id").val();
		var datos=$("#form_repuesto_edit").serialize();
		modal_cargando();
		$.ajax({
			url:'../repuestodetalles/'+id,
			type:'put',
			dataType:'json',
			data:datos+'&cotizacion_id='+cotizacion_id,
			success: function(json){
				if(json[0]==1){
					toastr.success("Repuesto editado con éxito");
					$("#form_repuesto_edit").trigger("reset");
					$("#modal_repuesto_edit").modal("hide");
					obtenerprevias();
					swal.closeModal();
				}else{
					toastr.error("Ocurrió un error");
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

	//modal editar trabajo previo
	$(document).on("click","#editar_trabajo",function(e){
		e.preventDefault();
		var id=$(this).attr("data-id");
		$.ajax({
			url:'../trabajodetalles/'+id+'/edit',
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
			url:'../repuestodetalles/'+id+'/edit',
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
		var cotizacion_id=$("#cotizacion_id").val();
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
		    	url:'../repuestodetalles/'+id,
		    	type:'DELETE',
		    	dataType:'json',
		    	data:{cotizacion_id},
		    	success: function(json){
		    		if(json[0]==1){
		    			toastr.success("Repuesto eliminado con éxito");
		    			swal.closeModal();
		    			$("#cotizacion_id").val(json[2]);
		    			obtenerprevias();
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
		var cotizacion_id=$("#cotizacion_id").val();
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
		    	url:'../trabajodetalles/'+id,
		    	type:'DELETE',
		    	dataType:'json',
		    	data:{cotizacion_id},
		    	success: function(json){
		    		if(json[0]==1){
		    			toastr.success("trabajo eliminado con éxito");
		    			swal.closeModal();
		    			$("#cotizacion_id").val(json[2]);
		    			obtenerprevias();
		    		}else{
		    			toastr.error("Ocurrió un error");
		    			swal.closeModal();
		    		}
		    	}
		    });
		  }
		});
		
	});



	//actualizar el campo subtotal para repuestos
	$(document).on("input",".n_precio_r,.n_cantidad_r",function(e){
		e.preventDefault();
		var precio=parseFloat($(".n_precio_r").val());
		var cantidad=parseInt($(".n_cantidad_r").val());
		var subto=precio*cantidad;
		$(".n_subto_r").val(subto);
	});

	$(document).on("input",".n_precio_t",function(e){
		e.preventDefault();
		var precio=parseFloat($(".n_precio_t").val());
		var subto=precio*1;
		$(".n_subto_t").val(subto);
	});

	$(document).on("input",".n_precio_tr",function(e){
		e.preventDefault();
		var precio=parseFloat($(".n_precio_tr").val());
		var subto=precio*1;
		$(".n_subto_tr").val(subto);
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

function obtenerprevias(){
	$.ajax({
		url:'../cotizaciones/previas',
		type:'get',
		dataType:'json',
		success: function(json){
			if(json[0]==1){
				$("#tabita>tbody").empty();
				$("#tabita>tbody").html(json[2]);
			}
		}
	});
}

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