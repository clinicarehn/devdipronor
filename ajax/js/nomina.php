<script>
$(document).ready(function() {
	getTipoContrato();
	getPagoPlanificado();
	getTipoEmpleado();
	listar_nominas();
});

//INICIO ACCIONES FROMULARIO NOMINAS
var listar_nominas = function(){
	var estado = $("#form_main_nominas #estado_nomina").val();	
	var tipo_contrato = $("#form_main_nominas #tipo_contrato_nomina").val();
	var pago_planificado = $("#form_main_nominas #pago_planificado_nomina").val();

	var table_nominas  = $("#dataTableNomina").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL;?>core/llenarDataTableNomina.php",
			"data":{
				"estado":estado,
				"tipo_contrato":tipo_contrato,
				"pago_planificado":pago_planificado
			}			
		},
		"columns":[
			{"data":"nomina_id"},
			{"data":"contrato"},
			{"data":"empresa"},
			{"data":"fecha_inicio"},
			{"data":"fecha_fin"},
			{"data":"importe",
				render: function (data, type) {
                    var number = $.fn.dataTable.render
                        .number(',', '.', 2, 'L ')
                        .display(data);
 
                    if (type === 'display') {
                        let color = 'green';
                        if (data < 0) {
                            color = 'red';
                        } 
 
                        return '<span style="color:' + color + '">' + number + '</span>';
                    }
 
                    return number;
                },
			},
			{"data":"notas"},									
			{"defaultContent":"<button class='table_editar btn btn-dark ocultar'><span class='fas fa-edit fa-lg'></span></button>"},
			{"defaultContent":"<button class='table_eliminar btn btn-dark ocultar'><span class='fa fa-trash fa-lg'></span></button>"}
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,	
		"columnDefs": [
		  { width: "11.11%", targets: 0 },
		  { width: "11.11%", targets: 1 },
		  { width: "11.11%", targets: 2 },
		  { width: "11.11%", targets: 3 },
		  { width: "11.11%", targets: 4 },
		  { width: "11.11%", targets: 5 },
		  { width: "11.11%", targets: 6 },
		  { width: "11.11%", targets: 7 },
		  { width: "11.11%", targets: 8 }		  
		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar listar_nominas',
				className: 'table_actualizar btn btn-secondary ocultar',
				action: 	function(){
					listar_contratos();
				}
			},
			{
				text:      '<i class="fas fas fa-plus fa-lg"></i> Ingresar',
				titleAttr: 'Agregar Nomina',
				className: 'table_crear btn btn-primary ocultar',
				action: 	function(){
					modal_nominas();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Nomina Empleados',
				messageTop: 'Fecha: ' + convertDateFormat(today()),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-success ocultar',
				exportOptions: {
						columns: [0,1,2,3,4,5,6,7,8]
				}					
			},
			{
				extend:    'pdf',
				text:      '<i class="fas fa-file-pdf fa-lg"></i> PDF',
				titleAttr: 'PDF',
				orientation: 'landscape',
				title: 'Nomina Empleados',
				messageTop: 'Fecha: ' + convertDateFormat(today()),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-danger ocultar',
				exportOptions: {
						columns: [0,1,2,3,4,5,6,7,8]
				},				
				customize: function ( doc ) {
					doc.content.splice( 1, 0, {
						margin: [ 0, 0, 0, 12 ],
						alignment: 'left',
						image: imagen,
						width:100,
                        height:45
					} );
				}
			}
		],
		"drawCallback": function( settings ) {
        	getPermisosTipoUsuarioAccesosTable(getPrivilegioTipoUsuario());
    	}
	});
	table_nominas.search('').draw();
	$('#buscar').focus();

	editar_nominas_dataTable("#dataTableNomina tbody", table_nominas);
	eliminar_nominas_dataTable("#dataTableNomina tbody", table_nominas);
}

