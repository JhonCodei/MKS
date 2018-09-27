<div class="row">
    <div class="card-box col-lg-12">
        <p class="h1 header-title">
            <b class="h2">PRE-Avance Visitas</b>
            <p class="text-muted m-b-30 font-14 text-dark h5">
            <br>
            Actualizado: <?php print _last_generado_ventas();?>
            </p>
        </p>

        <div class="col-lg-12 form-inline">
            <div class="input-group col-lg-3">
                <span class="input-group-addon bg-primary text-white b-0"> Periodo </span>
                <input type="number" style="border-color:#668BA4;" 
                    value="<?php print date('Ym')?>" 
                    class="form-control text-center" 
                    placeholder="Ingrese periodo" 
                    onkeypress="return max_length(this.value, 5);" 
                    id="input_periodo">
            </div>
         
            <!-- <span class="input-group-addon bg-primary text-white b-0"> A&ntilde;o </span> -->
            <?php if(info_usuario('tipo_user') == 0 || info_usuario('tipo_user') == 1 || info_usuario('tipo_user') == 2){?>
            <div class="input-group col-lg-3">
            <select class="form-control" id="input_region" style="text-align-last:center;border-color:#668BA4;">
                <option value="8" selected>Lima</option>
                <!-- <option value="99">Especiales - Capon</option> -->
                <!-- <option value="28">Norte sur chico</option> -->
                <option value="27">Sierra Central</option>
                <option value="26">Norte 1</option>
                <option value="25">Norte 2</option>
                <!-- <option value="23">Dimexa Arequipa</option> -->
                <!-- <option value="24">Castillo</option> -->
                <!-- <option value="13">CADENAS</option> -->
            </select>
            <button class="btn btn-primary waves-effect waves-light" onclick="return listar_avance_visitas();">
                <span class="fa fa-search"></span>
            &nbsp;Consultar</button>
            <!-- <button class="btn btn-default waves-effect waves-light" onclick="return reporte();">
                <span class="fa fa-search"></span>
            &nbsp;Venta por zona</button> -->
            <!-- </div> -->
            
            </div>
            <?php }else if(info_usuario('codigo') == 520){ ?> 
            <div class="input-group col-lg-3">
                <select class="form-control" id="input_region" style="text-align-last:center;border-color:#668BA4;">
                <option value="8" selected>Lima</option>
                <option value="28">Norte sur chico</option>
                <!-- <option value="13">CADENAS</option> -->
            </select>
            <button class="btn btn-primary waves-effect waves-light" onclick="return listar_avance_visitas();">
                <span class="fa fa-search"></span>
            &nbsp;Consultar</button>
            </div>
            <?php }else if(info_usuario('codigo') == 756){ ?> 
            <div class="input-group col-lg-3">
            <select class="form-control" id="input_region" style="text-align-last:center;border-color:#668BA4;">
            <option value="23">Dimexa Arequipa</option>
                <option value="24">Castillo</option>
            <!-- <option value="13">CADENAS</option> -->
            </select>
            <button class="btn btn-primary waves-effect waves-light" onclick="return listar_avance_visitas();">
                <span class="fa fa-search"></span>
            &nbsp;Consultar</button>
            </div>
            <?php }else{ ?> 
                <div class="input-group col-lg-3">
                    <input type="hidden" 
                        value="<?php print info_usuario('region_visita');?>" 
                        id="input_region">
                    <button class="btn btn-primary waves-effect waves-light" onclick="return listar_avance_visitas();">
                    <span class="fa fa-search"></span>
                    &nbsp;Consultar</button>
                </div>
            <?php } ?>         
        </div>        
        <br><br>
        <div class="table-responsive" id="div-content-data-1"></div><br><hr>
        <div class="table-responsive" id="div-content-data-2"></div>
    </div>
</div>