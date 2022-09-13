<?php	
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	if(!isset($_SESSION['user_sd'])){ 
		session_start(['name'=>'SD']); 
	}

	$empresa_id = $_SESSION['empresa_id_sd'];
	$insMainModel = new mainModel();
	
	
	$result = $insMainModel->valid_secuencia_facturacionSAR($empresa_id);
	
	if($result->num_rows>0){
		$resultNumero = $insMainModel->getTotalFacturasDisponiblesDB($empresa_id);
	
		$numeroAnterior = 0;
		$numeroMaximo = 0;
		$contador = 0;
		$fecha_limite = "";
	
		if($resultNumero->num_rows>0){
			$consultaNumero = $resultNumero->fetch_assoc();
			$numeroAnterior = $consultaNumero['numero'];
	
			if($numeroAnterior > 1){
				$numeroAnterior = $numeroAnterior - 1;
			}
			$resultNumeroMaximo = $insMainModel->getNumeroMaximoPermitido($empresa_id);
		
			if($resultNumeroMaximo->num_rows>0){
				$consultaNumeroMaximo = $resultNumeroMaximo->fetch_assoc();
				$numeroMaximo = $consultaNumeroMaximo['numero'];
			}
		
			$facturasPendientes = $numeroMaximo - $numeroAnterior;
		
			//OBTNENER LA FECHA LIMITE DE FACTURACION
			$resultNFechaLimite = $insMainModel->getFechaLimiteFactura($empresa_id);
		
			if($resultNFechaLimite->num_rows>0){
				$consultaFechaLimite = $resultNFechaLimite->fetch_assoc();
				$contador = $consultaFechaLimite['dias_transcurridos'];
				$fecha_limite = $consultaFechaLimite['fecha_limite'];
			}	
			
			$datos = array(
				0 => $facturasPendientes,
				1 => $contador,	
				2 => $fecha_limite,		
			);		
		}
	}else{
		$datos = array(
			0 => "Inactivo",
			1 => "",	
			2 => "",		
		);
	}

	echo json_encode($datos);
