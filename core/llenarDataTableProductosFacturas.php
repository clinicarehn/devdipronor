<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();

	$bodega = '';

	if(isset($_POST['bodega'])){
		$bodega = $_POST['bodega'];
	}

	$datos = [
		"bodega" => $bodega,
		"barcode" => '',	
	];
	
	$result = $insMainModel->getProductosCantidad($datos);
	
	$arreglo = array();
	$data = array();
	
	$entradaH = 0;
	$salidaH = 0;
	
	while($row = $result->fetch_assoc()){	
		$result_productos = $insMainModel->getCantidadProductos($row['productos_id']);	
		if($result_productos->num_rows>0){
			while($consulta = $result_productos->fetch_assoc()){
				if($row['almacen_id'] == 0 || $row['almacen_id'] == null){
					$bodega = "Sin bodega";
				}else{
					$bodega = $row['almacen'];
				}

				$data[] = array( 
					"productos_id"=>$row['productos_id'],
					"barCode"=>$row['barCode'],
					"nombre"=>$row['nombre'],
					"cantidad"=>$row['cantidad'],
					"medida"=>$row['medida'],
					"tipo_producto_id"=>$row['tipo_producto_id'],
					"precio_venta"=>$row['precio_venta'],
					"almacen"=>$bodega,
					"almacen_id"=>$row['almacen_id'],
					"tipo_producto"=>$row['tipo_producto'],
					"impuesto_venta"=>$row['impuesto_venta'],
					"precio_mayoreo"=>$row['precio_mayoreo'],
					"cantidad_mayoreo"=>$row['cantidad_mayoreo'],
					"tipo_producto_nombre"=>$row['tipo_producto_nombre']	
				);
								
				/*$id_producto_superior = intval($consulta['id_producto_superior']);
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
							"productos_id"=>$row['productos_id'],
							"barCode"=>$row['barCode'],
							"nombre"=>$row['nombre'],
							"entrada"=> number_format($entradaH,2),
							"salida"=>number_format($salidaH,2),
							"cantidad"=> $saldoH = number_format($entradaH - $salidaH,2),
							"medida"=>$row['medida'],
							"tipo_producto_id"=>$row['tipo_producto_id'],
							"precio_venta"=>$row['precio_venta'],
							"almacen"=>$bodega,
							"almacen_id"=>$row['almacen_id'],
							"tipo_producto"=>$row['tipo_producto'],
							"impuesto_venta"=>$row['impuesto_venta'],
							"precio_mayoreo"=>$row['precio_mayoreo'],
							"cantidad_mayoreo"=>$row['cantidad_mayoreo'],
							"tipo_producto_nombre"=>$row['tipo_producto_nombre']
						);	
					}
				}else{
		
					$data[] = array( 
						"productos_id"=>$row['productos_id'],
						"barCode"=>$row['barCode'],
						"nombre"=>$row['nombre'],
						"cantidad"=>$row['cantidad'],
						"medida"=>$row['medida'],
						"tipo_producto_id"=>$row['tipo_producto_id'],
						"precio_venta"=>$row['precio_venta'],
						"almacen"=>$bodega,
						"almacen_id"=>$row['almacen_id'],
						"tipo_producto"=>$row['tipo_producto'],
						"impuesto_venta"=>$row['impuesto_venta'],
						"precio_mayoreo"=>$row['precio_mayoreo'],
						"cantidad_mayoreo"=>$row['cantidad_mayoreo'],
						"tipo_producto_nombre"=>$row['tipo_producto_nombre']	
					);		
				}*/

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