<?php	
	$peticionAjax = true;
	require_once "../core/configGenerales.php";
	
	if(isset($_POST['prestamo_id'])){
		require_once "../controladores/prestamoControlador.php";
		$insVarios = new prestamoControlador();
		
		echo $insVarios->delete_prestamo_controlador();
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