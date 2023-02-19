<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$prestamo_id = $_POST['prestamo_id'];
	$result = $insMainModel->getPrestamoEdit($prestamo_id);
	$valores2 = $result->fetch_assoc();
	
	$datos = array(
		0 => $valores2['colaboradores_id'],
		1 => $valores2['fecha'],
		2 => $valores2['monto'],
		3 => $valores2['estado']
	);
	echo json_encode($datos);
?>	