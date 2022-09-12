<script>
$(document).ready(function() {
    listar_tipo_cuenta_contabilidad();
});

//INICIO TIPO DE PAGO
var listar_tipo_cuenta_contabilidad = function(){
	var table_tipo_cuenta_contabilidad = $("#dataTableConfTipoCuenta").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL; ?>core/llenarDataTableConfTipoCuenta.php"
		},
		"columns":[
			{"data":"tipo_cuenta_id"},
			{"data":"nombre"},					
			{"defaultContent":"<button class='table_editar btn btn-dark ocultar'><span class='fas fa-edit'></span></button>"},
			{"defaultContent":"<button class='table_eliminar btn btn-dark ocultar'><span class='fa fa-trash'></span></button>"}
		],
        "lengthMenu": lengthMenu10,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,//esta se encuenta en el archivo main.js
		"dom": dom,
		"columnDefs": [
		  { width: "5%", targets: 0 },
		  { width: "85%", targets: 1 },
		  { width: "5%", targets: 2 },
		  { width: "5%", targets: 3 }		  
		],		
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Tipo de Cuenta',
				className: 'table_actualizar btn btn-secondary ocultar',
				action: 	function(){
					listar_tipo_cuenta_contabilidad();
				}
			},
			{
				text:      '<i class="fas fa-layer-group"></i> Crear',
				titleAttr: 'Agregar Tipo de Cuenta',
				className: 'table_crear btn btn-primary ocultar',
				action: 	function(){
					modalTipoCuenta();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte Tipo de Cuenta',
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-success ocultar',
				exportOptions: {
						columns: [0,1,2]
				}				
			},
			{
				extend:    'pdf',
				text:      '<i class="fas fa-file-pdf fa-lg"></i> PDF',
				titleAttr: 'PDF',
				title: 'Reporte Tipo de Cuenta',
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-danger ocultar',
				exportOptions: {
						columns: [0,1,2]
				},					
				customize: function ( doc ) {
					doc.content.splice( 1, 0, {
						margin: [ 0, 0, 0, 12 ],
						alignment: 'left',
						image: imagen,//esta se encuenta en el archivo main.js
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
	table_tipo_cuenta_contabilidad.search('').draw();
	$('#buscar').focus();

	edit_tipo_pago_contabilidad_dataTable("#dataTableConfTipoCuenta tbody", table_tipo_cuenta_contabilidad);
	delete_tipo_pago_contabilidad_dataTable("#dataTableConfTipoCuenta tbody", table_tipo_cuenta_contabilidad);
}

var edit_tipo_pago_contabilidad_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_editar");
	$(tbody).on("click", "button.table_editar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		var url = '<?php echo SERVERURL;?>core/editarTipoCuenta.php';
		$('#formConfTipoCuenta #tipo_cuenta_id').val(data.tipo_cuenta_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formConfTipoCuenta').serialize(),
			success: function(registro){
				var valores = eval(registro);
				$('#formConfTipoCuenta').attr({ 'data-form': 'update' });
				$('#formConfTipoCuenta').attr({ 'action': '<?php echo SERVERURL;?>ajax/modificarTipoCuentaAjax.php' });
				$('#formConfTipoCuenta')[0].reset();
				$('#reg_formTipoCuenta').hide();
				$('#edi_formTipoCuenta').show();
				$('#delete_formTipoCuenta').hide();
				$('#formConfTipoCuenta #pro_tipoCuenta').val("Editar");
				$('#formConfTipoCuenta #tipo_cuenta').val(valores[0]);				

				if(valores[1] == 1){
					$('#formConfTipoCuenta #confTipoCuenta_activo').attr('checked', true);
				}else{
					$('#formConfTipoCuenta #confTipoCuenta_activo').attr('checked', false);
				}

				//HABILITAR OBJETOS				
				$('#formConfTipoCuenta #tipo_cuenta').attr('readonly', false);
				$('#formConfTipoCuenta #confTipoCuenta_activo').attr('disabled', false);
				$('#formConfTipoCuenta #estado_tipo_cuenta').show();
				
				$('#modalConfTipoCuenta').modal({
					show:true,
					keyboard: false,
					backdrop:'static'
				});
			}
		});
	});
}

var delete_tipo_pago_contabilidad_dataTable = function(tbody, table){
	$(tbody).off("click", "button.table_eliminar");
	$(tbody).on("click", "button.table_eliminar", function(){
		var data = table.row( $(this).parents("tr") ).data();
		var url = '<?php echo SERVERURL;?>core/editarTipoCuenta.php';
		$('#formConfTipoCuenta #tipo_cuenta_id').val(data.tipo_cuenta_id);

		$.ajax({
			type:'POST',
			url:url,
			data:$('#formConfTipoCuenta').serialize(),
			success: function(registro){
				var valores = eval(registro);
				$('#formConfTipoCuenta').attr({ 'data-form': 'update' });
				$('#formConfTipoCuenta').attr({ 'action': '<?php echo SERVERURL;?>ajax/eliminarTipoCuentaAjax.php' });
				$('#formConfTipoCuenta')[0].reset();
				$('#reg_formTipoCuenta').hide();
				$('#edi_formTipoCuenta').show();
				$('#delete_formTipoCuenta').hide();
				$('#formConfTipoCuenta #pro_tipoCuenta').val("Eliminar");
				$('#formConfTipoCuenta #tipo_cuenta').val(valores[0]);				

				if(valores[1] == 1){
					$('#formConfTipoCuenta #confTipoCuenta_activo').attr('checked', true);
				}else{
					$('#formConfTipoCuenta #confTipoCuenta_activo').attr('checked', false);
				}

				//DESHABILITAR OBJETOS				
				$('#formConfTipoCuenta #tipo_cuenta').attr('readonly', true);
				$('#formConfTipoCuenta #confTipoCuenta_activo').attr('disabled', true);
				$('#formConfTipoCuenta #estado_tipo_cuenta').show();		

				$('#modalConfTipoCuenta').modal({
					show:true,
					keyboard: false,
					backdrop:'static'
				});
			}
		});
	});
}
//FIN TIPO DE PAGO

//INICIO FORMULARIO TIPO DE PAGO
function modalTipoCuenta(){
	$('#formConfTipoCuenta').attr({ 'data-form': 'save' });
	$('#formConfTipoCuenta').attr({ 'action': '<?php echo SERVERURL; ?>ajax/addTipoCuentaAjax.php' });
	$('#formConfTipoCuenta')[0].reset();
	$('#formConfTipoCuenta #pro_tipoCuenta').val("Registro");
	$('#reg_formTipoCuenta').show();
	$('#edi_formTipoCuenta').hide();
	$('#delete_formTipoCuenta').hide();

	//HABILITAR OBJETOS
	$('#formConfTipoCuenta #tipo_cuenta').attr('readonly', false);
	$('#formConfTipoCuenta #confTipoCuenta_activo').attr('disabled', false);
	$('#formConfTipoCuenta #estado_tipo_cuenta').hide();

	$('#modalConfTipoCuenta').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
}
//FIN FORMULARIO TIPO DE PAGO


$(document).ready(function(){
    $("#modalConfTipoCuenta").on('shown.bs.modal', function(){
        $(this).find('#formConfTipoCuenta #tipo_cuenta').focus();
    });
});

$('#formConfTipoCuenta #label_confTipoCuenta_activo').html("Activo");
	
$('#formConfTipoCuenta .switch').change(function(){    
    if($('input[name=confTipoCuenta_activo]').is(':checked')){
        $('#formConfTipoCuenta #label_confTipoCuenta_activo').html("Activo");
        return true;
    }
    else{
        $('#formConfTipoCuenta #label_confTipoCuenta_activo').html("Inactivo");
        return false;
    }
});	
</script>