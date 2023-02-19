<?php	
	$peticionAjax = true;
	require_once "../core/configGenerales.php";
	
	if(isset($_POST['vale_empleado'])){
		require_once "../controladores/prestamoControlador.php";
		$insVarios = new prestamoControlador();
		
		echo $insVarios->agregar_prestamo_controlador();
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