var editar_nominas_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_editar");
	$(tbody).on("click", "button.table_editar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		var url = '<?php echo SERVERURL;?>core/editarNominas.php';
		$('#formContrato #contrato_id').val(data.contrato_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formContrato').serialize(),
			success: function(registro){
				var valores = eval(registro);
				$('#formContrato').attr({ 'data-form': 'update' });
				$('#formContrato').attr({ 'action': '<?php echo SERVERURL;?>ajax/modificarNominaAjax.php' });
				$('#formContrato')[0].reset();
				$('#reg_nomina').hide();
				$('#edi_nomina').show();
				$('#delete_nomina').hide();
				$('#formContrato #contrato_colaborador_id').val(valores[0]);
				$('#formContrato #colaborador_id').val(valores[0]);
				$('#formContrato #contrato_tipo_contrato_id').val(valores[1]);
				$('#formContrato #contrato_pago_planificado_id').val(valores[2]);
				$('#formContrato #contrato_tipo_empleado_id').val(valores[3]);
				$('#formContrato #contrato_salario').val(valores[4]);
				$('#formContrato #contrato_fecha_inicio').val(valores[5]);
				$('#formContrato #contrato_fecha_fin').val(valores[6]);
				$('#formContrato #contrato_notas').val(valores[7]);

				if(valores[8] == 1){
					$('#formContrato #contrato_activo').attr('checked', true);
				}else{
					$('#formContrato #contrato_activo').attr('checked', false);
				}

				//HABILITAR OBJETOS				
				$('#formContrato #contrato_tipo_contrato_id').attr('disabled', false);
				$('#formContrato #contrato_pago_planificado_id').attr('disabled', false);
				$('#formContrato #contrato_tipo_empleado_id').attr('disabled', false);
				$('#formContrato #contrato_salario').attr('readonly', false);
				$('#formContrato #contrato_fecha_inicio').attr('readonly', false);
				$('#formContrato #contrato_fecha_fin').attr('readonly', false);
				$('#formContrato #contrato_notas').attr('readonly', false);
				$('#formContrato #contrato_activo').attr('disabled', false);

				//DESHABILITATR OBJETOS
				$('#formContrato #contrato_colaborador_id').attr('disabled', true);
				$('#formContrato #buscar_contrato_empleado').hide();

				$('#formContrato #proceso_contrato').val("Editar");

				$('#modal_registrar_contrato').modal({
					show:true,
					keyboard: false,
					backdrop:'static'
				});
			}
		});
	});
}

var eliminar_nominas_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_eliminar");
	$(tbody).on("click", "button.table_eliminar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		var url = '<?php echo SERVERURL;?>core/editarNominas.php';
		$('#formNomina #contrato_id').val(data.contrato_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formNomina').serialize(),
			success: function(registro){
				var valores = eval(registro);
				$('#formNomina').attr({ 'data-form': 'delete' });
				$('#formNomina').attr({ 'action': '<?php echo SERVERURL;?>ajax/eliminarNominaAjax.php' });
				$('#formNomina')[0].reset();
				$('#reg_nomina').hide();
				$('#edi_nomina').hide();
				$('#delete_nomina').show();
				$('#formNomina #contrato_colaborador_id').val(valores[0]);
				$('#formNomina #colaborador_id').val(valores[0]);
				$('#formNomina #contrato_tipo_contrato_id').val(valores[1]);
				$('#formNomina #contrato_pago_planificado_id').val(valores[2]);
				$('#formNomina #contrato_tipo_empleado_id').val(valores[3]);
				$('#formNomina #contrato_salario').val(valores[4]);
				$('#formNomina #contrato_fecha_inicio').val(valores[5]);
				$('#formNomina #contrato_fecha_fin').val(valores[6]);
				$('#formNomina #contrato_notas').val(valores[7]);

				if(valores[8] == 1){
					$('#formNomina #contrato_activo').attr('checked', true);
				}else{
					$('#formNomina #contrato_activo').attr('checked', false);
				}

				//DESHABILITAR OBJETOS
				/*$('#formNomina #contrato_colaborador_id').attr('disabled', true);
				$('#formNomina #contrato_tipo_contrato_id').attr('disabled', true);
				$('#formNomina #contrato_pago_planificado_id').attr('disabled', true);
				$('#formNomina #contrato_tipo_empleado_id').attr('disabled', true);
				$('#formNomina #contrato_salario').attr('readonly', true);
				$('#formNomina #contrato_fecha_inicio').attr('readonly', true);
				$('#formNomina #contrato_fecha_fin').attr('readonly', true);
				$('#formNomina #contrato_activo').attr('disabled', true);
				$('#formNomina #contrato_notas').attr('readonly', true);
				$('#formNomina #buscar_contrato_empleado').hide();*/

				$('#formNomina #proceso_nomina').val("Eliminar");

				$('#modal_registrar_nomina').modal({
					show:true,
					keyboard: false,
					backdrop:'static'
				});
			}
		});
	});
}
//FIN ACCIONES FROMULARIO CONTRATOS

/*INICIO FORMULARIO CONTRATOS*/
function modal_nominas(){
	  $('#formNomina').attr({ 'data-form': 'save' });
	  $('#formNomina').attr({ 'action': '<?php echo SERVERURL;?>ajax/addNominaAjax.php' });
	  $('#formNomina')[0].reset();
	  $('#reg_nomina').show();
	  $('#edi_nomina').hide();
	  $('#delete_nomina').hide();

	  //HABILITAR OBJETOS
	  /*$('#formNomina #contrato_colaborador_id').attr('disabled', false);
	  $('#formNomina #contrato_tipo_contrato_id').attr('disabled', false);
	  $('#formNomina #contrato_pago_planificado_id').attr('disabled', false);
	  $('#formNomina #contrato_tipo_empleado_id').attr('disabled', false);
	  $('#formNomina #contrato_salario').attr('readonly', false);
	  $('#formNomina #contrato_fecha_inicio').attr('readonly', false);
	  $('#formNomina #contrato_fecha_fin').attr('disabled', false);
	  $('#formNomina #contrato_notas').attr('readonly', false);
	  $('#formNomina #contrato_activo').attr('disabled', false);
	  $('#formNomina #buscar_contrato_empleado').show();*/

	  $('#formNomina #proceso_nomina').val("Registro");

	  $('#modal_registrar_nomina').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	  });
}
/*FIN FORMULARIO CONTRATOS*/

