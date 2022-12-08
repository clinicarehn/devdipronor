<div class="container-fluid">
    <ol class="breadcrumb mt-2 mb-4">
        <li class="breadcrumb-item"><a class="breadcrumb-link" href="<?php echo SERVERURL; ?>dashboard/">Dashboard</a></li>
        <li class="breadcrumb-item active">Nomina</li>
    </ol>
	<div class="card mb-4">
        <div class="card-body">
			<form class="form-inline" id="form_main_nominas">			
				<div class="form-group mx-sm-3 mb-1">			
					<div class="input-group">
						<div class="input-group-append">
							<span class="input-group-text"><div class="sb-nav-link-icon"></div>Estado</span>
							<select id="estado_nomina" class="selectpicker" data-live-search="true">
								<option value="1">Activas</option>
								<option value="2">Inactivas</option>
							</select>
						</div>	
					</div>					
				</div>					
				<div class="form-group mx-sm-3 mb-1">
					<div class="input-group">
						<div class="input-group-append">
							<span class="input-group-text"><div class="sb-nav-link-icon"></div>Tipo Contrato</span>
							<select id="tipo_contrato_nomina" name="tipo_contrato_nomina" class="selectpicker" title="Tipo Contrato" data-live-search="true">
								<option value="">Seleccione</option>
					 		 </select>
						</div>	
					</div>
				</div>	
				<div class="form-group mx-sm-3 mb-1">
					<div class="input-group">
						<div class="input-group-append">
							<span class="input-group-text"><div class="sb-nav-link-icon"></div>Pago Planificado</span>
							<select id="pago_planificado_nomina" name="pago_planificado_nomina" class="selectpicker" data-live-search="true" title="Pago Planificado">
								<option value="">Seleccione</option>
					 		 </select>
						</div>	
					</div>
				</div> 	
				<div class="form-group mx-sm-3 mb-1">
					<div class="input-group">				
						<div class="input-group-append">				
							<span class="input-group-text"><div class="sb-nav-link-icon"></div>Inicio</span>
						</div>
						<input type="date" required id="fechai" name="fechai" value="<?php 
						$fecha = date ("Y-m-d");
						
						$año = date("Y", strtotime($fecha));
						$mes = date("m", strtotime($fecha));
						$dia = date("d", mktime(0,0,0, $mes+1, 0, $año));

						$dia1 = date('d', mktime(0,0,0, $mes, 1, $año)); //PRIMER DIA DEL MES
						$dia2 = date('d', mktime(0,0,0, $mes, $dia, $año)); // ULTIMO DIA DEL MES

						$fecha_inicial = date("Y-m-d", strtotime($año."-".$mes."-".$dia1));
						$fecha_final = date("Y-m-d", strtotime($año."-".$mes."-".$dia2));						
						
						
						echo $fecha_inicial;
					?>" class="form-control" data-toggle="tooltip" data-placement="top" title="Fecha Inicio" style="width:165px;">
					</div>
				  </div>	
				  <div class="form-group mx-sm-3 mb-1">
				 	<div class="input-group">				
						<div class="input-group-append">				
							<span class="input-group-text"><div class="sb-nav-link-icon"></div>Fin</span>
						</div>
						<input type="date" required id="fechaf" name="fechaf" value="<?php echo date ("Y-m-d");?>" class="form-control" data-toggle="tooltip" data-placement="top" title="Fecha Fin" style="width:165px;">
					</div>
				  </div>  				 							  
			</form>          
        </div>
    </div>		
    <div class="card mb-4">
		<div class="card mb-4">
			<div class="card-header">
				<i class="fas fa-hand-holding-usd mr-1"></i>
				Nomina
			</div>
			<div class="card-body"> 
				<div class="table-responsive">
					<table id="dataTableNomina" class="table table-striped table-condensed table-hover" style="width:100%">
						<thead>
							<tr>
								<th>Código</th>
								<th>Contrato</th>
								<th>Empresa</th>
								<th>Fecha Inicio</th>
								<th>Fecha Fin</th>
								<th>Importe</th>
								<th>Notas</th>
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
				$entidad = "nomina";
				
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
	$insMainModel->guardar_historial_accesos("Ingreso al modulo Nomnas");
?>