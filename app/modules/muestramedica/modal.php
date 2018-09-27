<div id="modal_muestra_medica" class="modal fade show" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="font-family:verdana !important;">
    <div id="type_modal" class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header " style="heigth:10px !important;color:#232931;">
                <h4 class="modal-title mt-0 h3">Muestra médica</h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="close_modal_mm_await();">×</button> -->
                <button type="button" class="btn btn-sm btn-danger waves-effect waves-light" aria-hidden="true" onclick="close_modal_mm_await();">
                <span class="fa fa-close"></span></button>
            </div>
            <div class="modal-body">
                <div id="div_content_modal">
                </div>
                
                <div class="input-group-prepend">
                    <hr>
                <!-- <label for="" class="text-center h5 text-muted"><== Deslizar ==></label> -->
                    <table id="table-mm-add" class="table table-striped table-condensed table-responsive table-bordered table-sm" style="width: auto !important;font-size:0.85em;">
                        <thead style="background-color:#476269;" class="text-center text-white">
                            <!-- <th style='width:5% !important;'>
                                <div class="pull-left">
                                    <button type="button" onclick="return newElement(event);" class="btn btn-primary btn-sm waves-effect waves-light"><span class="fa fa-plus"></span></button>
                                </div>
                            </th> -->
                            <!-- <a class="btn btn-default waves-effect waves-light autohidebut" href="javascript:;" onclick="$.Notification.autoHideNotify('custom', '#hola', 'I will be closed in 5 seconds...','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vitae orci ut dolor scelerisque aliquam.')">Custom</a> -->
                            <!-- colspan="2" -->
                            
                            <th class="text-center text-white">
                                <div class="pull-left input-group">
                                    <button type="button" onclick="return newElement(event);"
                                    class="btn btn-primary btn-sm waves-effect waves-light">
                                        <span class="fa fa-plus"></span>
                                    </button>                             
                                    <label for="" style="padding-top: 6px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CodProd&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </div>
                            </th>
                            <th class="text-center text-white" style='width:20% !important;'>&nbsp;Cantidad&nbsp;</th>
                            <th class="text-center text-white" style='width:40% !important;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Producto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-center text-white" style='width:20% !important;'> &nbsp;&nbsp;&nbsp;Stock&nbsp;&nbsp;&nbsp;</th>
                            <th class="text-center text-white" style='width:20% !important;'>&nbsp;&nbsp;Nuevo Stock&nbsp;&nbsp;</th>
                        </thead>
                        <tbody id="parent-div" class="display:block;">
                            <tr id="tr1">
                                <!-- <td>
                                    <button class="btn btn-sm btn-danger waves-effect waves-light"  onclick="removeElement('tr1');"><span class="fa fa-minus"></span></button>
                                </td> -->
                                <td >
                                    <div class="input-group bootstrap-touchspin">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default btn-sm bootstrap-touchspin-down waves-effect waves-light" onclick="return _buscar_producto(1);">
                                                <span class="fa fa-search"></span>
                                            </button>
                                        </span>
                                        <input type="number" name="prod_cod[]" onkeypress="return max_length(this.value, 14);" class="form-control input-sm border-input" placeholder="codigo" id="prod_cod_1" >
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="form-group" >
                                        <input name="prod_cant[]" type="number" onkeypress="return max_length(this.value, 2);" class="form-control input-sm text-center border-input" onblur="return onblur_stock_actual(1);" id="cant_1" placeholder="cantidad">
                                    </div>
                                </td>
                                <td style="width:60% !important;">
                                    <div class="form-group">
                                        <input name="prod_desc[]" type="text" class="form-control input-sm text-center border-input" readonly="true" id="prod_desc_1" placeholder="descripcion">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input name="prod_stock[]" type="text" class="form-control input-sm text-center border-input" readonly="true" id="stock_1" placeholder="stock">
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input name="prod_stock_actual[]" type="text" class="form-control input-sm text-center border-input" readonly="true" id="stk_act_1" placeholder="stock actual">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                
                </div>
               </div>
        </div>
    </div>
</div>
<!-- ADD MM -->
<!-- MODAL MARCAR -->
<div id="modal_marcar_sitio" class="modal fade show" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="padding-right: 10px;padding-left: -10px;font-family:verdana !important;">
    <div id="type_modal" class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header" style="heigth:10px !important;color:#232931;">
                <h4 class="modal-title mt-0 h3">Marcar Sitio</h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="close_modal_mm_await();">×</button> -->
                <button type="button" class="close" aria-hidden="true" onclick="close_modal_mm_await();">×</button>
            </div>
            <div class="modal-body">
                <div id="div_content_modal_marcar"></div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL MARCAR -->
