<?php
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$result = $insMainModel->getTipoCuentaBancos();

	if($result->num_rows>0){
		while($consulta2 = $result->fetch_assoc()){
			echo '<option value="'.$consulta2['tipo_pago_id'].'">'.$consulta2['nombre'].'</option>';
		}
	}
?>