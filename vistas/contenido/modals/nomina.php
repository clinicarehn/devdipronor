<!--INICIO MODAL CONTRATO-->
<div class="modal fade" id="modal_registrar_nomina">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Registro de Nomina</h4>    
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
        </div><div class="container"></div>
        <div class="modal-body">
			<form class="FormularioAjax" id="formNomina" action="" method="POST" data-form="" autocomplete="off" enctype="multipart/form-data">
					<div class="form-row">
						<div class="col-md-12 mb-3">
							<div class="input-group mb-3">
							<input type="hidden" id="nomina_id" name="contrato_id" class="form-control">						
							<input type="hidden" id="contrato_id" name="contrato_id" class="form-control">
							<input type="hidden" id="empresa_id" name="empresa_id" class="form-control">
							<input type="text" id="proceso_nomina" class="form-control" readonly>
							<div class="input-group-append">				
								<span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa fa-plus-square fa-lg"></i></span>
							</div>
						</div>	 
					</div>				
					<div class="col-md-3 mb-3">
					  <label for="nomina_tipo_contrato_id">Tipo Contrato <span class="priority">*<span/></label>
					  <div class="input-group mb-3">
						  <select id="nomina_tipo_contrato_id" name="nomina_tipo_contrato_id" class="selectpicker" data-live-search="true" title="Tipo de Contrato">
							<option value="">Seleccione</option>
						  </select>
					   </div>
					</div>	
					<div class="col-md-3 mb-3">
					  <label for="nomina_pago_planificado_id">Pago Planificado <span class="priority">*<span/></label>
					  <div class="input-group mb-3">
						  <select id="nomina_pago_planificado_id" name="nomina_pago_planificado_id" class="selectpicker" data-live-search="true" title="Pago Planificado">
							<option value="">Seleccione</option>
						  </select>
					   </div>
					</div>	
					<div class="col-md-3 mb-3">
					  <label for="nomina_tipo_empleado_id">Tipo Empleado <span class="priority">*<span/></label>
					  <div class="input-group mb-3">
						  <select id="nomina_tipo_empleado_id" name="nomina_tipo_empleado_id" class="selectpicker" data-live-search="true" title="Tipo Empleado">
							<option value="">Seleccione</option>
						  </select>
					   </div>
					</div>
					<div class="col-md-3 mb-3">
					  <label for="nomina_importe">Importe <span class="priority">*<span/></label>
					  <input type="number" required id="nomina_importe" name="nomina_importe" placeholder="Salario" class="form-control" step="0.01"/>
					</div>																				
				</div>

				<div class="form-row">
					<div class="col-md-3 mb-3">
					  <label for="nomina_fecha_inicio">Fecha Inicio <span class="priority">*<span/></label>
					  <input type="date" required id="nomina_fecha_inicio" name="nomina_fecha_inicio" value="<?php echo date("Y-m-d"); ?>" class="form-control" />
					</div>
					<div class="col-md-3 mb-3">
					  <label for="nomina_fecha_fin">Fecha Inicio <span class="priority">*<span/></label>
					  <input type="date" id="nomina_fecha_fin" name="nomina_fecha_fin" value="" class="form-control" />
					</div>											
				</div>

				<div class="form-row">
					<div class="col-md-12 mb-3">
						<label for="nomina_notas">Notas</label>
						<div class="input-group">
							<textarea id="nomina_notas" name="nomina_notas" placeholder="Notas" class="form-control" maxlength="1000" rows="3"></textarea>	
							<div class="input-group-prepend">						  
								<span class="input-group-text">
									<i class="btn btn-outline-success fas fa-microphone-alt" id="search_nomina_notas_start"></i>
									<i class="btn btn-outline-success fas fa-microphone-slash" id="search_nomina_notas_stop"></i>
								</span>
							</div>								  
						</div>	
						<p id="charNum_nomina_notas">254 Caracteres</p>
					</div>
				</div>														

				<div class="form-group" id="estado_nomina">				  
				  <div class="col-md-12">			
						<label class="switch">
							<input type="checkbox" id="nomina_activo" name="nomina_activo" value="1" checked>
							<div class="slider round"></div>
						</label>
						<span class="question mb-2" id="label_nomina_activo"></span>				
				  </div>				  
				</div>				
				<div class="RespuestaAjax"></div>  
			</form>
        </div>
		<div class="modal-footer">
			<button class="guardar btn btn-primary ml-2" type="submit" style="display: none;" id="reg_nomina" form="formNomina"><div class="sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Registrar</button>
			<button class="editar btn btn-warning ml-2" type="submit" style="display: none;" id="edi_nomina" form="formNomina"><div class="sb-nav-link-icon"></div><i class="fas fa-edit fa-lg"></i> Editar</button>
			<button class="eliminar btn btn-danger ml-2" type="submit" style="display: none;" id="delete_nomina" form="formNomina"><div class="sb-nav-link-icon"></div><i class="fa fa-trash fa-lg"></i> Eliminar</button>					
		</div>			
      </div>
    </div>
</div>
<!--FIN MODAL CONTRATO-->