<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$datos = [
		"colaboradores_id" => $_POST['colaboradores_id'],	
	];	

	$result = $insMainModel->getAsistenciaConsulta($datos);
	
	$arreglo = array();
	$data = array();
	
	while($row = $result->fetch_assoc()){				
		$data[] = array( 
			"asistencia_id"=>$row['asistencia_id'],
			"colaboradores_id"=>$row['colaboradores_id'],	
			"colaborador"=>$row['colaborador'],	
			"fecha"=>$row['fecha'],	
			"estado"=>$row['estado']
		);		
	}
	
	$arreglo = array(
		"echo" => 1,
		"totalrecords" => count($data),
		"totaldisplayrecords" => count($data),
		"data" => $data
	);

	echo json_encode($arreglo);
?>	