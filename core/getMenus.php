<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$result = $insMainModel->getMenus();
	
	if($result->num_rows>0){
		while($consulta2 = $result->fetch_assoc()){
			 echo '<option value="'.$consulta2['menu_id'].'">'.$consulta2['name'].'</option>';
		}
	}else{
		echo '<option value="">Seleccione</option>';
	}
?>	