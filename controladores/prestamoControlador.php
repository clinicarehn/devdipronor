<?php
    if($peticionAjax){
        require_once "../modelos/prestamoModelo.php";
    }else{
        require_once "./modelos/prestamoModelo.php";
    }
	
	class prestamoControlador extends prestamoModelo{
		public function agregar_prestamo_controlador(){
			if(!isset($_SESSION['user_sd'])){ 
				session_start(['name'=>'SD']); 
			}

			$colaboradores_id = mainModel::cleanString($_POST['vale_empleado']);
			$fecha = $_POST['fecha'];
			$monto = $_POST['vale'];
			$usuario = $_SESSION['colaborador_id_sd'];
			$estado = 0;//PENDIENTE DE PAGAR
			$fecha_registro = date("Y-m-d H:i:s");	
			
			$datos = [
				"colaboradores_id" => $colaboradores_id,
				"fecha" => $fecha,
				"monto" => $monto,
				"usuario" => $usuario,
				"estado" => $estado,
				"fecha_registro" => $fecha_registro,								
			];
			
			$resultVarios = prestamoModelo::valid_prestamo_modelo($colaboradores_id);
			
			if($resultVarios->num_rows==0){
				$query = prestamoModelo::agregar_prestamo_modelo($datos);
				
				if($query){
					$alert = [
						"alert" => "clear",
						"title" => "Registro almacenado",
						"text" => "El registro se ha almacenado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formVales",
						"id" => "proceso_vales",
						"valor" => "Registro",	
						"funcion" => "listar_asistencia();",
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
					"title" => "Empleado cuenta con prestamo pendiente de pago",
					"text" => "Lo sentimos este empleado ya tiene un préstamo pendiente de pago, no se puede ingresar otro.",
					"type" => "error",	
					"btn-class" => "btn-danger",						
				];				
			}
			
			return mainModel::sweetAlert($alert);
		}
		
		public function edit_prestamo_controlador(){
			$prestamo_id = $_POST['prestamo_id'];
			$colaboradores_id = mainModel::cleanString($_POST['vale_empleado']);
			$fecha = $_POST['fecha'];
			$monto = $_POST['vale'];
			$usuario = $_SESSION['colaborador_id_sd'];
			$fecha_registro = date("Y-m-d H:i:s");			
			
			$datos = [
				"prestamo_id" => $prestamo_id,
				"colaboradores_id" => $colaboradores_id,
				"fecha" => $fecha,
				"monto" => $monto,
				"usuario" => $usuario,
				"estado" => $estado,
				"fecha_registro" => $fecha_registro,								
			];	

			$query = prestamoModelo::edit_prestamo_modelo($datos);
			
			if($query){				
				$alert = [
					"alert" => "edit",
					"title" => "Registro modificado",
					"text" => "El registro se ha modificado correctamente",
					"type" => "success",
					"btn-class" => "btn-primary",
					"btn-text" => "¡Bien Hecho!",
					"form" => "formVales",	
					"id" => "proceso_vales",
					"valor" => "Editar",
					"funcion" => "",
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
			
			return mainModel::sweetAlert($alert);
		}
		
		public function delete_prestamo_controlador(){
			$prestamo_id = $_POST['prestamo_id'];
			
			$query = prestamoModelo::delete_prestamo_modelo($prestamo_id);
							
			if($query){
				$alert = [
					"alert" => "clear",
					"title" => "Registro eliminado",
					"text" => "El registro se ha eliminado correctamente",
					"type" => "success",
					"btn-class" => "btn-primary",
					"btn-text" => "¡Bien Hecho!",
					"form" => "formVales",	
					"id" => "proceso_vales",
					"valor" => "Eliminar",
					"funcion" => "listar_asistencia();",
					"modal" => "modal_registrar_vales",
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
		
			return mainModel::sweetAlert($alert);			
		}
	}
?>	