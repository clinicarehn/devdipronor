<?php
    if($peticionAjax){
        require_once "../modelos/nominaModelo.php";
    }else{
        require_once "./modelos/nominaModelo.php";
    }
	
	class nominaControlador extends nominaModelo{
		public function agregar_nomina_controlador(){
			if(!isset($_SESSION['user_sd'])){ 
				session_start(['name'=>'SD']); 
			}
						
			$pago_planificado_id = mainModel::cleanString($_POST['nomina_pago_planificado_id']);
			$empresa_id = mainModel::cleanString($_POST['nomina_empresa_id']);
			$fecha_inicio = mainModel::cleanString($_POST['nomina_fecha_inicio']);						
			$fecha_fin = mainModel::cleanString($_POST['nomina_fecha_fin']);
			$detalle = mainModel::cleanString($_POST['nomina_detale']);
			$importe = mainModel::cleanString($_POST['nomina_importe']);			
			$notas = mainModel::cleanString($_POST['nomina_notas']);
			$usuario = $_SESSION['colaborador_id_sd'];
			$estado = 0;//SIN GENERAR
			$fecha_registro = date("Y-m-d H:i:s");			

			$datos = [
				"pago_planificado_id" => $empresa_id,
				"empresa_id" => $empresa_id,
				"fecha_inicio" => $fecha_inicio,
				"fecha_fin" => $fecha_fin,
				"detalle" => $detalle,
				"importe" => $importe,
				"notas" => $notas,
				"usuario" => $usuario,
				"estado" => $estado,
				"fecha_registro" => $fecha_registro,
			];
			
			$resultNmina = nominaModelo::valid_nomina_modelo($detalle);
			
			if($resultNmina->num_rows==0){
				$query = nominaModelo::agregar_nomina_modelo($datos);
				
				if($query){
					$alert = [
						"alert" => "clear",
						"title" => "Registro almacenado",
						"text" => "El registro se ha almacenado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formNomina",
						"id" => "proceso_nomina",
						"valor" => "Registro",	
						"funcion" => "listar_nominas();",
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

		public function agregar_nomina_detalles_controlador(){
			if(!isset($_SESSION['user_sd'])){ 
				session_start(['name'=>'SD']); 
			}
						
			$nomina_id = $_POST['nomina_id'];
			$colaboradores_id = mainModel::cleanString($_POST['nominad_empleados']);
			$salario = mainModel::cleanString($_POST['nominad_salario']);
			$salario_diario = mainModel::cleanString($_POST['nominad_sueldo_diario']);
			$salario_hora = mainModel::cleanString($_POST['nominad_sueldo_hora']);

			//INGRESOS
			$dias_trabajados = mainModel::cleanString($_POST['nominad_diast']);
			$hrse25 = mainModel::cleanString($_POST['nominad_horas25']);
			$hrse50 = mainModel::cleanString($_POST['nominad_horas50']);			
			$hrse75 = mainModel::cleanString($_POST['nominad_horas75']);
			$hrse100 = mainModel::cleanString($_POST['nominad_horas100']);						
			$retroactivo = mainModel::cleanString($_POST['nominad_retroactivo']);
			$bono = mainModel::cleanString($_POST['nominad_bono']);
			$otros_ingresos = mainModel::cleanString($_POST['nominad_otros_ingresos']);
			
			//EGRESOS
			$deducciones = mainModel::cleanString($_POST['nominad_deducciones']);
			$prestamo = mainModel::cleanString($_POST['nominad_prestamo']);
			$ihss = mainModel::cleanString($_POST['nominad_ihss']);
			$rap = mainModel::cleanString($_POST['nominad_rap']);
			$isr = mainModel::cleanString($_POST['nominad_isr']);
			$incapacidad_ihss = mainModel::cleanString($_POST['nominad_incapacidad_ihss']);

			//RESUMEN
			$neto_ingresos = mainModel::cleanString($_POST['nominad_neto_ingreso']);
			$neto_egresos = mainModel::cleanString($_POST['nominad_neto_egreso']);
			$neto = mainModel::cleanString($_POST['nominad_neto']);

			$usuario = $_SESSION['colaborador_id_sd'];
			$estado = 0;//SIN GENERAR
			$notas = mainModel::cleanString($_POST['nomina_detalles_notas']);
			$fecha_registro = date("Y-m-d H:i:s");	

			$datos = [
				"nomina_id" => $nomina_id,
				"colaboradores_id" => $colaboradores_id,
				"salario" => $salario,
				"dias_trabajados" => $dias_trabajados,
				"hrse25" => $hrse25,
				"hrse50" => $hrse50,
				"hrse75" => $hrse75,
				"hrse100" => $hrse100,
				"retroactivo" => $retroactivo,
				"bono" => $bono,
				"otros_ingresos" => $otros_ingresos,
				"deducciones" => $deducciones,
				"prestamo" => $prestamo,
				"ihss" => $ihss,
				"rap" => $rap,
				"isr" => $isr,
				"incapacidad_ihss" => $incapacidad_ihss,
				"neto_ingresos" => $neto_ingresos,
				"neto_egresos" => $neto_egresos,
				"neto" => $neto,					
				"usuario" => $usuario,
				"estado" => $estado,
				"notas" => $notas,
				"fecha_registro" => $fecha_registro,				
			];
			
			$resultNominaEmpleados = nominaModelo::valid_nomina_detalles_modelo($datos);
			
			if($resultNominaEmpleados->num_rows==0){
				$query = nominaModelo::agregar_nomina_detalles_modelo($datos);
				
				if($query){
					$alert = [
						"alert" => "clear",
						"title" => "Registro almacenado",
						"text" => "El registro se ha almacenado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formNominaDetalles",
						"id" => "proceso_nomina_detalles",
						"valor" => "Registro",	
						"funcion" => "listar_nominas_detalles();getEmpleado();",
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
		
		public function edit_nomina_controlador(){
			$nomina_id = $_POST['nomina_id'];
			$fecha_inicio = mainModel::cleanString($_POST['nomina_fecha_inicio']);						
			$fecha_fin = mainModel::cleanString($_POST['nomina_fecha_fin']);
			$notas = mainModel::cleanString($_POST['nomina_notas']);
			
			$datos = [
				"nomina_id" => $nomina_id,
				"fecha_inicio" => $fecha_inicio,
				"fecha_fin" => $fecha_fin,			
				"notas" => $notas,
			];		

			$query = nominaModelo::edit_nomina_modelo($datos);
			
			if($query){				
				$alert = [
					"alert" => "edit",
					"title" => "Registro modificado",
					"text" => "El registro se ha modificado correctamente",
					"type" => "success",
					"btn-class" => "btn-primary",
					"btn-text" => "¡Bien Hecho!",
					"form" => "formNomina",	
					"id" => "proceso_nomina",
					"valor" => "Editar",
					"funcion" => "listar_nominas();",
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

		public function edit_nomina_detalles_controlador(){
			$nomina_id = $_POST['nomina_id'];
			$colaboradores_id = mainModel::cleanString($_POST['nominad_empleados']);
			$salario = mainModel::cleanString($_POST['nominad_salario']);						

			//INGRESOS
			$dias_trabajados = mainModel::cleanString($_POST['nomina_fecha_fin']);
			$hrse25 = mainModel::cleanString($_POST['nomina_detale']);
			$hrse50 = mainModel::cleanString($_POST['nomina_importe']);			
			$hrse75 = mainModel::cleanString($_POST['nomina_notas']);
			$hrse100 = mainModel::cleanString($_POST['nominad_salario']);						
			$retroactivo = mainModel::cleanString($_POST['nomina_fecha_fin']);
			$bono = mainModel::cleanString($_POST['nomina_detale']);
			$otros_ingresos = mainModel::cleanString($_POST['nominad_otros_ingresos']);
			
			//EGRESOS
			$deducciones = mainModel::cleanString($_POST['deducciones']);
			$prestamo = mainModel::cleanString($_POST['prestamo']);
			$ihss = mainModel::cleanString($_POST['ihss']);
			$rap = mainModel::cleanString($_POST['rap']);
			$isr = mainModel::cleanString($_POST['isr']);
			$incapacidad_ihss = mainModel::cleanString($_POST['incapacidad_ihss']);

			//RESUMEN
			$neto_ingresos = mainModel::cleanString($_POST['neto_ingresos']);
			$neto_egresos = mainModel::cleanString($_POST['neto_egresos']);
			$neto = mainModel::cleanString($_POST['neto']);

			$estado = 1;//ACTIVAS
			$notas = mainModel::cleanString($_POST['neto']);
			$fecha_registro = date("Y-m-d H:i:s");	

			$datos = [
				"nomina_id" => $nomina_id,
				"colaboradores_id" => $colaboradores_id,
				"salario" => $salario,
				"hrse25" => $hrse25,
				"hrse50" => $hrse50,
				"hrse75" => $hrse75,
				"hrse100" => $hrse100,
				"retroactivo" => $retroactivo,
				"bono" => $bono,
				"otros_ingresos" => $otros_ingresos,
				"deducciones" => $deducciones,
				"prestamo" => $prestamo,
				"rap" => $rap,
				"isr" => $isr,
				"incapacidad_ihss" => $incapacidad_ihss,
				"neto_ingresos" => $neto_ingresos,
				"neto_egresos" => $neto_egresos,
				"neto" => $neto,					
				"usuario" => $usuario,
				"estado" => $estado,
				"notas" => $notas,
				"fecha_registro" => $fecha_registro,				
			];	

			$query = nominaModelo::edit_nomina_detalles_modelo($datos);
			
			if($query){				
				$alert = [
					"alert" => "edit",
					"title" => "Registro modificado",
					"text" => "El registro se ha modificado correctamente",
					"type" => "success",
					"btn-class" => "btn-primary",
					"btn-text" => "¡Bien Hecho!",
					"form" => "formNomina",	
					"id" => "proceso_nomina",
					"valor" => "Editar",
					"funcion" => "listar_nominas_detalles();",
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
		
		public function delete_nomina_controlador(){
			$nomina_id = $_POST['nomina_id'];
			
			$result_valid_momina_modelo = nominaModelo::valid_nomina_detalles_modelo($nomina_id);
			
			if($result_valid_momina_modelo->num_rows==0 ){
				$query = nominaModelo::delete_nomina_modelo($nomina_id);
								
				if($query){
					$alert = [
						"alert" => "clear",
						"title" => "Registro eliminado",
						"text" => "El registro se ha eliminado correctamente",
						"type" => "success",
						"btn-class" => "btn-primary",
						"btn-text" => "¡Bien Hecho!",
						"form" => "formNomina",	
						"id" => "proceso_nomina",
						"valor" => "Eliminar",
						"funcion" => "listar_nominas();",
						"modal" => "modal_registrar_nomina",
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

		public function delete_nomina_detalles_controlador(){
			$nomina_id = $_POST['nomina_id'];
			
			$query = nominaModelo::delete_nomina_detalles_modelo($nomina_id);
							
			if($query){
				$alert = [
					"alert" => "clear",
					"title" => "Registro eliminado",
					"text" => "El registro se ha eliminado correctamente",
					"type" => "success",
					"btn-class" => "btn-primary",
					"btn-text" => "¡Bien Hecho!",
					"form" => "formNomina",	
					"id" => "proceso_nomina",
					"valor" => "Eliminar",
					"funcion" => "listar_nominas_detalles();",
					"modal" => "modal_registrar_nomina",
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