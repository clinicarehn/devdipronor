<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();

	$datos = [
		"tipo_producto_id" => $_POST['tipo_producto_id'],
		"fechai" => $_POST['fechai'],
		"fechaf" => $_POST['fechaf'],
		"bodega" => $_POST['bodega'],
		"producto" => $_POST['producto'],
		"cliente" =>  $_POST['cliente'],

	];	
	
	$result = $insMainModel->getMovimientosProductos($datos);
	
	$arreglo = array();
	$data = array();
	
	while($row = $result->fetch_assoc()){	
		$entrada = 0;
		$salida = 0;
		$saldo = 0;

		if(!empty($row['entrada'])){
			$entrada = $row['entrada'];
		}	

		if(!empty($row['salida'])){
			$salida = $row['salida'];
		}	
		
		if(!empty($row['saldo'])){
			$saldo = $row['saldo'];
		}		

		$data[] = array( 
			"cliente" => $row['cliente'],
			"comentario" => $row['comentario'],
			"movimientos_id"=>$row['movimientos_id'],
			"fecha_registro"=>$row['fecha_registro'],
			"barCode"=>$row['barCode'],
			"producto"=>$row['producto'],
			"medida"=>$row['medida'],
			"documento"=>$row['documento'],
			"entrada"=>$entrada,
			"salida"=>$salida,
			"saldo"=>$saldo,
			"bodega"=>$row['bodega'],
			"id_bodega"=>$row['almacen_id'],
			"productos_id"=>$row['productos_id']						
		);	
	}
	
	$arreglo = array(
		"echo" => 1,
		"totalrecords" => count($data),
		"totaldisplayrecords" => count($data),
		"data" => $data
	);

	echo json_encode($arreglo);