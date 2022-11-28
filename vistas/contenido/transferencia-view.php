<div class="container-fluid">
    <ol class="breadcrumb mt-2 mb-4">
        <li class="breadcrumb-item"><a class="breadcrumb-link" href="<?php echo SERVERURL; ?>dashboard/">Dashboard</a></li>
        <li class="breadcrumb-item active">Inventario</li>
    </ol>
   <div class="card mb-4">
        <div class="card-body">
			<form class="" id="form_main_movimientos">
				<div class="row">
					<div class="form-group mx-sm-3 mb-1">
						<div class="input-group">				
							<div class="input-group-append">				
								<span class="input-group-text"><div class="sb-nav-link-icon"></div>Categoría</span>
							</div>
							<select id="inventario_tipo_productos_id" name="inventario_tipo_productos_id" class="custom-select" data-toggle="tooltip" data-placement="top" title="Categoría de Productos">
						</select>
						</div>
					</div>
					<div class="form-group mx-sm-3 mb-1">
						<div class="input-group">
							<div class="input-group-append">
								<span class="input-group-text"><div class="sb-nav-link-icon"></div>Productos</span>
								<select id="inventario_productos_id" name="inventario_productos_id" class="custom-select" data-toggle="tooltip" data-placement="top" title="Productos">
								</select>
								<div class="input-group-append" id="grupo_editar_rtn">				
									<span data-toggle="tooltip" data-placement="top" title="Consultar Facturadores"><a data-toggle="modal" href="#" class="btn btn-outline-success form-control consultar_facturador"><div class="sb-nav-link-icon"></div><i class="fas fa-search fa-lg"></i></a></span>
								</div>
							</div>	
						</div>
					</div>
						<div class="form-group mx-sm-3 mb-1">
							<div class="input-group">				
								<div class="input-group-append">				
									<span class="input-group-text"><div class="sb-nav-link-icon"></div>Bodega</span>
								</div>
								<select id="almacen" name="almacen" class="custom-select" data-toggle="tooltip" data-placement="top" title="Categoría de Productos">
									
							</select>
							</div>
						</div>
				</div>
				
			</form>          
        </div>
    </div>	
    <div class="card mb-4">
		<div class="card mb-4">
			<div class="card-header">
				<i class="fab fa-servicestack mr-1"></i>
				Inventario
			</div>
			<div class="card-body"> 
				<div class="table-responsive">
					<table id="dataTablaMovimientos" class="table table-striped table-condensed table-hover" style="width:100%">
						<thead>
							<tr>
							<th>Fecha</th>	
						    <th>Bar Code</th>
							<th>Producto</th>
							<th>Medida</th>
							<th>Entrada</th>
							<th>Salida</th>
							<th>Saldo</th>
							<th>Bodega</th>
							<th>Transferencia</th>
							</tr>
						</thead>
					</table>  
				</div>                   
			</div>
			<div class="card-footer small text-muted">
				<?php
					require_once "./core/mainModel.php";
					
					$insMainModel = new mainModel();
					$entidad = "productos";
					
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
	$insMainModel->guardar_historial_accesos("Ingreso al modulo Inventario");
?>