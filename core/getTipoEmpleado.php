<?php
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$result = $insMainModel->getTipoEmpleado();

	if($result->num_rows>0){
		while($consulta2 = $result->fetch_assoc()){
			echo '<option value="'.$consulta2['tipo_empleado_id'].'">'.$consulta2['nombre'].'</option>';
		}
	}else{
		echo '<option value="">Seleccione</option>';
	}
?>