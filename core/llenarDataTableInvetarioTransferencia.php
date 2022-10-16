<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();

	$datos = [
		"tipo_producto_id" => $_POST['tipo_producto_id'],
		//"fechai" => $_POST['fechai'],
		//"fechaf" => $_POST['fechaf'],
		"productos_id" => '',
		"bodega" => $_POST['bodega'] 		
	];	
	
	$result = $insMainModel->getTranferenciaProductos($datos);
	
	$arreglo = array();
	$data = array();
	$medidaName = '';
	$entradaH = 0;
	$salidaH = 0;

	
	while($row = $result->fetch_assoc()){		
		
		$result_productos = $insMainModel->getCantidadProductos($row['productos_id']);	
		if($result_productos->num_rows>0){
			while($consulta = $result_productos->fetch_assoc()){
				$id_producto_superior = intval($consulta['id_producto_superior']);
				if($id_producto_superior != 0 || $id_producto_superior != 'null'){
					$datosH = [
						"tipo_producto_id" => "",
						"productos_id" => $id_producto_superior,
						"bodega" => $row['almacen_id']		
					];
					//agregos el producto hijo y las cantidades del padre
					$resultPadre = $insMainModel->getTranferenciaProductos($datosH);
					if($resultPadre->num_rows>0){
						$rowP = $resultPadre->fetch_assoc();

						
							$medidaName = strtolower($row['medida']);
							if($medidaName == "ton"){ // Medida en Toneladas
								$entradaH = $rowP['entrada'] / 2205;
								$salidaH = $rowP['salida'] / 2205;

							}


						$data[] = array( 
							"fecha_registro"=>$row['fecha_registro'],
							"barCode"=>$row['barCode'],
							"producto"=>$row['producto'],
							"medida"=>$row['medida'],
							"movimientos_id"=>$row['movimientos_id'],
							"entrada"=> number_format($entradaH,2),
							"salida"=>number_format($salidaH,2),
							"saldo"=> $saldoH = number_format($entradaH - $salidaH,2),
							"bodega"=>$row['bodega'],
							"id_bodega"=>$row['almacen_id'],
							"productos_id"=>$row['productos_id'],
							"superior"=>$row['id_producto_superior']			
						
						);
					}
				}else{
					$data[] = array( 
						"fecha_registro"=>$row['fecha_registro'],
						"barCode"=>$row['barCode'],
						"producto"=>$row['producto'],
						"medida"=>$row['medida'],
						"movimientos_id"=>$row['movimientos_id'],
						"entrada"=>$row['entrada'],
						"salida"=>$row['salida'],
						"saldo"=>$row['saldo'],
						"bodega"=>$row['bodega'],
						"id_bodega"=>$row['almacen_id'],
						"productos_id"=>$row['productos_id'],
						"superior"=>$row['id_producto_superior']			
					
					);	
		
				}

			}
		}	
	}
	
	$arreglo = array(
		"echo" => 1,
		"totalrecords" => count($data),
		"totaldisplayrecords" => count($data),
		"data" => $data
	);

	echo json_encode($arreglo);