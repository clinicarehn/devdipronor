<?php
   require_once "mainModel.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
	<link rel="stylesheet" href="<?php echo SERVERURL; ?>vistas/plantilla/css/style_factura.css">
	<link rel="shortcut icon" href="<?php echo SERVERURL; ?>vistas/plantilla/img/icono.png">
</head>
<body>
<?php echo $anulada; ?>
<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<center>
					<img src="<?php echo SERVERURL; ?>vistas/plantilla/img/logo.png" width="250px" height="95px" class="">
				</center>
			</td>
        </tr>
        <tr>
			<td class="textcenter">
					<span class="h2"><?php echo $consulta_registro['empresa']; ?></span>			
			</td>
        </tr>
        <tr><td class="textcenter">
            <p><?php echo nl2br($consulta_registro['direccion_empresa']); ?></p>
        </td></tr>
        <tr><td class="textcenter">
            <p><?php echo nl2br($consulta_registro['otra_informacion']); ?></p>	
        </td></tr>      
        <tr><td class="textcenter">
                <p>PBX: <?php echo $consulta_registro['empresa_telefono']; ?></p>
        </td></tr>
        <tr><td class="textcenter">
            <p>WhatsApp: <?php echo $consulta_registro['empresa_celular']; ?></p>					
        </td></tr>

        <tr>
            <td class="textcenter">
                 <p>Correo: <?php echo $consulta_registro['empresa_correo']; ?></p>
            </td>
        </tr>
        <tr class="">
                    <td class="textcenter">
                        <span class="h3">Factura</span>
                    </td>
        </tr>
		<tr>			
        <td class="textcenter">
                        <p><b>N° Factura:</b> <?php echo $consulta_registro['prefijo'].''.str_pad($consulta_registro['numero_factura'], $consulta_registro['relleno'], "0", STR_PAD_LEFT); ?></p>
                    </td></tr>
					<tr>
                    <td class="textcenter">
                        <p><b>Fecha:</b> <?php echo $consulta_registro['fecha'].' '.date('g:i a',strtotime($consulta_registro['hora'])); ?></p>
                    </td></tr>
					<tr>
                    <td class="textcenter">
                        <p><b>CAI:</b> <?php echo $consulta_registro['cai']; ?></p>
                    </td></tr>
					<tr>
                    <td class="textcenter">
                        <p><b>RTN:</b> <?php echo $consulta_registro['rtn_empresa']; ?></p>
                    </td></tr>
					<tr>
                    <td class="textcenter">
                        <p><b>Desde:</b> </b><?php echo $consulta_registro['prefijo'].''.$consulta_registro['rango_inicial']; ?> <b>Hasta:</b> <?php echo $consulta_registro['prefijo'].''.$consulta_registro['rango_final']; ?></p>
                    </td></tr>
					<tr>
                    <td class="textcenter">
                        <p><b>Fecha de Activación:</b> <?php echo $consulta_registro['fecha_activacion']; ?></p>
                    </td></tr>
					<tr>
                    <td class="textcenter">
                        <p><b>Fecha Limite de Emisión:</b> <?php echo $consulta_registro['fecha_limite']; ?></p>
                    </td></tr>
					<tr>
                    <td class="textcenter">
                        <p><b>Factura:</b> <?php echo $consulta_registro['tipo_documento']; ?></p>
                    </td>
        </tr>
	</table>
	<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="">
					<table class="datos_cliente">
						<tr>
							<td><label>RTN:</label><p><?php 
									if(strlen($consulta_registro['rtn_cliente'])<10){
										echo "";
									}else{
										echo $consulta_registro['rtn_cliente'];
									}
							
							?></p></td>		
                            <td><label>Teléfono:</label> <p><?php echo $consulta_registro['telefono']; ?></p></td>
                        </tr>
						<tr>
							<td colspan="2"><label>Cliente:</label><p><?php echo $consulta_registro['cliente']; ?></p></td>
						</tr>
					</table>
				</div>
			</td>

		</tr>
	</table>

	<table id="factura_detalle">
			<thead>
				<tr>
					<th width="">Producto</th>
					<th width="" class="textleft">Cantidad</th>
					<th width="6%" class="textleft">Medida</th>
					<th width="" class="textright">Precio</th>
					<th width="" class="textright">Descuento</th>
					<th width="" class="textright">Importe</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
				<?php
					$total_despues_isv = 0;
					$importe_gravado = 0;
					$importe_excento = 0;
					$subtotal = 0;
					$isv_neto = 0;
					$descuentos_neto = 0;
					$total = 0;
					$i = 1;
					$totalHNL = 0;
					$tasaCambioHNL = 0;
					$descuentos = 0;
					$producto_name = '';
					
					while($registro_detalles = $result_factura_detalle->fetch_assoc()){																
						$total_ = 0;
						$importe = 0;

						$total += ($registro_detalles["precio"] * $registro_detalles["cantidad"]);
						$total_ = ($registro_detalles["precio"] * $registro_detalles["cantidad"]);
						$importe += ($registro_detalles["precio"] * $registro_detalles["cantidad"] - $registro_detalles["descuento"]);
						$subtotal += $importe;
						$descuentos += $registro_detalles["descuento"];
						$descuentos_neto += $descuentos;
						$isv_neto += $registro_detalles["isv_valor"];
						
						if($registro_detalles["isv_valor"] > 0){
							$importe_gravado += ($registro_detalles["precio"] * $registro_detalles["cantidad"] - $registro_detalles["descuento"]);
						}else{
							$importe_excento += ($registro_detalles["precio"] * $registro_detalles["cantidad"] - $registro_detalles["descuento"]);
						}						
						
						if($registro_detalles["barCode"] != "" || $registro_detalles["barCode"] != null){
							$producto_name = '['.$registro_detalles["barCode"].'] '.$registro_detalles["producto"];
						}else{
							$producto_name = $registro_detalles["producto"];
						}

						echo '
                        
                        <tr>
                        <td>'.$producto_name.'</td>
                        <td align="center">'.$registro_detalles["cantidad"].'</td>
						<td align="center">'.$registro_detalles["medida"].'</td>
                        <td class="textright">L. '.number_format($registro_detalles["precio"],2).'</td>
                        <td class="textright">L. '.number_format($descuentos,2).'</td>
                        <td class="textright">L. '.number_format($importe,2).'</td>
                        </tr>
						';
						$i++;
					}

					$total_despues_isv = ($total + $isv_neto) - $descuentos_neto;				
				?>
			</tbody>
			<tfoot id="detalle_totales">				
		
				<tr>
					<td colspan="5" class="textright"><span>Importe</span></td>
					<td class="textright"><span>L. <?php echo number_format($total,2);?></span></td>
				</tr>
				<tr>
				<td colspan="5" class="textright"><span>Descuentos y Rebajas Otorgados</span></td>
					<td class="textright"><span>L. <?php echo number_format($descuentos_neto,2);?></span></td>
				</tr>				
				<tr>
					<td colspan="5" class="textright"><span>Sub-Total</span></td>
					<td class="textright"><span>L. <?php echo number_format($subtotal,2);?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>Importe Exonerado</span></td>
					<td class="textright"><span>L. <?php echo number_format(0,2);?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>Importe Excento</span></td>
					<td class="textright"><span>L. <?php echo number_format($importe_excento,2);?></span></td>
				</tr>				
				<tr>
					<td colspan="5" class="textright"><span>Importe Gravado 15%</span></td>
					<td class="textright"><span>L. <?php echo number_format($importe_gravado,2); ?></span></td>
				</tr>
				<tr>
					<td colspan="5" class="textright"><span>Importe Gravado 18%</span></td>
					<td class="textright"><span>L. <?php echo number_format(0,2);?></span></td>
				</tr>	
				<tr>
					<td colspan="5" class="textright"><span>ISV 15%</span></td>
					<td class="textright"><span>L. <?php echo number_format($isv_neto,2); ?></span></td>
				</tr>	
				<tr>
					<td colspan="5" class="textright"><span>ISV 18%</span></td>
					<td class="textright"><span>L. <?php echo number_format(0,2);?></span></td>
				</tr>			
				<tr>
					<td colspan="5" class="textright"><span>Total</span></td>
					<td class="textright"><span>L. <?php echo number_format($total_despues_isv,2); ?></span></td>
				</tr>				
		</tfoot>
	</table>
	<div>
	    <p class="nota"><?php 
			if($consulta_registro["notas"] != ""){
				echo "<p class='h2'><b>Nota:</b> ".nl2br($consulta_registro["notas"])."</p>";
			}		
		?></p>
		<p class="nota"><br/><br/></p>		
		<p class="nota"><center><?php echo $insMainModel->convertir($total_despues_isv).' LEMPIRAS';?></center></p>

        <p class="nota"><center>
			<?php 
				include_once 'cambioDolar.php';
				if($consulta_registro['fecha_dolar'] != '0000-00-00' ){
					$new_fecha_dolar = $consulta_registro['fecha_dolar'];

					if($total_despues_isv != 0 || $total_despues_isv != ""){
						$dolar = cambioDolar($total_despues_isv,$new_fecha_dolar);
						if($dolar->result != 0 || $dolar->result != null || $dolar->result != ""){
							echo "Tasa de Cambio L. ". number_format($total_despues_isv/$dolar->result,2)."<br/>";
							echo 'Total Dolares $ '.round($dolar->result,2);
						}
					}										
				}
			?>
			</center>
		</p>
        <p class="nota"><center><?php 
				if($consulta_registro['fecha_dolar'] != '0000-00-00') { 
					if($total_despues_isv != 0 || $total_despues_isv != ""){
						if($dolar->result != 0 || $dolar->result != null || $dolar->result != ""){
							echo $insMainModel->convertir($dolar->result).' DOLARES';
						}
					}			
				}?>	
		</center></p>		
		<p class="nota"><br/><br/></p>
		<p class="nota">La factura es beneficio de todos "Exíjala"</p>	
		<p class="nota">N° correlativo de orden de compra excenta __________________</p>
		<p class="nota">N° correlativo constancia de registro Exonerado __________________</p>
		<p class="nota">N° identificativo del registro de la SAG __________________</p>	

		<?php
			if($consulta_registro["estado"] == 2){
		?>
        <div style="padding-top: 3rem; padding-bottom: 3rem;">
			<p class="nota"><center><img src="<?php echo SERVERURL; ?>vistas/plantilla/img/sello.png" width="150px" height="130px"></p>
        </div>
		<?php
			}
		?>
		<p class="nota"><center><b>Original:</b> Cliente</center></p>	
		<p class="nota"><center><b>Copia:</b> Emisor</center></p>			
		<h4 class="label_gracias"><?php  echo nl2br($consulta_registro["eslogan"]); ?></h4>
	</div>

</div>
</body>
</html>