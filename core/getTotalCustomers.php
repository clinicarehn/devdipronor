<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$result = $insMainModel->getTotalCustomers();
	
	$totalCustomers = 0;
	
	if($result->num_rows>0){
		$consulta2 = $result->fetch_assoc();
		$totalCustomers = $consulta2['total'];
	}
	
	echo $totalCustomers;
?>	