<?php
    if($peticionAjax){
        require_once "../core/mainModel.php";
    }else{
        require_once "./core/mainModel.php";	
    }
	
	class tipoCuentaModelo extends mainModel{
		protected function agregar_tipo_cuenta_modelo($datos){
			$tipo_cuenta_id = mainModel::correlativo("tipo_cuenta_id", "tipo_cuenta");
			$insert = "INSERT INTO tipo_cuenta VALUES('$tipo_cuenta_id','".$datos['tipo_cuenta']."','".$datos['estado']."','".$datos['fecha_registro']."')";

			$sql = mainModel::connection()->query($insert) or die(mainModel::connection()->error);
			
			return $sql;			
		}
		
		protected function valid_tipo_cuenta_modelo($tipo_cuenta){
			$query = "SELECT tipo_cuenta_id FROM tipo_cuenta WHERE nombre = '$tipo_cuenta'";

			$sql = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $sql;
		}

		protected function valid_tipo_pago_cuenta_modelo($tipo_cuenta_id){
			$query = "SELECT tipo_pago_id FROM tipo_pago WHERE tipo_cuenta_id = '$tipo_cuenta_id'";
			$sql = mainModel::connection()->query($query) or die(mainModel::connection()->error);
			
			return $sql;
		}		
		
		protected function edit_tipo_cuenta_modelo($datos){
			$update = "UPDATE tipo_cuenta
			SET 
				nombre = '".$datos['tipo_cuenta']."',				
				estado = '".$datos['estado']."'
			WHERE tipo_cuenta_id = '".$datos['tipo_cuenta_id']."'";

			$sql = mainModel::connection()->query($update) or die(mainModel::connection()->error);
			
			return $sql;			
		}
		
		protected function delete_tipo_cuenta_modelo($tipo_pago_id){
			$delete = "DELETE FROM tipo_cuenta WHERE tipo_cuenta_id = '$tipo_pago_id'";
			
			$sql = mainModel::connection()->query($delete) or die(mainModel::connection()->error);
			
			return $sql;			
		}
	}