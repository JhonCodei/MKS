<div class="row" >
    <div class="card-box col-lg-12">
        <p class="h1 header-title">
        <a href="./" class="h2 waves-effect waves-light" style="color:#0CC243;">
        <span class="fa fa-arrow-circle-left"></span></a> &nbsp;<b class="h2 text-dark" >Editar Pedido</b>

        </p>
        <div class="justify-content-center">
            <div class="media-container-column" data-form-type="formoid">                 
                <div class="mbr-form text-center" data-form-title="">
                    <div class="row row-sm-offset">
                    <label for="" id="lbl_id"></label>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Fecha</span>
                            <input type="text" id="fecha"  class="form-control text-center border border-secondary" value="<?php print date("d/m/Y"); ?>">
                        </div>

                        <div class="input-group col-md-4">
                            <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Cond. pago</span>
                            <select class="form-control border border-secondary" id="condicion_pago">
                            <?php print  condicion_pago('CE'); ?>
                            </select>
                        </div>
                        <div class="input-group col-md-4">
                            <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Distribuidora</span>
                            <select class="form-control border border-secondary" id="distribuidora">
                                <?php  print distribuidoras(0); ?>
                            </select>
                        </div> 

                        <div class="input-group col-md-4">
                            <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Ruc</span>
                            <span class="input-group-addon bg-success text-white font-weight-bold border border-success waves-light waves-effect">
                                <i class="fa fa-search" onclick="return _buscar_cliente();"></i></span>
                            <input type="text" id="cliente_ruc" class="form-control text-center border border-secondary"
                                        onkeypress="return max_length(this.value, 10);" placeholder="N° Ruc">
                            <span class="input-group-addon bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" 
                                        onclick="__clear__('cliente_ruc,cliente_name');">
                                <i class="fa fa-trash"></i></span>    
                                <!-- onclick="return clear_text('cliente_ruc','cliente_name');"> -->
                        </div>

                        <div class="input-group col-md-4">
                            <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Cliente</span>
                            <input type="text" id="cliente_name" class="form-control text-center border border-secondary" readonly="true" placeholder="Razón social">
                        </div>

                        <div class="input-group col-md-4">
                            <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Codigo</span>
                            <span class="input-group-addon bg-success text-white font-weight-bold border border-success waves-light waves-effect" 
                                        onclick="return _buscar_vendedor();"><i class="fa fa-search"></i></span>

                            <input type="text" class="form-control text-center border border-secondary" id="cod_vend"
                                        onkeypress="return max_length(this.value, 3);" placeholder="-">
                            <span class="input-group-addon bg-danger text-white font-weight-bold border border-danger waves-light waves-effect"
                                        onclick="__clear__('cod_vend,name_vend');"><i class="fa fa-trash"></i></span>    
                        </div>

                        <div class="input-group col-md-4 ">
                            <span class="input-group-addon bg-primary text-white font-weight-bold border border-primary">Vendedor</span>
                            <input type="text" class="form-control text-center border border-secondary" readonly="true" placeholder="-" id="name_vend">
                        </div>

                        <div class="input-group col-md-4 ">
                            <button  type="button" onclick="return _insertar_pedido();" id="button_on_insert"
                                            class="col-lg-6 btn btn-default waves-effect waves-light border border-secondary">
                            <span class="fa fa-plus"></span>&nbsp;Guardar</button>
                            <!-- return back page -->
                            <button type="button" class="col-lg-6 btn btn-danger waves-effect waves-light border border-secondary">
                            <span class="fa fa-close"></span>&nbsp;Cancelar</button>
                            
                        </div>
                        <div class="col-md-4 table-responsive text-center">
                            <table class="" style="font-weight: bold;width:100%">
                                <thead style="background-color:#476269;font-size:0.75em;" class="text-center text-white">
                                    <th class="text-center border-prsnl2">Monto Bruto</th>
                                    <!-- <th class="text-center border-prsnl2">% Dscto.</th> -->
                                    <th class="text-center border-prsnl2">Descuento</th>
                                    <!-- <th class="text-center border-prsnl2">Monto Isc.</th> -->
                                    <th class="text-center border-prsnl2">Monto Imponible</th>
                                    <th class="text-center border-prsnl2">IGV</th>
                                    <th class="text-center border-prsnl2">Total</th>
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

                        <div class="pt-1 table-responsive" style="width:100% !important;">
                        <br>
                        <label for="" class="text-center h5 font-weight-bold text-dark">Estado: <label id="lbl_status" class="text-center h6 font-weight-bold text-primary">[ - INICIADO - ]</label></label>
                            
                            <table id="table-pedidos-add" class="table table-striped table-condensed table-bordered table-sm">
                            <thead style="background-color:#476269;" class="text-center text-white">
                                <th class="text-center text-white" style="width:1% !important;">
                                    <div class="pull-left">
                                    <button type="button" onclick="return newElement(event, 2, 'tr', 'insertar');" class="btn btn-sm btn-primary waves-effect waves-light">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                    </div>
                                </th>
                                <!-- <a class="btn btn-default waves-effect waves-light autohidebut" href="javascript:;" onclick="$.Notification.autoHideNotify('custom', '#hola', 'I will be closed in 5 seconds...','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas vitae orci ut dolor scelerisque aliquam.')">Custom</a> -->
                                <!-- colspan="2" -->
                                <th class="text-center text-white">
                                    <label for="" style="padding-top: 6px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Producto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </th>
                                <th class="text-center text-white">
                                    <label for="" style="padding-top: 6px !important;">&nbsp;Precios&nbsp;</label>
                                </th>
                                <th class="text-center text-white">
                                    <label for="" style="padding-top: 6px !important;">&nbsp;&nbsp;&nbsp;&nbsp;Valores&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                </th>
                            </thead>

                                <tbody id="parent-div_insertar">
                                    <tr id="tr1">
                                        <!-- button restar -->
                                        <td>
                                            <button class="btn btn-sm btn-danger waves-effect waves-light"  onclick="elementRemove('tr1');">
                                            <span class="fa fa-minus"></span></button>
                                        </td>
                                        <!-- button restar -->
                                        <!-- button fila productos onblur="precio_lista_x_prod_x_dist(1);"-->
                                        <td>
                                            <div class="input-group">
                                            <span class="input-group-addon input-sm bg-primary text-white font-weight-bold border border-primary waves-light waves-effect"
                                                        onclick="_buscar_producto(1);" ><i class="fa fa-search"></i></span>
                                                <input type="number"  onblur="precio_lista_x_prod_x_dist(1);_buscar_producto(1);" onchange="precio_lista_x_prod_x_dist(1);" class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cod."
                                                            name="prod_cod_insertar[]" id="prod_cod_1">
                                            </div>
                                            <div style="border:1px solid black;color:black;">
                                            <span class="input-group-addon input-sm bg-inverse text-white  font-weight-bold border border-secondary inp-g-ad_alter">Producto</span>
                                                <!-- <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Producto"
                                                            id="prod_desc_1" name="prod_desc_insertar[]"> -->
                                                <label class="label_input_producto" id="prod_desc_1" name="prod_desc_insertar[]">-</label>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-dark inp-g-ad_alter">Cant.</span>
                                                <input type="number" class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cant."
                                                            id="cant_1" name="prod_cant_insertar[]" onchange="precio_lista_x_prod_x_dist(1);"  onblur="precio_lista_x_prod_x_dist(1);"  onkeypress="return max_length(this.value, 4);">
                                                            <!-- onblur="precio_lista_x_prod_x_dist(1);" -->
                                            </div>
                                        </td>
                                        <!-- button fila productos -->

                                        <!-- button fila INFO -->
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-secondary inp-g-ad_alter">Prec.<br>list.</span>
                                                <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_list_1" name="prec_list_insertar[]">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-secondary inp-g-ad_alter">Desc.<br>%</span>
                                                <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="porc_desc_1" name="porc_desc_insertar[]">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-secondary inp-g-ad_alter">Prec.<br>Uni.</span>
                                                <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_unidad_1" name="prec_unidad_insertar[]">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-secondary inp-g-ad_alter">P.U.<br>+IGV</span>
                                                <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="prec_uni_igv_1" name="prec_uni_igv_insertar[]">
                                            </div>
                                        </td>
                                        <!-- button fila INFO -->
                                        <!-- button fila VALORES -->
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-secondary inp-g-ad_alter">Monto<br>Desc.</span>
                                                <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="monto_desc_1" name="monto_desc_insertar[]">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-secondary inp-g-ad_alter">Valor<br>Neto</span>
                                                <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="valor_neto_1" name="valor_neto_insertar[]">
                                            </div>
                                            <div>
                                                <span class="input-group-addon input-sm bg-inverse text-white font-weight-bold border border-secondary inp-g-ad_alter">Monto</span>
                                                <input type="text" readonly="true" class="form-control input-sm text-center border border-secondary font_inside_input" id="monto_line_total_1" name="monto_line_total_insertar[]">
                                            </div>
                                        </td>
                                        <!-- button fila VALORES -->
                                    </tr>
  
                                </tbody>
                            </table>
                        </div>
                        


                    <!-- CONTENIDO -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>