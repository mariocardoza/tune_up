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

  //busqueda de documento
  $(document).on("click",".busqueda_modal",function(e){
    e.preventDefault();
    let type=$(this).attr('data-type');
    let text=$(this).attr('data-text');
    $("#eltype").val(type);
    $("#exampleModalLabel2").text(text);
    $("#modal_buscar").modal("show");
  });

  //buscar tipo documento
  $(document).on("submit","#form_buscadoc",function(e){
    e.preventDefault();
    var numero=$("#numerodoc").val();
    var dominio = window.location.host;  
    let formulario=$("#form_buscadoc").serialize();
    if(numero!=''){
      $.ajax({
        url:'/busqueda',
        type:'get',
        dataType:'json',
        data:formulario,
        success: function(json){
          if(json[0]==1){
            if(json[1]!=null){
              toastr.success("Documento encontrado");
              location.href=json[2];
               
            }else{
              toastr.error("Documento no encontrado");
            }
          }
        }
      });
    }else{
      toastr.error("Digite el número para buscar");
    }
  });

  //buscar la placa
  $(document).on("submit","#form_buscaplaca",function(e){
    e.preventDefault();
    var placa=$("#laplaquita").val();
    var dominio = window.location.host;
      
        var url='/vehiculos/porplaca';
  
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

              var url = '/vehiculos/historial/'+json[1].id;
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


  /* GENERAR EL DTE */
  $(document).on("click",".dte", function(e){
    e.preventDefault();
    var id =$(this).attr("data-id");
    var tipo =$(this).attr("data-tipo");
    $.ajax({
        url:'/facturacion',
        type:'post',
        dataType:'json',
        data:{id,tipo},
        success: function(response){
          if (response.success) {
            toastr.success("DTE Generado con éxito");
              const base64Data = response.pdf_base64;
              const filename = response.filename;

              // 1. Decodificar la cadena Base64 a un Blob binario
              const pdfBlob = base64ToBlob(base64Data, 'application/pdf');

              // 2. Crear un objeto URL para el Blob
              const blobUrl = URL.createObjectURL(pdfBlob);

              // 3. Crear un enlace (<a>) en la memoria
              const link = document.createElement('a');
              link.href = blobUrl;
              link.download = filename;

              // 4. Simular un clic para forzar la descarga
              document.body.appendChild(link);
              link.click();
              
              // 5. Limpiar
              document.body.removeChild(link);
              URL.revokeObjectURL(blobUrl);
          }
        },
        error:function(error){
          toastr.error(error.responseJSON.message);
        }
      });
  });


  /* ANULAR */
  $(document).on("click",'.anular',function(e){
    e.preventDefault();
    $("#modal_anular").modal("show");
  });

  /* ANULAR SUBMIT */
  $(document).on("submit","#form_anular",function(e){
    e.preventDefault();
    let formulario=$("#form_anular").serialize();
    $.ajax({
      url:'/facturacion/anular',
      type:'post',
      dataType:'json',
      data:formulario,
      success: function(response){
        if (response.success) {
            toastr.success("DTE Generado con éxito");
              const base64Data = response.pdf_base64;
              const filename = response.filename;

              // 1. Decodificar la cadena Base64 a un Blob binario
              const pdfBlob = base64ToBlob(base64Data, 'application/pdf');

              // 2. Crear un objeto URL para el Blob
              const blobUrl = URL.createObjectURL(pdfBlob);

              // 3. Crear un enlace (<a>) en la memoria
              const link = document.createElement('a');
              link.href = blobUrl;
              link.download = filename;

              // 4. Simular un clic para forzar la descarga
              document.body.appendChild(link);
              link.click();
              
              // 5. Limpiar
              document.body.removeChild(link);
              URL.revokeObjectURL(blobUrl);
          }
      },
      error:function(error){
          toastr.error(error.responseJSON.message);
        }
    });
  })
});

function base64ToBlob(base64, mimeType) {
    const byteCharacters = atob(base64);
    const byteArrays = [];

    for (let offset = 0; offset < byteCharacters.length; offset += 512) {
        const slice = byteCharacters.slice(offset, offset + 512);
        const byteNumbers = new Array(slice.length);
        for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }

    return new Blob(byteArrays, {type: mimeType});
}

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