<!-- EVENTOS MODALS -->
<div id="modal-events-mm" class="modal fade show" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- <div class="pull-right"> -->
                <!-- <h4 class="modal-title mt-0">Listado</h4> -->
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="return close_modal('modal-events-mm');"><label for="" style="font-size:40px !important;">×</label></button> -->
            <!-- </div> -->
            <div class="modal-body">
                <div id="table-events-mm"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal" onclick="return close_modal('modal-events-mm');">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- EVENTOS MODALS -->

<div class="modal fade" id="modal-medicos-x-dia"  data-keyboard="false" data-backdrop="static" tabindex="-1000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="h3 modal-title mt-0">Medicos visitados</h3>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                  <div class="col-xs-12 text-center">
                     <div class="table-responsive" id="table-modal-medicos-dia">
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<!-- PASTE -->


<!-- MODAL PRONOSTICO -->
<div class="modal fade" id="modal-cobertura-no-visitados" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="h3 modal-title mt-0">Medicos pronostico</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                  <div class="col-lg-6">
                    <strong style="font-size:0.95em;">
                        <label class="h5"> Especialidad: <b id="espec"></b></label><br>
                        <label class="h5">Categoria:  <b id="categ"></b> </label>
                     </strong>
                  </div>

                  <div class="col-lg-12">
                     <div class="table-responsive">
                        <table style="font-weight:bold;" class="table text-center table-condensed table-bordered dt-responsive nowrap table-sm" cellspacing="0" width="100%">
                           <thead style="background-color: #36404A;">
                              <th  style="color: white;font-size:0.85em;" class="text-center">Medico</th>
                              <!-- <th  style="color: white;font-size:0.7em;" class="text-center">Especialidad</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Categoria</th> -->
                              <th  style="color: white;font-size:0.85em;" class="text-center">Distrito</th>
                              <th  style="color: white;font-size:0.85em;" class="text-center">Direcci&oacute;n</th>
                              <th  style="color: white;font-size:0.85em;" class="text-center">Visita</th>
                           </thead>
                           <tbody id="modal-table-medicos-no-visitados"></tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL PRONOSTICO -->
<!-- MODAL REALIZADO coberturarealizada-->
<div class="modal fade" id="modal-cobertura-visitados" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="h3 modal-title mt-0">Medicos visitados</h3>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                    <div class="col-lg-6">
                        <strong style="font-size:0.95em;">
                            <label class="h5"> Especialidad: <b id="espec2"></b></label><br>
                            <label class="h5">Categoria:  <b id="categ2"></b> </label>
                        </strong>
                    </div>
                  <div class="col-xs-12 text-center">
                     <div class="table-responsive">
                        <table style="font-weight:bold;" class="table text-center table-condensed table-bordered dt-responsive nowrap table-sm" cellspacing="0" width="100%">
                           <thead style="background-color: #36404A;">
                              <th  style="color: white;font-size:0.7em;" class="text-center">Medicos</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Visitas</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Fechas visitadas</th>
                           </thead>
                           <tbody id="modal-table-medicos-visitados"></tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL REALIZADO -->

<!-- PASTE -->

<!-- MODAL REALIZADO_FECHA_PRODUCTOS -->
<div class="modal fade" id="modal-productos-fechas" style="overflow-y: scroll;" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="h3 modal-title mt-0">Productos entregados</h3>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                    <div class="col-lg-12">
                        <strong style="font-size:0.95em;">
                            <label class="h5"> Medico: <b id="medico_desc"></b></label><br>
                        </strong>
                    </div>
                  <div class="col-lg-12">
                     <strong style="font-size:0.8em;"><b id="all_detalle_med"> </b></strong>
                     <div class="table-responsive">
                        <table style="font-weight:bold;" class="table text-center table-condensed table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                           <thead style="background-color: #36404A;">
                              <th  style="color: white;font-size:0.7em;" class="text-center">Productos</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Cantidad</th>
                           </thead>
                           <tbody id="modal-table-producto-detalle-fecha"></tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL REALIZADO_FECHA_PRODUCTOS -->