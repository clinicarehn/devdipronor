<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";

	$insMainModel = new mainModel();

	$datos = [
		"fechai" => $_POST['fechai'],
		"fechaf" => $_POST['fechaf'],		
	];		

		$query = "SELECT 
		IFNULL(SUM(fd.cantidad*fd.precio),0.00) AS 'subtotal', 
		IFNULL(SUM(fd.isv_valor),0.00) AS 'impuesto', 
		IFNULL(SUM(fd.descuento),0.00) AS 'descuento', 
		IFNULL(SUM((fd.cantidad*fd.precio) - (fd.cantidad*p.precio_compra) - fd.isv_valor - fd.descuento),0.00) AS 'ganancia',
		IFNULL(SUM((fd.cantidad*fd.precio) + fd.isv_valor - fd.descuento),0.00) AS 'total'
		FROM facturas AS f
		INNER JOIN facturas_detalles AS fd
		INNER JOIN productos AS p ON fd.productos_id = p.productos_id
		ON f.facturas_id = fd.facturas_id
		WHERE CAST(f.fecha AS DATE) BETWEEN '".$datos['fechai']."' AND '".$datos['fechaf']."'
		AND f.estado IN (1,2,3) 
		ORDER BY f.fecha DESC";

    $result = $insMainModel->consulta_total_gastos($query);

    $row = $result->fetch_assoc();

    echo json_encode($row);

	?>