<?php
	$peticionAjax = true;
	require_once "../core/configGenerales.php";

	if(isset($_POST['inputEmail']) && isset($_POST['inputPassword'])){
		require_once "../controladores/loginControlador.php";
		require_once "../core/mainModel.php";
		
		$login = new loginControlador();
		$date = date("Y-m-d");
		$año = date("Y");
		$mes = date("m");

		$fecha_inicial = date("Y-m-d", strtotime($año."-".$mes."-01"));
		$fecha_final = date("Y-m-d", strtotime($año."-".$mes."-10"));

		if(DB == "kireds_fayad"){
			echo $login->iniciar_sesion_controlador();
		}else{
			//SI EL CLIENTE ESTA DENTRO DEL TIEMPO PERMITIDO, INICIA LA SESION CORRECTAMENTE
			if($date >= $fecha_inicial && $date <= $fecha_final){
				echo $login->iniciar_sesion_controlador();
			}else{//CASO CONTRARIO ENTRAMOS EN UNA VALIDACION
				if($login->validar_facturacion_main_server_controlador() == 1){
					echo $login->iniciar_sesion_controlador();
				}else{
					echo $login->validar_facturacion_main_server_controlador();
				}
			}
		}

		$insMainModel = new mainModel();

		//mainModel::guardar_historial_accesos("Inicio de Sesion");
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
