$(document).ready(function(e){
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	Inputmask({"mask":"9999-9999","clearIncomplete":true}).mask(".telefono");
	Inputmask({"mask":"99999999-9","clearIncomplete":true}).mask(".dui");
	Inputmask({"mask":"9999-999999-999-9","clearIncomplete":true}).mask(".nit");

	$(".chosen-select").chosen({'width': "100%"});

	$('.fecha').datepicker({
    	format: 'dd/mm/yyyy',
    	minDate: "-60Y",
        maxDate: "-18Y",
        language:'es',
        autoclose:true
	});

 /* $(".form-control").on("keypress", function () {
       $input=$(this);
       setTimeout(function () {
        $input.val($input.val().toUpperCase());
       },50);
      })
*/
  //reporte de iva por pagar
  $(document).on("click",".ivapagar",function(e){
    e.preventDefault();
    $("#modal_reporte_iva").modal("show");
  });

  //reporte de trabajos a vehiculos
  $(document).on("click",".reportevehiculo",function(e){
    e.preventDefault();
    $("#modal_reporte_carro").modal("show");
  });

  //buscar la placa
  $(document).on("submit","#form_buscaplaca",function(e){
    e.preventDefault();
    var placa=$("#laplaquita").val();
    var dominio = window.location.host;
      
        var url='vehiculos/porplaca';
  
    if(placa!=''){
      $.ajax({
        url:url,
        type:'get',
        dataType:'json',
        data:{placa},
        success: function(json){
          if(json[0]==1){
            if(json[1]!=null){
              //location.href='vehiculos/historial/'+placa;
              var dominio2 = window.location.host;
              /*window.open(
                'http://'+dominio2+'/'+carpeta()+'/public/vehiculos/historial/'+json[1].id,
                '_blank' // <- This is what makes it open in a new window.
              );*/
              $("#modal_reporte_carro").modal("hide");

              var url = 'vehiculos/historial/'+json[1].id;
              $('#verpdf').attr('src', url);
              //$('#verpdf').reload();
              $("#modal_pdf").modal("show");
            }else{
              toastr.error("Vehículo no encontrado");
            }
          }
        }
      });
    }else{
      toastr.info("Digite una placa para buscar");
    }
  });

  //submit para reporte de iva por fecha
  $(document).on("submit","#form_buscaiva",function(e){
    e.preventDefault();
    var fecha1=$("#fecha1").val();
    var fecha2=$("#fecha2").val();
    if(fecha1!='' && fecha2!=''){
      var dominio = window.location.host;
      /*window.open(
        'http://'+dominio+'/'+carpeta()+'/public/ivaporventas?fecha1='+fecha1+'&fecha2='+fecha2,
        '_blank' // <- This is what makes it open in a new window.
      );*/
          $("#modal_reporte_iva").modal("hide");
          var url = '/ivaporventas?fecha1='+fecha1+'&fecha2='+fecha2;
          $('#verpdf').attr('src', url);
          //$('#verpdf').reload();
          $("#modal_pdf").modal("show");
    }
  });
});

function modal_cargando(){
        swal.fire({
          title: 'Cargando!',
          text: 'Este diálogo se cerrará al completar la operación.',
          allowOutsideClick: false,
          allowEscapeKey: false,
          showConfirmButton: false,
          onOpen: function () {
            swal.showLoading()
          }
        });
      }

function carpeta(){
      var carpeta = window.location.href;
      var nombre = carpeta.split("/");
      return nombre[3];
    }