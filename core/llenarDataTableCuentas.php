<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$result = $insMainModel->getCuentasContabilidad();
	
	$arreglo = array();
	$importe_venta = 0.00;
	$neto = 0.00;
	$saldo_anterior = 0.00;
	$saldo_cierre = 0.00;
	
	$data = array();
	
	while($row = $result->fetch_assoc()){
	   $cuentas_id = $row['cuentas_id'];
	   
	   $datos = [
			"fechai" => $_POST['fechai'],
			"fechaf" => $_POST['fechaf'],
			"cuentas_id" => $cuentas_id	
	   ];		

	   $result_ingresos = $insMainModel->getCuentasIngresos($datos);
	   $row_ingresos = $result_ingresos->fetch_assoc();
	   $ingreso = $row_ingresos['ingresos'];

	   //OBTENER EL AÑO DE LA FECHA ANTERIOR
	   $fecha_anterior = date("Y-m-d", strtotime ( '-1 month' , strtotime ( $_POST['fechai'] )));
	   $año_anterior = date("Y", strtotime($fecha_anterior));
	   $mes_anterior = date("m", strtotime($fecha_anterior));

	   /*#######################################################################################*/
	   $result_saldo_anterior = $insMainModel->getSaldoMovimientosCuentasSaldoAnterior($cuentas_id, $año_anterior, $mes_anterior);
	   
	   $saldo_anterior = 0.00;
	   $saldo_cierre = 0.00;

	   if($result_saldo_anterior->num_rows>0){
			$row_saldo_anterior = $result_saldo_anterior->fetch_assoc();
			$saldo_anterior = $row_saldo_anterior['saldo'];
	   }else{
			//CONSULTAMOS EL ULTIMO SALDO DE LA CUENTA
			$result__ultimo_saldo = $insMainModel->getSaldoMovimientosCuentasUltimoSaldo($cuentas_id);

			if($result__ultimo_saldo->num_rows>0){
				$row_ultimo_saldo = $result__ultimo_saldo->fetch_assoc();
				$saldo_anterior = $row_ultimo_saldo['saldo'];
				$fecha_registro = $row_ultimo_saldo['fecha_registro'];

				//CONSULTAMOS LOS REGISTROS ANTERIORES A LA FECHA DEL
				$result_ultimo_fecha_valores = $insMainModel->getSaldoMovimientosCuentasUltimaFecha($cuentas_id, $fecha_registro);

				if($result_ultimo_fecha_valores->num_rows>0){
					$saldo_anterior = $row_ultimo_saldo['saldo'];
				}else{
					$saldo_anterior = 0;
				}				
			}
	   }

	   $result_egresos = $insMainModel->getCuentaEgresos($datos);
	   $row_egresos = $result_egresos->fetch_assoc();
	   $egreso = $row_egresos['egresos'];	   

	   $saldo_cierre = $ingreso - $egreso;

	   $neto = $saldo_anterior + $saldo_cierre;
	   
	   $data[] = array( 
		  "cuentas_id"=>$cuentas_id,
		  "codigo"=>$row['codigo'],
		  "nombre"=>$row['nombre'],
		  "saldo_anterior"=> number_format($saldo_anterior),		  
		  "ingreso"=> number_format($ingreso),	
		  "egreso"=> number_format($egreso),
		  "saldo_cierre"=> number_format($saldo_cierre),		  
		  "neto"=> number_format($neto)		  
	  );	
	  	
	}
	
	$arreglo = array(
		"echo" => 1,
		"totalrecords" => count($data),
		"totaldisplayrecords" => count($data),
		"data" => $data
	);

	echo json_encode($arreglo);