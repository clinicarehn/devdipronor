<?php
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$result = $insMainModel->getTipoCorreo();

	if($result->num_rows>0){
		echo '<option value="">Seleccione un Tipo de Correo</option>';	
		while($consulta2 = $result->fetch_assoc()){
			echo '<option value="'.$consulta2['correo_tipo_id'].'">'.$consulta2['nombre'].'</option>';
		}
	}else{
		echo '<option value="">Seleccione un Tipo de Correo</option>';
	}
?>