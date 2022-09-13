<div class="container-fluid">
    <ol class="breadcrumb mt-2 mb-4">
        <li class="breadcrumb-item"><a class="breadcrumb-link" href="<?php echo SERVERURL; ?>dashboard/">Dashboard</a></li>
        <li class="breadcrumb-item active">Secuencia Facturación</li>
    </ol>
	<div class="card mb-4">
        <div class="card-body">
			<form class="form-inline" id="form_main_secuencia">
				<div class="form-group mx-sm-3 mb-1">
					<div class="input-group">				
						<div class="input-group-append">				
							<span class="input-group-text"><div class="sb-nav-link-icon"></div>Estado</span>
						</div>
						<select id="estado" name="estado" class="custom-select" data-toggle="tooltip" data-placement="top" title="Categoría de Productos">
							<option value="1">Activo</option>
							<option value="2">Inactivo</option>
					  </select>
					</div>
				</div>				
				  <div class="form-group mx-sm-2 mb-1">
               		 <button class="consultar btn btn-secondary ml-2" type="submit" id="search"><div class="sb-nav-link-icon"></div><i class="fas fa-search fa-lg"></i> Buscar</button>
			 	 </div>   				  
			</form>          
        </div>
    </div>	


    <div class="card mb-4">
		<div class="card mb-4">
			<div class="card-header">
				<i class="fas fa-sliders-h mr-1"></i>
				Secuencia Facturación
			</div>
			<div class="card-body"> 
				<div class="table-responsive">
					<table id="dataTableSecuencia" class="table table-striped table-condensed table-hover" style="width:100%">
						<thead>
							<tr>
								<th>Empresa</th>
								<th>CAI</th>
								<th>Prefijo</th>
								<th>Siguiente</th>
								<th>Rango Inicial</th>
								<th>Rango Final</th>
								<th>Fecha Limite</th>
								<th>Editar</th>	
								<th>Eliminar</th>							
							</tr>
						</thead>
					</table>  
				</div>                   
				</div>
			<div class="card-footer small text-muted">
 			<?php
				require_once "./core/mainModel.php";
				
				$insMainModel = new mainModel();
				$entidad = "secuencia_facturacion";
				
				if($insMainModel->getlastUpdate($entidad)->num_rows > 0){
					$consulta_last_update = $insMainModel->getlastUpdate($entidad)->fetch_assoc();
					
					$fecha_registro = $consulta_last_update['fecha_registro'];
					$hora = date('g:i:s a',strtotime($fecha_registro));
									
					echo "Última Actualización ".$insMainModel->getTheDay($fecha_registro, $hora);						
				}else{
					echo "No se encontraron registros ";
				}			
			?>
			</div>
		</div>
	</div>

<?php
	$insMainModel->guardar_historial_accesos("Ingreso al modulo Secuencia de Facturación");
?>