<?php	
	$peticionAjax = true;
	require_once "../core/configGenerales.php";
	
	if(isset($_POST['tipo_cuenta'])){
		require_once "../controladores/tipoCuentaControlador.php";
		$insVarios = new tipoCuentaControlador();
		
		echo $insVarios->agregar_tipo_cuenta_controlador();
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