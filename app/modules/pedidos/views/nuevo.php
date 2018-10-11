<div class="row">
        <div class="card-box col-md-12">
        <p class="h1 header-title">
        <a href="javascript:void(0)" onclick="close_modal_await()" class="h2 waves-effect waves-light" style="color:#0CC243;">
        <span class="fa fa-arrow-circle-left"></span></a> &nbsp;<b class="h2 text-dark" >Nuevo Pedido</b>
        </p>
          <div class="row">
            <div class="col-md-4">
                <div class="input-group"> 
                    <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Fecha</span>
                    <input type="text" id="fecha"  class="form-control text-center border border-secondary" value="<?php print date("d/m/Y"); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group"> 
                    <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Cond. pago</span>
                    <select class="form-control border border-secondary" id="condicion_pago">
                    <?php print  condicion_pago('CE'); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group"> 
                    <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Distribuidora</span>
                    <select class="form-control border border-secondary" id="distribuidora">
                    <?php  print distribuidoras(0); ?>
                    </select>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Ruc</span>
                    <span class="input-group-addon bg-success text-white font-weight-bold border border-success waves-light waves-effect" onclick="return _buscar_cliente();">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" id="cliente_ruc" class="form-control text-center border border-secondary" onkeypress="return max_length(this.value, 10);" placeholder="N° Ruc">
                    <span class="input-group-addon bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" onclick="__clear__('cliente_ruc,cliente_name');">
                        <i class="fa fa-trash"></i>
                    </span>  
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Cliente</span>
                    <input type="text" id="cliente_name" class="form-control text-center border border-secondary" readonly="true" placeholder="Razón social">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Codigo</span>
                    <span class="input-group-addon bg-success text-white font-weight-bold border border-success waves-light waves-effect" onclick="return _buscar_vendedor();"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control text-center border border-secondary" id="cod_vend" onkeypress="return max_length(this.value, 3);" placeholder="-">
                    <span class="input-group-addon bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" onclick="__clear__('cod_vend,name_vend');"><i class="fa fa-trash"></i></span>  
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
                <div class="input-group"> 
                    <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Vendedor</span>
                    <input type="text" class="form-control text-center border border-secondary" readonly="true" placeholder="-" id="name_vend">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group"> 
                    <button  type="button" onclick="return _insertar_pedido();" id="button_on_insert" class="col-lg-6 btn btn-default waves-effect waves-light border border-secondary">
                    <span class="fa fa-plus"></span>&nbsp;Guardar</button>
                            <!-- return back page -->
                    <button onclick="close_modal_await()" type="button" class="col-lg-6 btn btn-danger waves-effect waves-light border border-secondary">
                    <span class="fa fa-close"></span>&nbsp;Cancelar</button>
                </div>
            </div>
            <div class="col-md-4">
              <table class="table table-sm text-center">
                <thead style="background-color:#476269;font-size:0.75em;" class="text-center text-white">
                  <tr>
                    <th class="text-center">Monto Bruto</th>
                    <th class="text-center">Descuento</th>
                    <th class="text-center">Monto Imponible</th>
                    <th class="text-center">IGV</th>
                    <th class="text-center">Total</th>
                  </tr>
                </thead>
                <tbody  style="font-size:0.85em;color:black;">
                    <tr>
                        <td class="text-center border-prsnl2" id="sm_m_b">0.00</td>
                        <!-- <td class="text-center border-prsnl2">0.00</td> -->
                        <td class="text-center border-prsnl2" id="sm_desc">0.00</td>
                        <!-- <td class="text-center border-prsnl2">0.00</td> -->
                        <td class="text-center border-prsnl2" id="sm_m_i">0.00</td>
                        <td class="text-center border-prsnl2" id="sm_igv">0.00</td>
                        <td class="text-center border-prsnl2 bck_total" id="sm_total">0.00</td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
                <div class="checkbox checkbox-primary">
                    <input id="checkbox1" type="checkbox">
                    <label for="checkbox1" style="color:black;">Precio Especial</label>
                </div>
            </div>
            <div class="col-md-8">
                <textarea name="" id="notas" class="form-control text-center border border-secondary" placeholder="observaciones" onkeypress="return max_length(this.value, 200);" cols="100" rows="10"></textarea>
            </div>
          </div>
            <div class="col-lg-12">
            <br>
                <label for="" class="text-center h5 font-weight-bold text-dark">Estado: 
                <label id="lbl_status" class="text-center h6 font-weight-bold text-primary">[ - INICIADO - ]</label></label>
            </div>
          <!-- tabla eventos  -->
          <div class="row">
          <div class="pt-1 table-responsive" style="width:100% !important;">
              <table id="table-pedidos-add" class="table table-striped table-condensed table-bordered table-sm"> 
                <thead style="background-color:#476269;" class="text-center text-white">
                  <tr>
                    <th class="text-center text-white" style="width:1% !important;">
                        <div class="pull-left">
                            <button type="button" onclick="return newElement(event, 2, 'tr', 'insertar');" class="btn btn-sm btn-primary waves-effect waves-light">
                                <span class="fa fa-plus"></span>
                            </button>
                        </div>
                    </th>
                    <th style="width: 10% important;" class="text-center text-white">
                        <label for="">Producto</label>
                    </th>
                    <th style="width: 10% important;" class="text-center text-white">
                        <label for="">Precios</label>
                    </th>
                    <th style="width: 10% important;" class="text-center text-white">
                        <label for="">Valores</label>
                    </th>
                  </tr>
                </thead>
                <tbody id="parent-div_insertar">
                  <tr id="tr1">
                    <td style="width:1% !important;">
                        <button class="btn btn-sm btn-danger waves-effect waves-light"  onclick="elementRemove('tr1');elementRemove('2tr1');">
                            <span class="fa fa-minus"></span>
                        </button>
                    </td>
                    <td style="width:40% !important;">
                      <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-primary text-white border border-primary waves-light waves-effect" onclick="_buscar_producto(1);" ><i class="fa fa-search"></i></span>
                                <input type="number" style="font-size:0.75em !important;" onblur="precio_lista_x_prod_x_dist(1);_buscar_producto(1);" onchange="precio_lista_x_prod_x_dist(1);" class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cod." name="prod_cod_insertar[]" id="prod_cod_1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <span class="input-group-addon input-sm bg-inverse text-white border border-dark inp-g-ad_alter">Producto</span>                                
                                <textarea readonly="true" style="width: 100%;height:35px;font-size:0.7em !important;resize:none;" class="font_inside_input input-sm" id="prod_desc_1" name="prod_desc_insertar[]" ></textarea>
                            </div>
                        </div>
                        <div class="col-md-4" >
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-inverse text-white border border-dark inp-g-ad_alter">Cant.</span>
                                <input type="number" class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cant."
                                    id="cant_1" name="prod_cant_insertar[]" onchange="precio_lista_x_prod_x_dist(1);"  onblur="precio_lista_x_prod_x_dist(1);"  onkeypress="return max_length(this.value, 4);">
                            </div>
                        </div>
                      </div>
                    </td>
                    <!-- style="width:25% !important;" -->
                    <td style="width:30% !important;">
                      <div class="row" >
                        <div class="col-md-3">
                          <form class="form-inline">
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Prec.<br>list.</span>
                                <input type="text" style="font-size:0.8em !important;" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_list_1" name="prec_list_insertar[]">
                            </div>
                          </form>
                        </div>
                        <div class="col-md-3">
                          <form class="form-inline">
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Desc.<br>%</span>
                                <input type="text" style="font-size:0.8em !important;" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="porc_desc_1" name="porc_desc_insertar[]">
                            </div>
                          </form>
                        </div>
                        <div class="col-md-3">
                          <form class="form-inline">
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Prec.<br>Uni.</span>
                                <input type="text" style="font-size:0.8em !important;" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_unidad_1" name="prec_unidad_insertar[]">
                            </div>
                          </form>
                        </div>
                        <div class="col-md-3">
                          <form class="form-inline">
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">P.U.<br>+IGV</span>
                                <input type="text" style="font-size:0.8em !important;" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_uni_igv_1" name="prec_uni_igv_insertar[]">
                            </div>
                          </form>
                        </div>
                      </div>
                    </td>
                    <td style="width:30% !important;">
                      <div class="row">
                        <div class="col-md-4">
                          <form class="form-inline">
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Monto<br>Desc.</span>
                                <input type="text" readonly="true" style="font-size:0.8em !important;" class="form-control input-sm text-center border border-secondary font_inside_input" id="monto_desc_1" name="monto_desc_insertar[]">
                            </div>
                          </form>
                        </div>
                        <div class="col-md-4">
                          <form class="form-inline">
                            <div class="input-group">
                                <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Valor<br>Neto</span>
                                <input type="text" readonly="true" style="font-size:0.8em !important;" class="form-control input-sm text-center border border-secondary font_inside_input" id="valor_neto_1" name="valor_neto_insertar[]">
                            </div>
                          </form>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <span class="input-group-addon input-sm bg-inverse text-white border border-secondary inp-g-ad_alter">Monto</span>
                                <input type="text" readonly="true" style="font-size:0.8em !important;" class="form-control input-sm text-center border border-secondary font_inside_input" id="monto_line_total_1" name="monto_line_total_insertar[]" />
                            </div>
                        </div>
                        
                      </div>
                    </td>
                  </tr>
                  <tr id="2tr1">
                    <td></td>
                    <td colspan="3">
                        <input type="text" class="form-control border border-secondary" placeholder="observaciones" onkeypress="return max_length(this.value, 130);" id="obs_line_1" name="obs_line_insertar[]">
                    </td>
                  </tr>                
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
