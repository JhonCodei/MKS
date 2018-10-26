<div class="row">
 <div class="card-box col-lg-12">
 <div class="text-left">
    <label class="mx-auto text-left text-dark h3 m-b-0"><strong>Gastos</strong></label>
          </div>

          <div class="pull-in-card p-20 widget-box-two m-b-0 m-t-10 list-inline text-center row" style="background-color: #86bab6;">
              <div class="form-group col-md-2">
                  <label for="inputEmail4" style="font-size:12px;line-height:1px;" class="text-white">Periodo</label>
                  <input type="text" class="form-control input-sm text-center" id="#2fecha" placeholder="Fecha" onkeypress="return max_length(this.value, 5);" value="<?php print date("Ym");?>">
              </div>

              <div class="form-group col-md-2">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">Quincena</label>
                  <select class="form-control input-sm" id="" style="height:60%;text-align-last:center;">
                    <?php validate_periodo_gastos();?>
                  </select>
              </div>

              <div class="form-group col-md-2">
                  <label for="inputEmail4" style="font-size:12px;line-height:1px;" class="text-white">Fecha</label>
                  <input type="text" class="form-control input-sm text-center" id="fecha" placeholder="Fecha" onkeypress="return max_length(this.value, 9);">
              </div>

              <div class="form-group col-md-2">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">Motivo</label>
                  <select class="form-control input-sm" id="" style="height:60%;text-align-last:center;">
                        <?php print _motivos_('PSJ'); ?>
                  </select>
              </div>

              <div class="form-group col-md-2">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">Tipo Documento</label>
                  <select class="form-control input-sm" id="" style="height:60%;text-align-last:center;">
                        <?php print _tipo_documentos_('FAC'); ?>
                  </select>
              </div>

              <div class="form-group col-md-2">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">Documento</label>
                  <input type="text" class="form-control input-sm text-center" onkeypress="return max_length(this.value, 14);" id="inputPassword4" placeholder="Documento">
              </div>

              <div class="form-group col-md-2">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">RUC</label>
                  <input type="text" class="form-control input-sm text-center" onkeypress="return max_length(this.value, 10);" id="inputPassword4" placeholder="RUC">
              </div>

              <div class="form-group col-md-1">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">Importe</label>
                  <input type="text" class="form-control input-sm text-center" onkeypress="return max_length(this.value, 6);" id="inputPassword4" placeholder="Importe">
              </div>

              <div class="form-group col-md-1">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">Ventas</label>
                  <input type="text" class="form-control input-sm text-center" onkeypress="return max_length(this.value, 6);" id="inputPassword4" placeholder="Ventas">
              </div>

              <div class="form-group col-md-2">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">RUC Cliente</label>
                  <input type="text" class="form-control input-sm text-center" onkeypress="return max_length(this.value, 10);" id="inputPassword4" placeholder="RUC Cliente">
              </div>

              <div class="form-group col-md-4">
                  <label for="inputPassword4" style="font-size:12px;line-height:1px;" class="text-white">Observaciones</label>
                  <textarea type="text" class="form-control input-sm text-center" maxlength="250" style="resize:none;" id="inputPassword4" placeholder="Observaciones"></textarea>
              </div>
              <div class="form-group col-md-2" style="line-height:20px;">
                  <br>
                  <button class="btn btn-sm btn-primary waves-light waves-effect col-lg-12"><span class="fa fa-plus"></span>&nbsp;Insertar</button>
                  <button class="btn btn-sm btn-danger waves-light waves-effect col-lg-12"><span class="fa fa-trash"></span>&nbsp;Borrar</button>
              </div>

          </div>

 </div>
</div>