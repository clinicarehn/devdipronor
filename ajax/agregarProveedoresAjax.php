<?php	
	$peticionAjax = true;
	require_once "../core/configGenerales.php";
	
	if(isset($_POST['nombre_proveedores']) && isset($_POST['rtn_proveedores']) && isset($_POST['fecha_proveedores']) && isset($_POST['departamento_proveedores']) && isset($_POST['municipio_proveedores'])){
		require_once "../controladores/proveedoresControlador.php";
		$insVarios = new proveedoresControlador();
		
		echo $insVarios->agregar_proveedores_controlador();
	}else{
		echo "
			<script>
				swal({
					title: 'Error', 
					text: 'Los datos son incorrectos por favor corregir',
					type: 'error', 
					confirmButtonClass: 'btn-danger'
				});			
			</script>";
	}
?>	