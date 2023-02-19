<!--INICIO MODAL ASISTENCIA-->
<div class="modal fade" id="modal_registrar_asistencia">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Asistencia</h4>    
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
        </div><div class="container"></div>
        <div class="modal-body">
			<form class="FormularioAjax FormularioAjax" id="formAsistencia" action="" method="POST" data-form="" autocomplete="off" enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-12 mb-3">
						<div class="input-group mb-3">
							<input type="hidden" id="asistencia_id" name="asistencia_id" class="form-control">
							<input type="text" id="proceso_asistencia" class="form-control" readonly>
							<div class="input-group-append">				
								<span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa fa-plus-square fa-lg"></i></span>
							</div>
						</div>	 
					</div>							
				</div>					
				<div class="form-row">
					<div class="col-md-4 mb-4">
						<label for="asistencia_empleado">Empleado <span class="priority">*<span/></label>
						<div class="input-group">
							<div class="input-group-append">
								<select id="asistencia_empleado" name="asistencia_empleado" class="selectpicker" title="Empleado" data-size="5" data-live-search="true" required>
								</select>
							</div>	
						</div>
					</div>					
					<div class="col-md-3 mb-3">
					  <label for="fecha">Fecha <span class="priority">*<span/></label>
					  <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo date ("Y-m-d");?>" placeholder="Fecha" required>		  
					</div>	
					<div class="col-md-3 mb-3">
					  <label for="fecha">Hora <span class="priority">*<span/></label>
					  <input type="time" class="form-control" id="hora" name="hora">		  
					</div>							
				</div>	
				<div class="form-row">
					<br/>
					<br/>
					<br/>
				</div>							
				<div class="RespuestaAjax"></div>  
			</form>
        </div>
		<div class="modal-footer">
			<button class="guardar btn btn-primary ml-2" type="submit" style="display: none;" id="reg_asistencia" form="formAsistencia"><div class="sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Registrar</button>	
		</div>			
      </div>
    </div>
</div>
<!--FIN MODAL ASISTENCIA-->

<!--INICIO CONSULTAR ASISTENCIAS-->
<div class="modal fade" id="modal_consultar_asistencia">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Buscar Asistencias</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
        </div><div class="container"></div>
        <div class="modal-body">
			<form class="FormularioAjax" id="formulario_consultar_asistencia">
				<input type="hidden" id="colaboradores_id" name="colaboradores_id" class="form-control">			
				<div class="form-group">				  
					<div class="col-md-12">			
						<div class="overflow-auto">											
							<table id="DatatableConsultarAsistencia" class="table table-striped table-condensed table-hover" style="width:100%">
								<thead>
									<tr>
										<th>Empleado</th>
										<th>Fecha</th>
										<th>Eliminar</th>
									</tr>
								</thead>
							</table>
						</div>				
					</div>				  
				</div>
			</form>
        </div>
		<div class="modal-footer">

		</div>			
      </div>
    </div>
</div>
<!--FIN CONSULTAR ASISTENCIAS-->

<!--INICIO MODAL VALES-->
<div class="modal fade" id="modal_registrar_vales">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Registro de Vales</h4>    
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
        </div><div class="container"></div>
        <div class="modal-body">
			<form class="FormularioAjax FormularioAjax" id="formVales" action="" method="POST" data-form="" autocomplete="off" enctype="multipart/form-data">
				<div class="form-row">
					<div class="col-md-12 mb-3">
						<div class="input-group mb-3">
							<input type="hidden" id="prestamo_id" name="prestamo_id" class="form-control">						
							<input type="text" id="proceso_vales" class="form-control" readonly>
							<div class="input-group-append">				
								<span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa fa-plus-square fa-lg"></i></span>
							</div>
						</div>	 
					</div>							
				</div>					
				<div class="form-row">
					<div class="col-md-4 mb-4">
						<label for="vale_empleado">Empleado <span class="priority">*<span/></label>
						<div class="input-group">
							<div class="input-group-append">
								<select id="vale_empleado" name="vale_empleado" class="selectpicker" title="Empleado" data-size="5" data-live-search="true" required>
								</select>
							</div>	
						</div>
					</div>					
					<div class="col-md-3 mb-3">
					  <label for="fecha">Fecha <span class="priority">*<span/></label>
					  <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo date ("Y-m-d");?>" placeholder="Fecha" required>		  
					</div>	
					<div class="col-md-3 mb-3">
					  <label for="vale">Monto <span class="priority">*<span/></label>
					  <input type="number" class="form-control" id="vale" name="vale" step="0.00001">		  
					</div>							
				</div>	
				<div class="form-row">
					<br/>
					<br/>
					<br/>
				</div>							
				<div class="RespuestaAjax"></div>  
			</form>
        </div>
		<div class="modal-footer">
			<button class="guardar btn btn-primary ml-2" type="submit" style="display: none;" id="reg_vales" form="formVales"><div class="sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Registrar</button>	
			<button class="eliminar btn btn-danger ml-2" type="submit" style="display: none;" id="delete_vales" form="formVales"><div class="sb-nav-link-icon"></div><i class="fa fa-trash fa-lg"></i> Eliminar</button>					 
		</div>			
      </div>
    </div>
</div>
<!--FIN MODAL VALES-->