<?php	
	$peticionAjax = true;
	require_once "../core/configGenerales.php";
	
	if(isset($_POST['categoria_id']) && isset($_POST['categoria_productos'])){
		require_once "../controladores/categoriaProductosControlador.php";
		$insVarios = new categoriaProductosControlador();
		
		echo $insVarios->delete_categoria_productos_controlador();
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