<!--INICIO MODAL PARA EL INGRESO DE TIPO DE PAGO-->
<div class="modal fade" id="modalConfTipoCuenta">
	<div class="modal-dialog modal modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tipo de Cuenta</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
        </div><div class="container"></div>
        <div class="modal-body">		
			<form class="form-horizontal FormularioAjax" id="formConfTipoCuenta" action="" method="POST" data-form="" enctype="multipart/form-data">				
				<div class="form-row">
					<div class="col-md-12 mb-3">
					    <input type="hidden" required="required" readonly id="tipo_cuenta_id" name="tipo_cuenta_id"/>
						<div class="input-group mb-3">
							<input type="text" required readonly id="pro_tipoCuenta" name="pro_tipoCuenta" class="form-control"/>
							<div class="input-group-append">				
								<span class="input-group-text"><div class="sb-nav-link-icon"></div><i class="fa fa-plus-square fa-lg"></i></span>
							</div>
						</div>	 
					</div>							
				</div>
				<div class="form-row">
					<div class="col-md-12 mb-3">
						<label for="tipo_cuenta">Tipo de Cuenta<span class="priority">*<span/></label>
						<input type="text" required readonly id="tipo_cuenta" name="tipo_cuenta" class="form-control"/>
					</div>					
				</div>				
				<div class="form-group" id="estado_tipo_cuenta">				  
				  <div class="col-md-12">			
						<label class="switch">
							<input type="checkbox" id="confTipoCuenta_activo" name="confTipoCuenta_activo" value="1" checked>
							<div class="slider round"></div>
						</label>
						<span class="question mb-2" id="label_confTipoCuenta_activo"></span>				
				  </div>				  
				</div>					
				<div class="RespuestaAjax"></div> 
			</form>
        </div>	
		<div class="modal-footer">
			<button class="guardar btn btn-primary ml-2" type="submit" style="display: none;" id="reg_formTipoCuenta" form="formConfTipoCuenta"><div class="guardar sb-nav-link-icon"></div><i class="far fa-save fa-lg"></i> Registrar</button>
			<button class="editar btn btn-warning ml-2" type="submit" style="display: none;" id="edi_formTipoCuenta" form="formConfTipoCuenta"><div class="sb-nav-link-icon"></div><i class="fas fa-edit fa-lg"></i> Editar</button>
			<button class="eliminar btn btn-danger ml-2" type="submit" style="display: none;" id="delete_formTipoCuenta" form="formConfTipoCuenta"><div class="sb-nav-link-icon"></div><i class="fa fa-trash fa-lg"></i> Eliminar</button>					
		</div>			
      </div>
    </div>
</div>
<!--FIN MODAL PARA EL INGRESO DE TIPO DE PAGO-->