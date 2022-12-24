<?php
    if($peticionAjax){
        require_once "../core/mainModel.php";
    }else{
        require_once "./core/mainModel.php";	
    }
	
	class nominaModelo extends mainModel{
		protected function agregar_nomina_modelo($datos){
			$nomina_id = mainModel::correlativo("nomina_id", "nomina");
			$insert = "INSERT INTO nomina VALUES('$nomina_id','".$datos['empresa_id']."','".$datos['pago_planificado_id']."','".$datos['tipo_nomina']."','".$datos['fecha_inicio']."','".$datos['fecha_fin']."','".$datos['detalle']."','".$datos['importe']."','".$datos['notas']."','".$datos['usuario']."','".$datos['estado']."','".$datos['fecha_registro']."')";

			$sql = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
			
			return $sql;			
		}

		protected function agregar_nomina_detalles_modelo($datos){
			$nomina_detalles_id = mainModel::correlativo("nomina_detalles_id", "nomina_detalles");
			$insert = "INSERT INTO nomina_detalles VALUES('$nomina_detalles_id','".$datos['nomina_id']."','".$datos['colaboradores_id']."','".$datos['salario']."','".$datos['dias_trabajados']."','".$datos['hrse25']."','".$datos['hrse50']."','".$datos['hrse75']."','".$datos['hrse100']."','".$datos['retroactivo']."','".$datos['bono']."','".$datos['otros_ingresos']."','".$datos['deducciones']."','".$datos['prestamo']."','".$datos['ihss']."','".$datos['rap']."','".$datos['isr']."','".$datos['incapacidad_ihss']."','".$datos['neto_ingresos']."','".$datos['neto_egresos']."','".$datos['neto']."','".$datos['usuario']."','".$datos['estado']."','".$datos['notas']."','".$datos['fecha_registro']."')";

			$sql = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
			
			return $sql;			
		}		
		
		protected function valid_nomina_modelo($detalle){
			$query = "SELECT nomina_id FROM nomina WHERE estado = 0 AND detalle = '".$detalle."'";
			$sql = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $sql;
		}

		protected function valid_nomina_detalles_modelo($nomina_id){
			$query = "SELECT nomina_detalles_id FROM nomina_detalles WHERE estado = 0 AND nomina_id = '".$nomina_id."'";
			$sql = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $sql;
		}	
		
		protected function edit_nomina_modelo($datos){
			$update = "UPDATE nomina
			SET 
				dias_trabajados = '".$datos['dias_trabajados']."',
				dias_trabajados = '".$datos['dias_trabajados']."',
				hrse50 = '".$datos['hrse50']."',
				hrse75 = '".$datos['hrse75']."',
				hrse100 = '".$datos['hrse100']."',
				retroactivo = '".$datos['retroactivo']."',
				bono = '".$datos['bono']."',
				otros_ingresos = '".$datos['otros_ingresos']."',
				notas = '".$datos['notas']."',
				deducciones = '".$datos['deducciones']."',
				prestamo = '".$datos['prestamo']."',
				ihss = '".$datos['ihss']."',
				rap = '".$datos['rap']."',
				isr = '".$datos['isr']."',
				incapacidad_ihss = '".$datos['incapacidad_ihss']."',
				neto_ingresos = '".$datos['neto_ingresos']."',
				neto_egresos = '".$datos['neto_egresos']."',
				neto = '".$datos['neto']."',						
				notas = '".$datos['notas']."'
			WHERE nomina_detalles_id = '".$datos['nomina_detalles_id']."'";
			
			$sql = mainModel::connection()->query($update) or die(mainModel::connection()->error);
			
			return $sql;			
		}

		protected function edit_nomina_detalles_modelo($datos){
			$update = "UPDATE nomina
			SET 
				fecha_inicio = '".$datos['fecha_inicio']."',
				fecha_fin = '".$datos['fecha_fin']."',
				notas = '".$datos['notas']."'
			WHERE nomina_id = '".$datos['nomina_id']."'";
			
			$sql = mainModel::connection()->query($update) or die(mainModel::connection()->error);
			
			return $sql;			
		}		
		
		protected function delete_nomina_modelo($nomina_id){
			$delete = "DELETE FROM nomina WHERE nomina_id = '$nomina_id'";
			
			$sql = mainModel::connection()->query($delete) or die(mainModel::connection()->error);
			
			return $sql;			
		}

		protected function delete_nomina_detalles_modelo($nomina_detalles_id){
			$delete = "DELETE FROM nomina_detalles WHERE nomina_detalles_id = '$nomina_detalles_id'";
			
			$sql = mainModel::connection()->query($delete) or die(mainModel::connection()->error);
			
			return $sql;			
		}
	}