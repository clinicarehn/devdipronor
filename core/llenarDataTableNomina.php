<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$datos = [
		"estado" => $_POST['estado'],
		"tipo_contrato" => $_POST['tipo_contrato'],		
		"pago_planificado" => $_POST['pago_planificado'],
	];	

	$result = $insMainModel->getNomina($datos);
	
	$arreglo = array();
	$data = array();
	
	while($row = $result->fetch_assoc()){				
		$data[] = array( 
			"nomina_id"=>$row['nomina_id'],
			"contrato"=>$row['contrato'],
			"empresa"=>$row['empresa'],
			"fecha_inicio"=>$row['fecha_inicio'],
			"fecha_fin"=>$row['fecha_fin'],
			"importe"=>$row['importe'],
			"notas"=>$row['notas']		
		);		
	}
	
	$arreglo = array(
		"echo" => 1,
		"totalrecords" => count($data),
		"totaldisplayrecords" => count($data),
		"data" => $data
	);

	echo json_encode($arreglo);