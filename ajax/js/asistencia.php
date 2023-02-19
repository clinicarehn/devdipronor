<script>
$(document).ready(function() {
	listar_asistencia();
	getColaboradores();
	$('#form_main_asistencia #estado').val(0);
	$('#form_main_asistencia #estado').selectpicker('refresh');	
});

$('#form_main_asistencia #estado').on("change", function(e){
	listar_asistencia();
});

//INICIO ACCIONES FROMULARIO PRIVILEGIOS
var listar_asistencia = function(){
	var estado = $('#form_main_asistencia #estado').val();
	var colaboradores_id = $('#form_main_asistencia #colaborador').val();
	var fechai = $('#form_main_asistencia #fechai').val();
	var fechaf = $('#form_main_asistencia #fechaf').val();

	var table_asistencia  = $("#dataTableAsistencia").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL;?>core/llenarDataTableAsistencia.php",
			"data":{
				"fechai":fechai,
				"fechaf":fechaf,
				"colaborador":colaboradores_id,
				"estado":estado
			}			
		},
		"columns":[
			{"data":"empleado"},
			{"data":"lunes"},
			{"data":"martes"},
			{"data":"miercoles"},
			{"data":"jueves"},
			{"data":"viernes"},
			{"data":"sabado"},
			{"data":"domingo"},
			{"data":"total"},
			{"data":"vale"},
			{"data":"total_vale"},	
			{"defaultContent":"<button class='table_consultar consultar_asistencia btn btn-dark ocultar'><span class='fas fa-search fa-lg'></span></button>"},
			{"defaultContent":"<button class='table_eliminar eliminar_prestamo btn btn-dark ocultar'><span class='fa fa-trash fa-lg'></span></button>"},
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "17.09%", targets: 0 },
		  { width: "8.09%", targets: 1 },
		  { width: "8.09%", targets: 2 },
		  { width: "8.09%", targets: 3 },
		  { width: "8.09%", targets: 4 },
		  { width: "8.09%", targets: 5 },
		  { width: "8.09%", targets: 6 },
		  { width: "8.09%", targets: 7},
		  { width: "8.09%", targets: 8 },
		  { width: "9.09%", targets: 9},
		  { width: "9.09%", targets: 10 }
		],		
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Asistencia',
				className: 'btn btn-secondary',
				action: 	function(){
					listar_asistencia();
				}
			},		
			{
				text:      '<i class="fas fas fa-plus fa-lg"></i> Ingresar Asistencia',
				titleAttr: 'Agregar Asistencia',
				className: 'btn btn-primary',
				action: 	function(){
					modal_asistencia();
				}
			},
			{
				text:      '<i class="fas fas fa-plus fa-lg"></i> Ingresar Vales',
				titleAttr: 'Agregar Vales',
				className: 'btn btn-primary',
				action: 	function(){
					modal_vales();
				}
			},			
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte Asistencia',
				messageTop: 'Semana del: ' + convertDateFormat(fechai) + ' Fecha hasta: ' + convertDateFormat(fechaf),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'btn btn-success',
				exportOptions: {
					columns: [0,1,2,3,4,5,6,7,8,9,10]
				},
			},
			{
				extend:    'pdf',
				text:      '<i class="fas fa-file-pdf fa-lg"></i> PDF',
				titleAttr: 'PDF',
				orientation: 'landscape',
				pageSize: 'LETTER',	
				title: 'Reporte Asistencia',
				messageTop: 'Semana del: ' + convertDateFormat(fechai) + ' Fecha hasta: ' + convertDateFormat(fechaf),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'btn btn-danger',
				exportOptions: {
						columns: [0,1,2,3,4,5,6,7,8,9,10]
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
	table_asistencia.search('').draw();
	$('#buscar').focus();

	view_asistencia_colaboradores_dataTable("#dataTableAsistencia tbody", table_asistencia);
	delete_prestamos_colaboradores_dataTable("#dataTableAsistencia tbody", table_asistencia);
}

var view_asistencia_colaboradores_dataTable = function(tbody, table){
	$(tbody).off("click", "button.consultar_asistencia");
	$(tbody).on("click", "button.consultar_asistencia", function(){
		var data = table.row( $(this).parents("tr") ).data();
		var url = '<?php echo SERVERURL;?>core/editarPrestamos.php';

		$('#formulario_consultar_asistencia')[0].reset();
		$('#formulario_consultar_asistencia #colaboradores_id').val(data.colaboradores_id);

		listar_consulta_asistencia();

		$('#modal_consultar_asistencia').modal({
			show:true,
			keyboard: false,
			backdrop:'static'
		});
	});
}

var delete_prestamos_colaboradores_dataTable = function(tbody, table){
	$(tbody).off("click", "button.eliminar_prestamo");
	$(tbody).on("click", "button.eliminar_prestamo", function(){
		var data = table.row( $(this).parents("tr") ).data();
		var url = '<?php echo SERVERURL;?>core/editarPrestamos.php';
		$('#formVales')[0].reset();
		$('#formVales #prestamo_id').val(data.prestamo_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formVales').serialize(),
			success: function(registro){
				var valores = eval(registro);
				$('#formVales').attr({ 'data-form': 'delete' });
				$('#formVales').attr({ 'action': '<?php echo SERVERURL;?>ajax/deletePrestamoAjax.php' });				
				$('#reg_vales').hide();
				$('#delete_vales').show();

				$('#formVales #vale_empleado').val(valores[0]);
				$('#formVales #vale_empleado').selectpicker('refresh');
				$('#formVales #fecha').val(valores[1]);
				$('#formVales #vale').val(valores[2]);

				//DESHABILITAR OBJETOS
				$('#formVales #vale_empleado').attr("disabled", true);
				$('#formVales #fecha').attr("readonly", true);
				$('#formVales #vale').attr("readonly", true);				
				
				$('#formVales #proceso_vales').val("Eliminar");
				$('#modal_registrar_vales').modal({
					show:true,
					keyboard: false,
					backdrop:'static'
				});
			}
		});
	});
}

