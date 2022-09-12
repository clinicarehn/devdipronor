<?php
    if($peticionAjax){
        require_once "../modelos/tipoCuentaModelo.php";
    }else{
        require_once "./modelos/tipoCuentaModelo.php";
    }
	
	class tipoCuentaControlador extends tipoCuentaModelo{
		public function agregar_tipo_cuenta_controlador(){
			$tipo_cuenta = mainModel::cleanString($_POST['tipo_cuenta']);
			$estado = 1;

			$fecha_registro = date("Y-m-d H:i:s");	
			
			$datos = [
				"tipo_cuenta" => $tipo_cuenta,
				"estado" => $estado,
				"fecha_registro" => $fecha_registro,				
			];
			
			$resultTipoCuentaModelo = tipoCuentaModelo::valid_tipo_cuenta_modelo($tipo_cuenta);
			
			if($resultTipoCuentaModelo->num_rows==0){
				$query = tipoCuentaModelo::agregar_tipo_cuenta_modelo($datos);
				
				if($query){
					$alert = [
						"alert" => "clear",
						"title" => "Registro almacenado",
						"text" => "El registro se ha almacenado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formConfTipoCuenta",
						"id" => "pro_tipoCuenta",
						"valor" => "Registro",	
						"funcion" => "listar_tipo_cuenta_contabilidad();",
						"modal" => "",
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
				$alert = [
					"alert" => "simple",
					"title" => "Resgistro ya existe",
					"text" => "Lo sentimos este registro ya existe",
					"type" => "error",	
					"btn-class" => "btn-danger",						
				];				
			}
			
			return mainModel::sweetAlert($alert);
		}
		
		public function edit_tipo_cuenta_controlador(){
			$tipo_cuenta_id = $_POST['tipo_cuenta_id'];
			$tipo_cuenta = mainModel::cleanString($_POST['tipo_cuenta']);

			if (isset($_POST['confTipoCuenta_activo'])){
				$estado = $_POST['confTipoCuenta_activo'];
			}else{
				$estado = 2;
			}

			$fecha_registro = date("Y-m-d H:i:s");	
			
			$datos = [
				"tipo_cuenta_id" => $tipo_cuenta_id,
				"tipo_cuenta" => $tipo_cuenta,
				"estado" => $estado,
				"fecha_registro" => $fecha_registro,				
			];

			$resultTipoCuentaModelo = tipoCuentaModelo::valid_tipo_cuenta_modelo($tipo_cuenta);

			if($resultTipoCuentaModelo->num_rows==0){
				$query = tipoCuentaModelo::edit_tipo_cuenta_modelo($datos);
			
				if($query){				
					$alert = [
						"alert" => "edit",
						"title" => "Registro modificado",
						"text" => "El registro se ha modificado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formConfTipoCuenta",	
						"id" => "pro_tipoCuenta",
						"valor" => "Editar",
						"funcion" => "listar_tipo_cuenta_contabilidad();",
						"modal" => "",
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
				$alert = [
					"alert" => "simple",
					"title" => "Resgistro ya existe",
					"text" => "Lo sentimos este registro ya existe",
					"type" => "error",	
					"btn-class" => "btn-danger",						
				];					
			}		
			
			return mainModel::sweetAlert($alert);
		}
		
		public function delete_tipo_cuenta_controlador(){
			$tipo_cuenta_id = $_POST['tipo_cuenta_id'];
			
			$result_valid_pagos_on_pagos_modelo = tipoCuentaModelo::valid_tipo_pago_cuenta_modelo($tipo_cuenta_id);
			
			if($result_valid_pagos_on_pagos_modelo->num_rows==0 ){
				$query = tipoCuentaModelo::delete_tipo_cuenta_modelo($tipo_cuenta_id);
								
				if($query){
					$alert = [
						"alert" => "clear",
						"title" => "Registro eliminado",
						"text" => "El registro se ha eliminado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formConfTipoCuenta",	
						"id" => "pro_tipoCuenta",
						"valor" => "Eliminar",
						"funcion" => "listar_tipo_cuenta_contabilidad();",
						"modal" => "modalConfTipoPago",
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
				$alert = [
					"alert" => "simple",
					"title" => "Este registro cuenta con información almacenada",
					"text" => "No se puede eliminar este registro",
					"type" => "error",	
					"btn-class" => "btn-danger",						
				];				
			}
			
			return mainModel::sweetAlert($alert);			
		}
	}