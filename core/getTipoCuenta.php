<?php
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$result = $insMainModel->getTipoCuenta();

	if($result->num_rows>0){
		echo '<option value="">Seleccione un Tipo de Cuenta</option>';	
		while($consulta2 = $result->fetch_assoc()){
			echo '<option value="'.$consulta2['tipo_cuenta_id'].'">'.$consulta2['nombre'].'</option>';
		}
	}
?>