$(document).ready(function(){
    $("#modal_consultar_asistencia").on('shown.bs.modal', function(){
        $(this).find('#formulario_consultar_asistencia #buscar').focus();
    });
});

var listar_consulta_asistencia = function(){
	var colaboradores_id = $('#formulario_consultar_asistencia #colaboradores_id').val();

	var table_consulta_asistencia = $("#DatatableConsultarAsistencia").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL;?>core/llenarDataTableConsultaAsistencia.php",
			"data":{
				"colaboradores_id":colaboradores_id
			}			
		},
		"columns":[
			{"data":"colaborador"},
			{"data":"fecha"},
			{"defaultContent":"<button class='table_eliminar eliminar_asistencia btn btn-dark ocultar'><span class='fa fa-trash fa-lg'></span></button>"},
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,
		"dom": dom,
		"columnDefs": [
		  { width: "33.33%", targets: 0 },
		  { width: "33.33%", targets: 1 },
		  { width: "33.33%", targets: 2 },
		],		
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Asistencia',
				className: 'btn btn-secondary',
				action: 	function(){
					listar_asistencia();
				}
			},	
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte Asistencia',
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'btn btn-success',
				exportOptions: {
					columns: [0,1,2,3,4,5,6,7,8,9,10]
				},
			},
			{
				extend:    'pdf',
				text:      '<i class="fas fa-file-pdf fa-lg"></i> PDF',
				titleAttr: 'PDF',
				orientation: 'landscape',
				pageSize: 'LETTER',	
				title: 'Reporte Asistencia',
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'btn btn-danger',
				exportOptions: {
						columns: [0,1,2,3,4,5,6,7,8,9,10]
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
	table_consulta_asistencia.search('').draw();
	$('#buscar').focus();

	delete_asistencia_colaboradores_dataTable("#DatatableConsultarAsistencia tbody", table_consulta_asistencia);
}

var delete_asistencia_colaboradores_dataTable = function(tbody, table){
	$(tbody).off("click", "button.eliminar_asistencia");
	$(tbody).on("click", "button.eliminar_asistencia", function(e){
		e.preventDefault();
		var data = table.row( $(this).parents("tr") ).data();

		swal({
			title: "¿Estas seguro?",
			text: "¿Desea eliminar la asistencia para el colaborador: # " + data.colaborador + ", para la fecha " + data.fecha + "?",
			type: "info",
			showCancelButton: true,
			confirmButtonClass: "btn-primary",
			confirmButtonText: "¡Sí, eliminar la asistencia!",
			cancelButtonText: "Cancelar",
			closeOnConfirm: false
		},
		function(){
			deleteAsistenciaColaborador(data.asistencia_id);
		});
	});
}

function deleteAsistenciaColaborador(asistencia_id){
    var url = '<?php echo SERVERURL;?>core/deleteAsistenciaColaborador.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
		data:'asistencia_id='+asistencia_id,
        success: function(data){
		    if(data == 1){
				swal({
					title: "Success",
					text: "La asitencia ha sido eliminada correctamente",
					type: "success",
				});
				listar_consulta_asistencia();
				listar_asistencia();
			}else{
				swal({
					title: 'Error', 
					text: 'Lo sentimos no se puede eliminar la asistencia',
					type: 'error', 
					confirmButtonClass: 'btn-danger'
				});	
			}
		}
     });
}

