<div class="row">
   <div class="card-box col-lg-12">
      <div class="text-center h3">Ruteo - Diario</div>
      <br>
      <div class="input-group col-sm-12 col-md-10 col-lg-5">
         <span  style="border-color:#61B292;" class="input-group-addon bg-primary text-white">
         <span class="ti ti-calendar"></span>
         </span>
         <input type="text" class="form-control text-center" id="fecha-consulta" style="border-color:#61B292;width:60% !important;" value="<?php print date("d/m/Y");?>">
         <!-- <select class="form-control" id="select_event_change" onchange="return change_event();" style="border-color:#61B292;width:50% !important;">
            <option value="#">Listado</option> -->
         </select>
         <div class="input-group-append">
            <button id="event_button_" class="btn btn-outline-secondary btn-default text-white waves-effect waves-light" type="button" style="height:38px;"> <span class="fa fa-search"></span></button>
         </div>
      </div>
      <hr>
      <button onclick="alert(1);" class="btn btn-default waves-effect waves-light btn-sm" id="btn_add">
      <span  class="fa fa-plus"></span></button>&nbsp;&nbsp;<label for="btn_add" style="color:black;">Agregar</label>
      <div class="row" id="parent">

         <div class="col-lg-6" id="parent-div_insertar">       
            <table class="table table-condensed table-sm">
               <thead>
                  <tr>
                     <th colspan="2" style="width:auto;"></th>
                     <th style="width:80%;"></th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td class="bg-primary input-sm text-white font-weight-bold border-primary " style="border-radius:0px;">
                        <button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="alert(0);">
                        <span class="fa fa-minus"></span></button>
                     </td>
                     <td class="text-right bg-primary input-sm text-white font-weight-bold border-primary align-middle" style="border-radius:0px;">
                        HORA&nbsp;&nbsp;
                     </td>
                     <td>
                     <div class="input-group">
                      <input id="timepicker2" name="xx[]" type="text" class="form-control input-sm text-center border border-secondary">
                      <span class="input-group-addon input-sm bg-primary text-white font-weight-bold border border-primary waves-light waves-effect" onclick="__clear__('cliente_ruc,cliente_name');">
                           <i class="md md-access-time"></i>
                           </span>
                     </div>
                    </td>
                  </tr>
                  <tr>
                     <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm">CLIENTE&nbsp;&nbsp;
                        <span class="input-group-addon input-sm bg-success text-white font-weight-bold border border-white waves-light waves-effect">
                        <i class="fa fa-search" onclick="return _buscar_cliente();"></i>
                        </span>
                     </td>
                     <td>
                        <div class="input-group">
                           <input style="width:20% !important;" pattern="[0-9.]+" type="number" size="5" maxlength="11" id="cliente_ruc" class="form-control input-sm text-center border border-secondary font_inside_input" onkeypress="return max_length(this.value, 10);validateNumber(event);" placeholder="N° Ruc">
                           <input style="width:30% !important;" type="text" readonly="true" id="cliente_name" class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cliente">
                           <span class="input-group-addon input-sm bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" onclick="__clear__('cliente_ruc,cliente_name');">
                           <i class="fa fa-trash"></i>
                           </span>
                        </div>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBJETIVO&nbsp;&nbsp;
                     </td>
                     <td><select name="" id="" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;"><?php print _objetivos_(0);?></select></td>
                  </tr>
                  <tr>
                     <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">IMPORTE&nbsp;&nbsp;</td>
                     <td>
                        <input type="number" class="form-control input-sm border border-secondary font_inside_input" onkeypress="return max_length(this.value, 2);" id="">
                     </td>
                  </tr>
                  <tr>
                     <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBSERVACI&Oacute;N&nbsp;&nbsp;</td>
                     <td><textarea class="input-sm border border-secondary font_inside_input" style="width: 100%;height:35px;font-size:0.7em !important;resize:none;" id="" cols="30" rows="10"></textarea></td>
                  </tr>
                  <tr>
                     <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">TIPO&nbsp;&nbsp;</td>
                     <td>
                        <select name="" id="" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">
                           <option value="s">SI</option>
                           <option value="n">NO</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td colspan="3" class="jumbotron"></td>
                  </tr>
               </tbody>
            </table>
         </div>
         

         
         
      </div>
   </div>
</div>