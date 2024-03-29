<script>
$(document).ready(function() {
	funciones();   	
		listar_movimientos();
});

function funciones(){
	getTipoProductosMovimientos();
	getTipoProductos();
	getProductoOperacion();
	getClientes();
    getProductosMovimientos(1);
	getAlmacen();
}

$('#form_main_movimientos #categoria_id').on('change',function(){
  listar_movimientos();
});

$('#form_main_movimientos #fechai').on('change',function(){
  listar_movimientos();
});

$('#form_main_movimientos #fechaf').on('change',function(){
  listar_movimientos();
});

$('#form_main_movimientos #almacen').on('change',function(){
  listar_movimientos();
});

$('#producto_movimiento_filtro').on('change',function(){
  listar_movimientos();
});

$('#cliente_movimiento_filtro').on('change',function(){
  listar_movimientos();
});

$('#inventario_tipo_productos_id').on('change',function(){
  listar_movimientos();
});

//INICIO MOVIMIENTOS
var listar_movimientos = function(){
	var tipo_producto_id;
	tipo_producto_id = $('#form_main_movimientos #inventario_tipo_productos_id').val();
	var fechai = $("#form_main_movimientos #fechai").val();
	var fechaf = $("#form_main_movimientos #fechaf").val();
	var bodega = $("#form_main_movimientos #almacen").val();
	var producto = $("#producto_movimiento_filtro").val();
	var cliente = $('#cliente_movimiento_filtro').val();

	var table_movimientos  = $("#dataTablaMovimientos").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL;?>core/llenarDataTableMovimientos.php",
			"data":{
				"tipo_producto_id":tipo_producto_id,
				"fechai":fechai,
				"fechaf":fechaf,
				"bodega":bodega,
				"producto":producto,
				"cliente":cliente,
			}
		},
		"columns":[
			{"data":"fecha_registro"},
			{"data":"barCode"},
			{"data":"cliente"},
			{"data":"producto"},
			{"data":"medida"},
			{"data":"documento"},
			{"data":"entrada",
				render: function (data, type) {
                    var number = $.fn.dataTable.render
                        .number(',', '.', 2, '')
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
			{"data":"salida",
				render: function (data, type) {
                    var number = $.fn.dataTable.render
                        .number(',', '.', 2, '')
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
			
			{"data":"comentario"},
			{"data":"bodega"},
			
		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,//esta se encuenta en el archivo main.js
		"dom": dom,
		"columnDefs": [
		  { width: "13.5%", targets: 0 },
		  { width: "10.5%", targets: 1 },
		  { width: "20.5%", targets: 2 },
		  { width: "5.5%", targets: 3 },
		  { width: "18.5%", targets: 4 },
		  { width: "10.5%", targets: 5 },
		  { width: "10.5%", targets: 6 },
		  { width: "10.5%", targets: 7 },
		  { width: "10.5%", targets: 8 },
		  { width: "10.5%", targets: 9 },
		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Movimientos',
				className: 'table_actualizar btn btn-secondary ocultar',
				action: 	function(){
					listar_movimientos();

				}
			},
			{
				text:      '<i class="fas fas fa-plus fa-lg"></i> Ingresar',
				titleAttr: 'Agregar Movimientos',
				className: 'table_crear btn btn-primary ocultar',
				action: 	function(){
					modal_movimientos();
				}
			},
			{
				extend:    'excelHtml5',
				footer: true,
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte Movimientos',
				messageTop: 'Fecha desde: ' + convertDateFormat(fechai) + ' Fecha hasta: ' + convertDateFormat(fechaf),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-success ocultar',
				exportOptions: {
					columns: [0,1,2,3,4,5,6,7,8,9,10]
				},
			},
			{
				extend:    'pdf',
				footer: true,
				text:      '<i class="fas fa-file-pdf fa-lg"></i> PDF',
				titleAttr: 'PDF',
				orientation: 'landscape',
				title: 'Reporte Movimientos',
				messageTop: 'Fecha desde: ' + convertDateFormat(fechai) + ' Fecha hasta: ' + convertDateFormat(fechaf),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-danger ocultar',
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
	table_movimientos.search('').draw();
	table_movimientos.order([0,'desc'])
	$('#buscar').focus();

	total_movimiento_footer();
	//transferencia_producto_dataTable("#dataTablaMovimientos tbody",table_movimientos);

}
//FOOTER MOVIMIENTOS
var total_movimiento_footer = function(){	
	var tipo_producto_id = $('#form_main_movimientos #inventario_tipo_productos_id').val();
	var fechai = $("#form_main_movimientos #fechai").val();
	var fechaf = $("#form_main_movimientos #fechaf").val();
	var bodega = $("#form_main_movimientos #almacen").val();
	var producto = $("#producto_movimiento_filtro").val();
	var cliente = $('#cliente_movimiento_filtro').val();
	
	$.ajax({
		url : '<?php echo SERVERURL;?>core/totalMovimientosFooter.php',
		type: "POST",
		data : {
				"tipo_producto_id":tipo_producto_id,
				"fechai":fechai,
				"fechaf":fechaf,
				"bodega":bodega,
				"producto":producto,
				"cliente":cliente,
			}
		})
		.done(function(data) {
			data = JSON.parse(data)
			$("#entrada-footer-movimiento").html("L. " + data.entrada);
			$("#salida-footer-movimiento").html("L. " + data.salida);
			$("#total-footer-movimiento").html("L. " + data.saldo);			
		})
		.fail(function(data) {
			console.log( "total ingreso error" );
	});
}
//FIN MOVIMIENTOS

//INVENTARIO TRANSFERENCIA
var inventario_transferencia = function(){
	var tipo_producto_id;

	if ($('#form_main_movimientos #inventario_tipo_productos_id').val() == "" || $('#form_main_movimientos #inventario_tipo_productos_id').val() == null){
	  tipo_producto_id = 1;
	}else{
	  tipo_producto_id = $('#form_main_movimientos #inventario_tipo_productos_id').val();
	}

	var fechai = $("#form_main_movimientos #fechai").val();
	var fechaf = $("#form_main_movimientos #fechaf").val();
	var bodega = $("#form_main_movimientos #almacen").val();

	var table_movimientos  = $("#dataTablaMovimientos").DataTable({
		"destroy":true,
		"ajax":{
			"method":"POST",
			"url":"<?php echo SERVERURL;?>core/llenarDataTableInventarioTransferencia.php",
			"data":{
				"tipo_producto_id":tipo_producto_id,
				"fechai":fechai,
				"fechaf":fechaf,
				"bodega":bodega
			}
		},
		"columns":[
			{"data":"fecha_registro"},
			{"data":"barCode"},
			{"data":"producto"},
			{"data":"medida"},
			{"data":"documento"},
			{"data":"entrada",
				render: function (data, type) {
					if(data == null){
						data = 0;
					}

					var number = $.fn.dataTable.render
						.number(',', '.', 2, '')
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
			{"data":"salida",
				render: function (data, type) {
					if(data == null){
						data = 0;
					}

					var number = $.fn.dataTable.render
						.number(',', '.', 2, '')
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
			{"data":"saldo",
				render: function (data, type) {
					if(data == null){
						data = 0;
					}

					var number = $.fn.dataTable.render
						.number(',', '.', 2, '')
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
			{"data":"bodega"},
			{"defaultContent":"<button class='table_transferencia btn btn-dark'><span class='fa fa-exchange-alt fa-lg'></span></button>"},

		],
        "lengthMenu": lengthMenu,
		"stateSave": true,
		"bDestroy": true,
		"language": idioma_español,//esta se encuenta en el archivo main.js
		"dom": dom,
		"columnDefs": [
		  { width: "13.5%", targets: 0 },
		  { width: "10.5%", targets: 1 },
		  { width: "20.5%", targets: 2 },
		  { width: "5.5%", targets: 3 },
		  { width: "18.5%", targets: 4 },
		  { width: "10.5%", targets: 5 },
		  { width: "10.5%", targets: 6 },
		  { width: "10.5%", targets: 7 },
		  { width: "10.5%", targets: 8 },
		  { width: "10.5%", targets: 9 },


		],
		"buttons":[
			{
				text:      '<i class="fas fa-sync-alt fa-lg"></i> Actualizar',
				titleAttr: 'Actualizar Movimientos',
				className: 'table_actualizar btn btn-secondary ocultar',
				action: 	function(){
					inventario_transferencia();
				}
			},
			{
				text:      '<i class="fas fas fa-plus fa-lg"></i> Ingresar',
				titleAttr: 'Agregar Movimientos',
				className: 'table_crear btn btn-primary ocultar',
				action: 	function(){
					modal_movimientos();
				}
			},
			{
				extend:    'excelHtml5',
				text:      '<i class="fas fa-file-excel fa-lg"></i> Excel',
				titleAttr: 'Excel',
				title: 'Reporte Movimientos',
				messageTop: 'Fecha desde: ' + convertDateFormat(fechai) + ' Fecha hasta: ' + convertDateFormat(fechaf),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-success ocultar'
			},
			{
				extend:    'pdf',
				text:      '<i class="fas fa-file-pdf fa-lg"></i> PDF',
				titleAttr: 'PDF',
				orientation: 'landscape',
				title: 'Reporte Movimientos',
				messageTop: 'Fecha desde: ' + convertDateFormat(fechai) + ' Fecha hasta: ' + convertDateFormat(fechaf),
				messageBottom: 'Fecha de Reporte: ' + convertDateFormat(today()),
				className: 'table_reportes btn btn-danger ocultar',
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
	table_movimientos.search('').draw();
	$('#buscar').focus();

	transferencia_producto_dataTable("#dataTablaMovimientos tbody",table_movimientos);

}
//FIN TRANSFERENCIA

//TRANSFERIR PRODUCTO/BODEGA
var transferencia_producto_dataTable = function(tbody, table){

	$(tbody).off("click", "button.table_transferencia");
	$(tbody).on("click", "button.table_transferencia", function(){
		var data = table.row( $(this).parents("tr") ).data();		
		$('#formTransferencia #productos_id').val(data.productos_id);
		$('#formTransferencia #nameProduct').html(data.producto);
	
		$('#modal_transferencia_producto').modal({
			show:true,
			keyboard: false,
			backdrop:'static'
		});
	})

};

$("#putEditarBodega").click(function(){
	var form = $("#formTransferencia");
	var respuesta=form.children('.RespuestaAjax');
	var url = '<?php echo SERVERURL;?>ajax/modificarBodegaProductosAjax.php';
	$.ajax({
		type:'POST',
		url:url,
		data:$('#formTransferencia').serialize(),
			beforeSend: function() {
			  $('#modal_transferencia_producto').modal({
				show:false,
				keyboard: false,
				backdrop:'static'
			  });

			},
			success: function(data){
				$('#modal_transferencia_producto').modal('toggle');
				respuesta.html(data);
			}
	})
});
//TRANSFERIR PRODUCTO/BODEGA

function getAlmacen(){
    var url = '<?php echo SERVERURL;?>core/getAlmacen.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#form_main_movimientos #almacen').html("");
			$('#form_main_movimientos #almacen').html(data);
			$('#form_main_movimientos #almacen').selectpicker('refresh');	
			
			$('#formMovimientos #almacen_modal').html("");
			$('#formMovimientos #almacen_modal').html(data);
			$('#formMovimientos #almacen_modal').selectpicker('refresh');				
		}
     });
}

//INIICO OBTENER EL TIPO DE PRODUCTO
function getTipoProductos(){
    var url = '<?php echo SERVERURL;?>core/getTipoProductoMovimientos.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
		    $('#form_main_movimientos #inventario_tipo_productos_id').html("");
			$('#form_main_movimientos #inventario_tipo_productos_id').html(data);
			$('#form_main_movimientos #inventario_tipo_productos_id').selectpicker('refresh');
			
		    $('#formMovimientos #movimientos_tipo_producto_id').html("");
			$('#formMovimientos #movimientos_tipo_producto_id').html(data);	
			$('#formMovimientos #movimientos_tipo_producto_id').selectpicker('refresh');		
		}
     });
}
//FIN OBTENER EL TIPO DE PRODUCTO

function getProductoOperacion(){
	var url = '<?php echo SERVERURL;?>core/getOperacion.php';

	$.ajax({
        type: "POST",
        url: url,
        success: function(data){
		    $('#formMovimientos #movimiento_operacion').html("");
			$('#formMovimientos #movimiento_operacion').html(data);
			$('#formMovimientos #movimiento_operacion').selectpicker('refresh');
		}
     });
}

function getTipoProductosMovimientos(){
	var url = '<?php echo SERVERURL;?>core/getTipoProductoMovimientos.php';

	$.ajax({
        type: "POST",
        url: url,
        success: function(data){
		    $('#formMovimientos #movimientos_tipo_producto_id').html("");
			$('#formMovimientos #movimientos_tipo_producto_id').html(data);
			$('#formMovimientos #movimientos_tipo_producto_id').selectpicker('refresh');
		}
     });
}

$(document).ready(function() {
	$('#form_main_movimientos #inventario_tipo_productos_id').on('change', function(){
		var tipo_producto_id;

		if ($('#form_main_movimientos #inventario_tipo_productos_id').val() == "" || $('#form_main_movimientos #inventario_tipo_productos_id').val() == null){
		  tipo_producto_id = 1;
		}else{
		  tipo_producto_id = $('#form_main_movimientos #inventario_tipo_productos_id').val();
		}

		getProductosMovimientos(tipo_producto_id);
	    return false;
    });

	$('#formMovimientos #movimientos_tipo_producto_id').on('change', function(){
		var tipo_producto_id;

		if ($('#formMovimientos #movimientos_tipo_producto_id').val() == "" || $('#formMovimientos #movimientos_tipo_producto_id').val() == null){
		  tipo_producto_id = 1;
		}else{
		  tipo_producto_id = $('#formMovimientos #movimientos_tipo_producto_id').val();
		}

		getProductosMovimientos(tipo_producto_id);
	    return false;
    });
});

function getProductosMovimientos(tipo_producto_id){
    var url = '<?php echo SERVERURL; ?>core/getProductosMovimientosTipoProducto.php';

	$.ajax({
        type: "POST",
        url: url,
		data:'tipo_producto_id='+tipo_producto_id,
        success: function(data){
		    $('#form_main_movimientos #producto_movimiento_filtro').html("");
			$('#form_main_movimientos #producto_movimiento_filtro').html(data);
			$('#form_main_movimientos #producto_movimiento_filtro').selectpicker('refresh');		    
			
			$('#formMovimientos #movimiento_producto').html("");
			$('#formMovimientos #movimiento_producto').html(data);
			$('#formMovimientos #movimiento_producto').selectpicker('refresh');
		}
     });
}

function getClientes(){
    var url = '<?php echo SERVERURL;?>core/getClientesHostProductos.php';

	$.ajax({
        type: "POST",
        url: url,
	    async: true,
        success: function(data){
			$('#form_main_movimientos #cliente_movimiento_filtro').html("");
			$('#form_main_movimientos #cliente_movimiento_filtro').html(data);	
			$('#form_main_movimientos #cliente_movimiento_filtro').selectpicker('refresh');

		    $('#formMovimientos #cliente_movimientos').html("");
			$('#formMovimientos #cliente_movimientos').html(data);	
			$('#formMovimientos #cliente_movimientos').selectpicker('refresh');
		}
     });
}

//INICIO FORMULARIO MOVIMIENTOS
function modal_movimientos(){
	$('#formMovimientos').attr({ 'data-form': 'save' });
	$('#formMovimientos').attr({ 'action': '<?php echo SERVERURL; ?>ajax/agregarMovimientoProductosAjax.php' });
	$('#formMovimientos')[0].reset();
	$('#formMovimientos #proceso_movimientos').val("Registro");
	$('#modal_movimientos').show();
	funciones();
	$('#modal_movimientos').modal({
		show:true,
		keyboard: false,
		backdrop:'static'
	});
}
//FIN FORMULARIO MOVIMIENTOS

$(document).ready(function(){
    $("#modal_buscar_productos_movimientos").on('shown.bs.modal', function(){
        $(this).find('#formulario_busqueda_productos_movimientos #buscar').focus();
    });
});

$(document).ready(function(){
    $("#modal_movimientos").on('shown.bs.modal', function(){
        $(this).find('#formularioMovimientos #movimiento_categoria').focus();
    });
});

$(document).ready(function(){
    $("#modal_transferencia_producto").on('shown.bs.modal', function(){
        $(this).find('#formTransferencia #cantidad_movimiento').focus();
    });
});
</script>