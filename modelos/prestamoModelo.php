<?php
    if($peticionAjax){
        require_once "../core/mainModel.php";
    }else{
        require_once "./core/mainModel.php";	
    }
	
	class prestamoModelo extends mainModel{
		protected function agregar_prestamo_modelo($datos){
			$prestamo_id = mainModel::correlativo("prestamo_id", "prestamo");
			$insert = "INSERT INTO prestamo VALUES('$prestamo_id','".$datos['colaboradores_id']."','".$datos['fecha']."','".$datos['monto']."','".$datos['usuario']."','".$datos['estado']."','".$datos['fecha_registro']."')";
			
			$sql = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
			
			return $sql;			
		}
		
		protected function valid_prestamo_modelo($colaboradores_id){
			$query = "SELECT prestamo_id FROM prestamo WHERE colaboradores_id = '$colaboradores_id' AND estado = 0";
			$sql = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $sql;
		}
		
		protected function edit_prestamo_modelo($datos){
			$update = "UPDATE prestamo
			SET 
				monto = '".$datos['monto']."'
			WHERE prestamo_id = '".$datos['prestamo_id']."' AND estado = 0";
			
			$sql = mainModel::connection()->query($update) or die(mainModel::connection()->error);
			
			return $sql;			
		}
		
		protected function delete_prestamo_modelo($prestamo_id){
			$delete = "DELETE FROM prestamo WHERE prestamo_id = '$prestamo_id' AND estado = 0";
			
			$sql = mainModel::connection()->query($delete) or die(mainModel::connection()->error);
			
			return $sql;			
		}
	}
?>	