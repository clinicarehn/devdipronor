<?php
    if($peticionAjax){
        require_once "../core/mainModel.php";
    }else{
        require_once "./core/mainModel.php";	
    }
	
	class pagoCompraModelo extends mainModel{
		protected function agregar_pago_compras_base($res){
			$saldo_credito = 0;
			$nuevo_saldo = 0;
			$compras_id = $res['compras_id'];
			$importe = $res['importe'];
			$abono = $res['abono'];
			$tipo_pago_id = $res['tipo_pago'];
			$metodo_pago = $res['metodo_pago'];
			$banco_id = $res['banco_id'];
			$referencia_pago1 = $res["referencia_pago1"];
			$referencia_pago2 = $res["referencia_pago2"];
			$referencia_pago3 = $res["referencia_pago3"];
			$empresa = $res['empresa'];
			$fecha = $res['fecha'];
			$estado = $res["estado"];
			$fecha_registro = $res["fecha_registro"];	
			$cambio = $res['cambio'];
			$usuario = $res['usuario'];
			$efectivo = $res['efectivo'];
			$tarjeta = 	$res['tarjeta'];	
			
			isset($res["colaboradores_id"]) ? $colaboradores_id = $res["colaboradores_id"] : $colaboradores_id = '';

			if($res['tipo_pago'] == 2){

				//consultamos a la tabla cuenta x pagar
				$get_cxc_proveedor = pagoCompraModelo::consultar_compra_cuentas_por_pagar($compras_id);
				
				
				if($get_cxc_proveedor->num_rows > 0){
					$rec = $get_cxc_proveedor->fetch_assoc();
					$saldo_credito = $rec['saldo'];
					
				}else{
					$alert = [
						"alert" => "simple",
						"title" => "Ocurrio un error inesperado",
						"text" => "No hemos podido procesar su solicitud",
						"type" => "error",
						"btn-class" => "btn-danger",					
					];
				}

				//validar que no se hagan mas abonos que el importe
				if($abono <= $saldo_credito ){
					//update tabla cobrar cliente
					if($abono == $saldo_credito){
						//actualizamos el estado a pagado (2)
						$query = pagoCompraModelo::update_status_compras_cuentas_por_pagar($compras_id);

						//ACTUALIZAMOS EL ESTADO DE LA FACTURA
						pagoCompraModelo::update_status_compras($compras_id);
					}else{
						$nuevo_saldo = $saldo_credito - $abono;
						$query = pagoCompraModelo::update_status_compras_cuentas_por_pagar($compras_id,1,$nuevo_saldo);

					}
				}else{
					$alert = [
						"alert" => "simple",
						"title" => "El abono es mayor al importe",
						"text" => "No hemos podido procesar su solicitud",
						"type" => "error",
						"btn-class" => "btn-danger",					
					];

					return $alert;
				}

				$datos = [
					"compras_id" => $compras_id,
					"fecha" => $fecha,
					"importe" => $tipo_pago_id == 2 ? $abono : $importe,
					"cambio" => $cambio,
					"usuario" => $usuario,
					"estado" => $estado,
					"fecha_registro" => $fecha_registro,
					"empresa" => $empresa,
					"tipo_pago" => $tipo_pago_id,
					"efectivo" => $efectivo,
					"tarjeta" => $tarjeta,
					"banco_id" => $banco_id,
					"referencia_pago1" => $referencia_pago1,
					"referencia_pago2" => $referencia_pago2,
					"referencia_pago3" => $referencia_pago3,
				];

				$query = pagoCompraModelo::agregar_pago_compras_modelo($datos);
				
				if($query){
					//ACTUALIZAMOS EL DETALLE DEL PAGO
					 $consulta_pago = pagoCompraModelo::consultar_codigo_pago_modelo($compras_id)->fetch_assoc();
					
					$pagoscompras_id = $consulta_pago['pagoscompras_id'];
												
					$datos_pago_detalle = [
						"pagoscompras_id" => $pagoscompras_id,
						"tipo_pago_id" => $metodo_pago,
						"banco_id" => $banco_id,
						"efectivo" => $abono,
						"descripcion1" => $referencia_pago1,
						"descripcion2" => $referencia_pago2,
						"descripcion3" => $referencia_pago3,			
					];	

					
						pagoCompraModelo::agregar_pago_detalles_compras_modelo($datos_pago_detalle);
					
					
					/**###########################################################################################################*/
					//CONSULTAMOS EL SUBTOTAL, ISV, DESCUENTO, NC Y TOTAL EN LOS COMPRAS DETALLES
					$resultDetallesCompras = pagoCompraModelo::consulta_detalle_compras($compras_id);

					$total_despues_isvMontoTipoPago = 0;
					$isv_neto = 0;
					$descuentos = 0;
					$total_antes_isvMontoTipoPago = 0;
					$nc = 0;

					while($dataDetallesCompra = $resultDetallesCompras->fetch_assoc()){
						$total_despues_isvMontoTipoPago = $dataDetallesCompra['monto'];
						$isv_neto = $dataDetallesCompra['isv_valor'];
						$descuentos = $dataDetallesCompra['descuento'];
						$total_antes_isvMontoTipoPago = ($total_despues_isvMontoTipoPago - $isv_neto) - $descuentos;
					}
					
					//CONSULTAMOS LA CUENTA_ID SEGUN EL TIPO DE PAGO
					$consulta_fecha_compra = pagoCompraModelo::consultar_cuenta_contabilidad_tipo_pago($metodo_pago)->fetch_assoc();
					$cuentas_id = $consulta_fecha_compra['cuentas_id'];

					//CONSULTAMOS EL PROVEEDOR
					$consulta_fecha_compra = pagoCompraModelo::consultar_proveedor_id_compra($compras_id)->fetch_assoc();
					$proveedores_id = $consulta_fecha_compra['proveedores_id'];	
					$factura = $consulta_fecha_compra['factura'];				
					$tipo_egreso = 1;//COMPRA
					$observacion = "Egresos por compras";
					$egresos_id = mainModel::correlativo("egresos_id", "egresos");

					//AGREGAMOS LOS EGRESOS DE LA COMPRA
					$datosEgresos = [
						"proveedores_id" => $proveedores_id,
						"cuentas_id" => $cuentas_id,
						"empresa_id" => $empresa,
						"tipo_egreso" => $tipo_egreso,
						"fecha" => $fecha,
						"factura" => $factura,
						"subtotal" => $total_antes_isvMontoTipoPago,
						"isv" => $isv_neto,
						"descuento" => $descuentos,
						"nc" => $nc,
						"total" => $total_despues_isvMontoTipoPago,
						"observacion" => $observacion,
						"estado" => $estado,
						"fecha_registro" => $fecha_registro,						
						"colaboradores_id" => $colaboradores_id,
						"egresos_id" => $egresos_id,
					];

					//AGREGAMOS LOS EGRESOS
					$result_valid_egresos = pagoCompraModelo::valid_egresos_cuentas_modelo($datosEgresos);
			
					if($result_valid_egresos->num_rows==0 ){
						pagoCompraModelo::agregar_egresos_contabilidad_modelo($datosEgresos);

						//CONSULTAMOS EL SALDO DISPONIBLE PARA LA CUENTA
						$consulta_ingresos_contabilidad = pagoCompraModelo::consultar_saldo_movimientos_cuentas_contabilidad($cuentas_id)->fetch_assoc();
						$saldo_consulta = $consulta_ingresos_contabilidad['saldo'];	
						$ingreso = 0;
						$egreso = $total_despues_isvMontoTipoPago;
						$saldo = $saldo_consulta - $egreso;
						
						//AGREGAMOS LOS MOVIMIENTOS DE LA CUENTA
						$datos_movimientos = [
							"cuentas_id" => $cuentas_id,
							"empresa_id" => $empresa,
							"fecha" => $fecha,
							"ingreso" => $ingreso,
							"egreso" => $egreso,
							"saldo" => $saldo,
							"colaboradores_id" => $colaboradores_id,
							"fecha_registro" => $fecha_registro,				
						];
						
						pagoCompraModelo::agregar_movimientos_contabilidad_modelo($datos_movimientos);
					}					
					/**###########################################################################################################*/

					$alert = [
						"alert" => "clear_pay",
						"title" => "Registro almacenado",
						"text" => "El registro se ha almacenado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formEfectivoPurchase",
						"id" => "proceso_pagosPurchase",
						"valor" => "Registro",	
						"funcion" => "getBancoPurchase();listar_cuentas_por_pagar_proveedores();",
						"modal" => "modal_pagosPurchase",
					];
				}else{
					$alert = [
						"alert" => "simple",
						"title" => "Ocurrio un error inesperado",
						"text" => "No hemos podido procesar su solicitud",
						"type" => "error",
						"btn-class" => "btn-danger",					
					];				
				}					
						
				
			}else{
					$datos = [
						"compras_id" => $compras_id,
						"tipo_pago_id" => $tipo_pago_id,
						"fecha" => $fecha,
						"importe" => $importe,
						"cambio" => $cambio,
						"usuario" => $usuario,
						"estado" => $estado,
						"fecha_registro" => $fecha_registro,
						"empresa" => $empresa,
						"tipo_pago" => $tipo_pago_id,
						"efectivo" => $efectivo,
						"tarjeta" => $tarjeta,
						"banco_id" => $banco_id,
						"referencia_pago1" => $referencia_pago1,
						"referencia_pago2" => $referencia_pago2,
						"referencia_pago3" => $referencia_pago3,
					];

					$result_valid_pagos_compras = pagoCompraModelo::valid_pagos_compras($compras_id);
			
				    $query = pagoCompraModelo::agregar_pago_compras_modelo($datos);
					
					//ACTUALIZAMOS EL DETALLE DEL PAGO
					$consulta_pago = pagoCompraModelo::consultar_codigo_pago_modelo($compras_id)->fetch_assoc();
					$pagoscompras_id = $consulta_pago['pagoscompras_id'];
												
					$datos_pago_detalle = [
						"pagoscompras_id" => $pagoscompras_id,
						"tipo_pago_id" => $metodo_pago,
						"banco_id" => $banco_id,
						"efectivo" => $importe,
						"descripcion1" => $referencia_pago1,
						"descripcion2" => $referencia_pago2,
						"descripcion3" => $referencia_pago3,			
					];	

					$result_valid_pagos_detalles_compras = pagoCompraModelo::valid_pagos_detalles_compras($pagoscompras_id, $metodo_pago);
					
					//VALIDAMOS QUE NO EXISTA EL DETALLE DEL PAGO, DE NO EXISTIR SE ALMACENA EL DETALLE DEL PAGO
					if($result_valid_pagos_detalles_compras->num_rows==0){
						pagoCompraModelo::agregar_pago_detalles_compras_modelo($datos_pago_detalle);
					}					
					
					//ACTUALIZAMOS EL ESTADO DE LA FACTURA
					pagoCompraModelo::update_status_compras($compras_id);
					
					// //VERIFICAMOS SI ES UNA CUENTA POR COBRAR, DE SERLO ACTUALIZAMOS EL ESTADO DEL PAGO PARA LA CUENTA POR COBRAR
					// $result_cxp_clientes = pagoCompraModelo::consultar_compra_cuentas_por_pagar($compras_id);
					
					// if($result_cxp_clientes->num_rows>0){
					// 	pagoCompraModelo::update_status_compras_cuentas_por_pagar($compras_id);
					// }
					
					/**###########################################################################################################*/
					//CONSULTAMOS EL SUBTOTAL, ISV, DESCUENTO, NC Y TOTAL EN LOS COMPRAS DETALLES
					$resultDetallesCompras = pagoCompraModelo::consulta_detalle_compras($compras_id);

					$total_despues_isvMontoTipoPago = 0;
					$isv_neto = 0;
					$descuentos = 0;
					$total_antes_isvMontoTipoPago = 0;
					$nc = 0;

					while($dataDetallesCompra = $resultDetallesCompras->fetch_assoc()){
						$total_despues_isvMontoTipoPago = $dataDetallesCompra['monto'];
						$isv_neto = $dataDetallesCompra['isv_valor'];
						$descuentos = $dataDetallesCompra['descuento'];
						$total_antes_isvMontoTipoPago = ($total_despues_isvMontoTipoPago - $isv_neto) - $descuentos;
					}
					
					//CONSULTAMOS LA CUENTA_ID SEGUN EL TIPO DE PAGO
					$consulta_fecha_compra = pagoCompraModelo::consultar_cuenta_contabilidad_tipo_pago($metodo_pago)->fetch_assoc();
					$cuentas_id = $consulta_fecha_compra['cuentas_id'];

					//CONSULTAMOS EL PROVEEDOR
					$consulta_fecha_compra = pagoCompraModelo::consultar_proveedor_id_compra($compras_id)->fetch_assoc();
					$proveedores_id = $consulta_fecha_compra['proveedores_id'];	
					$factura = $consulta_fecha_compra['factura'];				
					$tipo_egreso = 1;//COMPRA
					$observacion = "Egresos por compras";
					$egresos_id = mainModel::correlativo("egresos_id", "egresos");

					//AGREGAMOS LOS EGRESOS DE LA COMPRA
					$datosEgresos = [
						"proveedores_id" => $proveedores_id,
						"cuentas_id" => $cuentas_id,
						"empresa_id" => $empresa,
						"tipo_egreso" => $tipo_egreso,
						"fecha" => $fecha,
						"factura" => $factura,
						"subtotal" => $total_antes_isvMontoTipoPago,
						"isv" => $isv_neto,
						"descuento" => $descuentos,
						"nc" => $nc,
						"total" => $total_despues_isvMontoTipoPago,
						"observacion" => $observacion,
						"estado" => $estado,
						"fecha_registro" => $fecha_registro,						
						"colaboradores_id" => $colaboradores_id,
						"egresos_id" => $egresos_id,
					];

					//AGREGAMOS LOS EGRESOS
					$result_valid_egresos = pagoCompraModelo::valid_egresos_cuentas_modelo($datosEgresos);
			
					if($result_valid_egresos->num_rows==0 ){
						pagoCompraModelo::agregar_egresos_contabilidad_modelo($datosEgresos);

						//CONSULTAMOS EL SALDO DISPONIBLE PARA LA CUENTA
						$consulta_ingresos_contabilidad = pagoCompraModelo::consultar_saldo_movimientos_cuentas_contabilidad($cuentas_id)->fetch_assoc();
						$saldo_consulta = $consulta_ingresos_contabilidad['saldo'];	
						$ingreso = 0;
						$egreso = $total_despues_isvMontoTipoPago;
						$saldo = $saldo_consulta - $egreso;
						
						//AGREGAMOS LOS MOVIMIENTOS DE LA CUENTA
						$datos_movimientos = [
							"cuentas_id" => $cuentas_id,
							"empresa_id" => $empresa,
							"fecha" => $fecha,
							"ingreso" => $ingreso,
							"egreso" => $egreso,
							"saldo" => $saldo,
							"colaboradores_id" => $colaboradores_id,
							"fecha_registro" => $fecha_registro,				
						];
						
						pagoCompraModelo::agregar_movimientos_contabilidad_modelo($datos_movimientos);
					}					
					/**###########################################################################################################*/

					$alert = [
						"alert" => "clear_pay",
						"title" => "Registro almacenado",
						"text" => "El registro se ha almacenado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formEfectivoPurchase",
						"id" => "proceso_pagosPurchase",
						"valor" => "Registro",	
						"funcion" => "getBancoPurchase();listar_cuentas_por_pagar_proveedores();printPurchase(".$compras_id.");",
						"modal" => "modal_pagosPurchase",
					];		
						
					
			}

			return $alert;

		}
		protected function agregar_pago_compras_modelo($datos){
			$pagoscompras_id = mainModel::correlativo("pagoscompras_id", " pagoscompras");
			$insert = "INSERT INTO pagoscompras 
				VALUES('$pagoscompras_id','".$datos['compras_id']."','".$datos['tipo_pago']."','".$datos['fecha']."',
				'".$datos['importe']."','".$datos['efectivo']."','".$datos['cambio']."','".$datos['tarjeta']."',
				'".$datos['usuario']."','".$datos['estado']."','".$datos['empresa']."','".$datos['fecha_registro']."')";
				
			$result = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
		
			return $result;		
		}
		
		protected function agregar_pago_detalles_compras_modelo($datos){			
			$pagoscompras_detalles_id = mainModel::correlativo("pagoscompras_detalles_id", "pagoscompras_detalles");
			$insert = "INSERT INTO pagoscompras_detalles 
				VALUES('$pagoscompras_detalles_id','".$datos['pagoscompras_id']."','".$datos['tipo_pago_id']."','".$datos['banco_id']."','".$datos['efectivo']."','".$datos['descripcion1']."','".$datos['descripcion2']."','".$datos['descripcion3']."')";
				
			$result = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
			
			return $result;			
		}
		
		protected function agregar_movimientos_contabilidad_modelo($datos){
			$movimientos_cuentas_id = mainModel::correlativo("movimientos_cuentas_id", "movimientos_cuentas");
			$insert = "INSERT INTO movimientos_cuentas VALUES('$movimientos_cuentas_id','".$datos['cuentas_id']."','".$datos['empresa_id']."','".$datos['fecha']."','".$datos['ingreso']."','".$datos['egreso']."','".$datos['saldo']."','".$datos['colaboradores_id']."','".$datos['fecha_registro']."')";
						
			$sql = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
			
			return $sql;			
		}

		protected function agregar_egresos_contabilidad_modelo($datos){
			$egresos_id = mainModel::correlativo("egresos_id", "egresos");
			$insert = "INSERT INTO egresos VALUES('".$egresos_id ."','".$datos['cuentas_id']."','".$datos['proveedores_id']."','".$datos['empresa_id']."','".$datos['tipo_egreso']."','".$datos['fecha']."','".$datos['factura']."','".$datos['subtotal']."','".$datos['descuento']."','".$datos['nc']."','".$datos['isv']."','".$datos['total']."','".$datos['observacion']."','".$datos['estado']."','".$datos['colaboradores_id']."','".$datos['fecha_registro']."')";
	
			$sql = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
			
			return $sql;			
		}

		protected function cancelar_pago_modelo($pagoscompras_id){
			$estado = 2;//FACTURA CANCELADA
			$update = "UPDATE pagoscompras
				SET
					estado = '$estado'
				WHERE pagoscompras_id = '$pagoscompras_id'";
			
			$result = mainModel::connection()->query($update) or die(mainModel::connection()->error);
			
			return $result;				
		}
		
		protected function consultar_codigo_pago_modelo($compras_id){
			$query = "SELECT pagoscompras_id
				FROM pagoscompras
				WHERE compras_id = '$compras_id'";

			$result = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $result;			
		}
		
		protected function update_status_compras($compras_id){
			$estado = 2;//FACTURA PAGADA
			$update = "UPDATE compras
				SET
					estado = '$estado'
				WHERE compras_id = '$compras_id'";
			
			$result = mainModel::connection()->query($update) or die(mainModel::connection()->error);
			
			return $result;					
		}
		
		protected function update_status_compras_cuentas_por_pagar($compras_id,$estado = 2,$importe = ''){
			if($importe != ''){
				$importe = ', saldo = '.$importe;
			}

			$update = "UPDATE pagar_proveedores
				SET
					estado = '$estado'
					$importe
				WHERE compras_id = '$compras_id'";
			
			$result = mainModel::connection()->query($update) or die(mainModel::connection()->error);
			
			return $result;					
		}		
		
		protected function consultar_compra_cuentas_por_pagar($compras_id){
			$query = "SELECT *
				FROM pagar_proveedores
				WHERE compras_id = '$compras_id'";

			$result = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $result;				
		}	

		protected function consultar_compra_fecha($compras_id){
			$query = "SELECT fecha
				FROM compras
				WHERE compras_id = '$compras_id'";

			$result = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $result;				
		}

		protected function valid_pagos_compras($compras_id){
			$query = "SELECT pagoscompras_id
				FROM pagoscompras
				WHERE compras_id = '$compras_id'";

			$result = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $result;			
		}
		
		protected function valid_pagos_detalles_compras($pagos_id, $tipo_pago){
			$query = "SELECT pagoscompras_detalles_id
				FROM pagoscompras_detalles
				WHERE pagoscompras_id = '$pagos_id' AND tipo_pago_id = '$tipo_pago'";
			$result = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $result;				
		}	
		
		protected function valid_egresos_cuentas_modelo($datos){
			$query = "SELECT egresos_id FROM egresos WHERE factura = '".$datos['factura']."' AND proveedores_id = '".$datos['proveedores_id']."'";

			$sql = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $sql;			
		}

		protected function consultar_cuenta_contabilidad_tipo_pago($tipo_pago_id){
			$query = "SELECT nombre, cuentas_id
				FROM tipo_pago
				WHERE tipo_pago_id = '$tipo_pago_id'";

			$result = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $result;				
		}	

		protected function consultar_proveedor_id_compra($compras_id){
			$query = "SELECT proveedores_id, number AS 'factura'
				FROM compras
				WHERE compras_id = '$compras_id'";

			$result = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $result;				
		}			

		protected function consultar_saldo_movimientos_cuentas_contabilidad($cuentas_id){
			$query = "SELECT ingreso, egreso, saldo
				FROM movimientos_cuentas
				WHERE cuentas_id = '$cuentas_id'
				ORDER BY movimientos_cuentas_id DESC LIMIT 1";
			
			$sql = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $sql;				
		}	
		
		protected function consulta_detalle_compras($compras_id){
			$result = mainModel::getMontoTipoPagoCompras($compras_id);
			
			return $result;			
		}		
	}