function getColaboradores(){
    var url = '<?php echo SERVERURL;?>core/getColaboradores.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#form_main_asistencia #colaborador').html("");
			$('#form_main_asistencia #colaborador').html(data);
			$('#form_main_asistencia #colaborador').selectpicker('refresh');	
			
		    $('#formAsistencia #asistencia_empleado').html("");
			$('#formAsistencia #asistencia_empleado').html(data);
			$('#formAsistencia #asistencia_empleado').selectpicker('refresh');	
			
		    $('#formVales #vale_empleado').html("");
			$('#formVales #vale_empleado').html(data);
			$('#formVales #vale_empleado').selectpicker('refresh');				
		}
     });
}

function modal_asistencia(){
	  $('#formAsistencia').attr({ 'data-form': 'save' });
	  $('#formAsistencia').attr({ 'action': '<?php echo SERVERURL;?>ajax/addAsistenciaAjax.php' });
	  $('#formAsistencia')[0].reset();
	  $('#reg_asistencia').show();	
	  $('#formAsistencia #proceso_asistencia').val("Registro");
	  getColaboradores();
	  $('#modal_registrar_asistencia').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	  });
}

function modal_vales(){
	$('#formVales').attr({ 'data-form': 'save' });
	$('#formVales').attr({ 'action': '<?php echo SERVERURL;?>ajax/addPrestamoAjax.php' });
	$('#formVales')[0].reset();
	$('#reg_vales').show();
	$('#delete_vales').hide();
	$('#formVales #proceso_vales').val("Registro");
	getColaboradores();

	 //HABILITAR OBJETOS
	$('#formVales #vale_empleado').attr("disabled", false);
	$('#formVales #fecha').attr("readonly", false);
	$('#formVales #vale').attr("readonly", false);

	$('#modal_registrar_vales').modal({
	show:true,
	keyboard: false,
	backdrop:'static'
	});
}

document.addEventListener("DOMContentLoaded", function(){
    // Invocamos cada 1 segundos ;)
    const milisegundos = 1 *500;
    setInterval(function(){
        // No esperamos la respuesta de la petición porque no nos importa
        showTime();
    },milisegundos);
});

$(document).ready(function(){	
	showTime();
});

function showTime(){
	const current = new Date();

	const time = current.toLocaleTimeString("en-US", {
		hour: "2-digit",
		minute: "2-digit",
		hour12: false
	});

	$('#formAsistencia #hora').val(time);	
}
</script>