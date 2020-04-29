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
        language:'es'
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