$(document).ready(function(){
    $("#modal_registrar_contrato").on('shown.bs.modal', function(){
        $(this).find('#formContrato #puesto').focus();
    });
});

$('#formContrato #label_nomina_activo').html("Activo");
	
$('#formContrato .switch').change(function(){    
    if($('input[name=nomina_activo]').is(':checked')){
        $('#formContrato #label_nomina_activo').html("Activo");
        return true;
    }
    else{
        $('#formContrato #label_nomina_activo').html("Inactivo");
        return false;
    }
});	

function getTipoContrato(){
    var url = '<?php echo SERVERURL;?>core/getTipoContrato.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#form_main_nominas #tipo_contrato_nomina').html("");
			$('#form_main_nominas #tipo_contrato_nomina').html(data);	
			$('#form_main_nominas #tipo_contrato_nomina').selectpicker('refresh');	
			
		    $('#formNomina #nomina_tipo_contrato_id').html("");
			$('#formNomina #nomina_tipo_contrato_id').html(data);
			$('#formNomina #nomina_tipo_contrato_id').selectpicker('refresh');		
		}
     });
}

function getPagoPlanificado(){
    var url = '<?php echo SERVERURL;?>core/getPagoPlanificado.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#form_main_nominas #pago_planificado_nomina').html("");
			$('#form_main_nominas #pago_planificado_nomina').html(data);
			$('#form_main_nominas #pago_planificado_nomina').selectpicker('refresh');	
			
		    $('#formNomina #nomina_pago_planificado_id').html("");
			$('#formNomina #nomina_pago_planificado_id').html(data);
			$('#formNomina #nomina_pago_planificado_id').selectpicker('refresh');				
		}
     });
}

function getTipoEmpleado(){
    var url = '<?php echo SERVERURL;?>core/getTipoEmpleado.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){			
		    $('#form_main_contrato #tipo_empleado').html("");
			$('#form_main_contrato #tipo_empleado').html(data);	
			$('#form_main_contrato #tipo_empleado').selectpicker('refresh');				

		    $('#formNomina #nomina_tipo_empleado_id').html("");
			$('#formNomina #nomina_tipo_empleado_id').html(data);	
			$('#formNomina #nomina_tipo_empleado_id').selectpicker('refresh');				
		}
     });
}

//INICIO FORMULARIO CONRATO
function getEmpleado(){
    var url = '<?php echo SERVERURL;?>core/getEmpleado.php';
		
	$('#framework').change(function(){
		$('#hidden_framework').val($('#framework').val());
	});


	$.ajax({
		url:url,
		method:"POST",
		success:function(data){
			//console.log(data);
			$('#hidden_framework').val('');
			$('#framework').html(data);
			$('.selectpicker').selectpicker('val', '');
		}
	});
}
// FIN FORMULARIO CONTRATO

$('#formContrato #contrato_notas').keyup(function() {
	    var max_chars = 254;
        var chars = $(this).val().length;
        var diff = max_chars - chars;
		
		$('#formContrato #charNum_contrato_notas').html(diff + ' Caracteres'); 
		
		if(diff == 0){
			return false;
		}
});

function caracteresEstadoContrato(){
	var max_chars = 254;
	var chars = $('#formContrato #contrato_notas').val().length;
	var diff = max_chars - chars;

	$('#formContrato #charNum_contrato_notas').html(diff + ' Caracteres'); 
	
	if(diff == 0){
		return false;
	}
}

//INICIO GRABACIONES POR VOZ
$(document).ready(function() {
	//INICIO FORMULARIO ATENCIONES EXPEDIENTE CLINICO
	$('#formContrato #search_contrato_notas_stop').hide();
	
    var recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.lang = "es";
	
    $('#formContrato #search_contrato_notas_start').on('click',function(event){
		$('#formContrato #search_contrato_notas_start').hide();
		$('#formContrato #search_contrato_notas_stop').show();

		recognition.start();
		
		recognition.onresult = function (event) {
			finalResult = '';
			var valor_anterior  = $('#formContrato #contrato_notas').val();
			for (var i = event.resultIndex; i < event.results.length; ++i) {
				if (event.results[i].isFinal) {
					finalResult = event.results[i][0].transcript;
					if(valor_anterior != ""){
						$('#formContrato #contrato_notas').val(valor_anterior + ' ' + finalResult);
						caracteresEstadoContrato();
					}else{
						$('#formContrato #contrato_notas').val(finalResult);
						caracteresEstadoContrato();
					}				
				}
			}
		};		
		return false;
    });	
	
	$('#formContrato #search_contrato_notas_stop').on("click", function(event){
		$('#formContrato #search_contrato_notas_start').show();
		$('#formContrato #search_contrato_notas_stop').hide();
		recognition.stop();
	});	

	/*###############################################################################################################################*/
});	
</script>