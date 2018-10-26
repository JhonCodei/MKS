<!-- MODAL PRONOSTICO -->
<div class="modal fade" id="modal-add-record" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
   <div class="modal-body">
				<div class="text-left" style="">
						<label class="h3"> Gastos </label>
							<button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" data-dismiss="modal" aria-hidden="true" onclick="return close_modal('modal-add-record');">
						<span class="fa fa-close"></span></button>
					</div>
					<hr>
								<table class="" style="width:100%;">
									<thead>
										<th  style="width:auto;"></th>
										<th style="width:80%;"></th>
									</thead>
									<tbody>
									<input type="hidden" id="id_gasto_sql"/>
									<tr>
											<td class="text-right bg-primary text-white font-weight-bold border-secondary input-sm align-middle text-center">FECHA&nbsp;&nbsp;</td>
											<td>
												<input type="text" class="form-control input-sm text-center border border-secondary" id="fecha" placeholder="Fecha" onkeypress="return max_length(this.value, 9);">
											</td>
									</tr>
										<tr>
											<td class="text-right bg-primary text-white font-weight-bold input-sm align-middle text-center">MOTIVO&nbsp;&nbsp;</td>
											<td><select class="form-control-sm input-sm border border-secondary" id="motivo" style="width:100%;text-align-last:center;">
                <?php print _motivos_('PSJ'); ?>
            </select></td>
										</tr>
										<tr>
											<td class="text-right bg-primary text-white font-weight-bold input-sm align-middle text-center">TIPO&nbsp;DOC.&nbsp;&nbsp;</td>
											<td>
												<select class="form-control-sm input-sm border border-secondary" id="tipo" style="width:100%;text-align-last:center;">
                <?php print _tipo_documentos_('FAC'); ?>
            </select>
											</td>
										</tr>
										<tr>
											<td class="text-right bg-primary text-white font-weight-bold border-secondary input-sm align-middle text-center">DOC.&nbsp;&nbsp;</td>
											<td>
											<input type="text" class="form-control input-sm text-center border border-secondary" onkeypress="return max_length(this.value, 29);" id="documentos" placeholder="Documento">
											</td>
										</tr>
										<tr>
											<td class="text-right bg-primary text-white font-weight-bold border-secondary input-sm align-middle text-center">RUC&nbsp;&nbsp;</td>
											<td>
											<input type="number" class="form-control input-sm text-center border border-secondary" onkeypress="return max_length(this.value, 10);" id="ruc" placeholder="RUC">
											</td>
										</tr>
										<tr>
											<td class="text-right bg-primary text-white font-weight-bold border-secondary input-sm align-middle text-center">IMPORTE&nbsp;&nbsp;</td>
											<td>
											<input type="text" class="form-control input-sm text-center border border-secondary" onkeypress="return max_length(this.value, 6);" id="importe" placeholder="Importe">
											</td>
										</tr>
										<tr>
											<td class="text-right bg-primary text-white font-weight-bold border-secondary input-sm align-middle text-center">VENTAS&nbsp;&nbsp;</td>
											<td>
												<input type="text" class="form-control input-sm text-center border border-secondary" onkeypress="return max_length(this.value, 6);" id="ventas" placeholder="Ventas">
											</td>
										</tr>
										<tr>
											<td class="text-right bg-primary text-white font-weight-bold border-secondary input-sm align-middle text-center">RUC CLI.&nbsp;&nbsp;</td>
											<td>
											<input type="number" class="form-control input-sm text-center border border-secondary" onkeypress="return max_length(this.value, 10);" id="ruc-cliente" placeholder="RUC Cliente">
											</td>
										</tr>
										<tr>
										<td class="text-right bg-primary text-white font-weight-bold border-secondary input-sm align-middle text-center">OBS.&nbsp;&nbsp;</td>
											<td>
												<textarea type="text" class="form-control input-sm text-center border border-secondary" maxlength="250" style="resize:none;" id="observaciones" placeholder="Observaciones"></textarea>
											</td>
										</tr>
										<tr>
										<td ></td>
											<td class="pull-right">
												<button class="btn btn-md btn-default waves-light waves-effect" id="btn_events" onclick="_insertar_gasto();"><span class="fa fa-save"></span>&nbsp;Guardar</button>
											</td>
										</tr>
									</tbody>
								</table>
   </div>
  </div>
 </div>
</div>
<!-- MODAL PRONOSTICO -->