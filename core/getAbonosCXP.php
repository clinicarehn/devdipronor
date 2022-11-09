<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$factura_id = $_POST['factura_id'];

    $result = $insMainModel->abonos_cxp_proveedor($factura_id);
    $total_abono = 0;
	$arreglo = array();
	$data = array();
		
	while($row = $result->fetch_assoc()){
		
        $total_abono += $row['efectivo'];

		$data[] = array( 
			"facturas_id"=>$row['pagoscompras_id'],
			"fecha"=>$row['fecha'],
			"abono"=>number_format($row['efectivo'],2),						
			"cliente"=> $row['nombre'],
			"descripcion"=>'',
			"tipo_pago"=> $row['tipoPago'],
			"importe"=>number_format($row['importe'],2),
            "total"=> number_format($total_abono ,2)
		);		
	}
	
	$arreglo = array(
		"echo" => 1,
		"totalrecords" => count($data),
		"totaldisplayrecords" => count($data),
		"data" => $data
	);

	echo json_encode($arreglo);