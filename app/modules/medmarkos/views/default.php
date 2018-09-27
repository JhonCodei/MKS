<div class="row">
    <div class="card-box col-lg-12">
        <p class="h1 header-title">
            <h2><b>MedMarkos</b></h2>
            <p class="text-muted m-b-30 font-14">Reporte, indicadores de medicos.</p>
        </p>
        <div class="row">
        <?php if($_SESSION['user_user'] == 'admsin'){?>
        <div class="col-lg-12">
            <form id="formdata" class=" form-inline btn-group">
                <input type="file" id="excel" name="excel"  style="border-color:blue;" class="filestyle col-lg-6" data-buttontext="Explorar">
                <select class="text-center form-control" id="tipo" style="border-color:blue;line-height:10px;">
                    <option value="movimiento">movimiento</option>
                    <option value="medicos">medicos</option>
                    <option value="medicos_x_repre" selected>medicos por repre</option>
                </select>
                <input type="text" id="periodoinp" style="border-color:blue;" placeholder="periodo" class="text-center form-control">
                <button type="submit" class="btn btn-primary waves-effect waves-light">
                    <span class="fa fa-load"></span> Cargar
                </button>
            </form>
         </div>
        <?php } ?>
        <!-- input search --> 
        </div>
        <?php if($_SESSION['user_user'] == 'admin'){?>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-11">
                <form id="formdata" class="form-inline btn-group">
                    
                    <input type="file" id="excel" name="excel"  style="border-color:blue;" class="filestyle col-lg-6" data-buttontext="Explorar">
                    
                    <select class="text-center form-control" id="tipo" style="border-color:blue;line-height:10px;">
                        <option value="movimiento">movimiento</option>
                        <option value="medicos">medicos</option>
                        <option value="medicos_x_repre" selected>medicos por repre</option>
                    </select>

                    <input type="text" id="periodoinp" style="border-color:blue;" placeholder="periodo" class="text-center form-control">
                    
                    <button type="submit" class="btn btn-primary waves-effect waves-light"><span class="fa fa-load"></span> Cargar</button>
                    
                </form>
            </div>
        </div>
        <?php } ?>
        <div class="row">

            <div class="col-md-1"></div>
            <div class="col-md-2" style="">
                <div class="form-group" style="position: static;">
                    <label for="field-1" class="control-label" style="color:#404969;">Periodo</label>
                    <input type="number" class="form-control text-center" id="periodo" value="<?php print date("Ym");?>" style="border-color:#588D9C;" onblur="listar_representantes();">
                </div>
            </div>
            <?php if(select_box_region_x_sup(4) == 0 && info_usuario('codigo') != 520){?>
            
            <div class="col-md-2" style="">
                <div class="form-group" style="position: static;">
                    <label for="field-1" class="control-label" style="color:#404969;">Region</label>
                    <select class="form-control" id="region_src" style="border-color:#588D9C;" onchange="return listar_representantes();">                       
                        <option value="T">Todos</option>
                        <option value="8" selected>Lima</option>
                        <option value="28">Norte sur chico</option>
                        <option value="27">Sierra central</option>
                        <option value="26">Norte 1</option>
                        <option value="25">Norte 2</option>
                        <option value="23">Dimexa Arequipa</option>
                        <option value="24">Castillo Arequipa</option>
                    </select>
                </div>
            </div>
            <?php } else if(select_box_region_x_sup(4) == 2 && info_usuario('codigo') == 520){?>
                <div class="col-md-2" style="">
                <div class="form-group" style="position: static;">
                    <label for="field-1" class="control-label" style="color:#404969;">Region</label>
                    <select class="form-control" readonly="true" id="region_src" style="border-color:#588D9C;cursor: not-allowed;" onchange="return listar_representantes();">                       
                        <option value="8,28,25" selected>Lima</option>
                    </select>
                </div>
            </div>
            <!-- <input type="hidden" id="region_src" value="8,28,25" onchange="return listar_representantes();"> -->
            <?php } else{?>
                <div class="col-md-2" style="">
                <div class="form-group" style="position: static;">
                    <label for="field-1" class="control-label" style="color:#404969;">Region</label>
                    <select class="form-control"  readonly="true" id="region_src" style="border-color:#588D9C;cursor: not-allowed;" onchange="return listar_representantes();">                       
                        <option value="<?php print select_box_region_x_sup(3);?>" selected><?php print ucfirst(search_region_name(select_box_region_x_sup(2))) ;?></option>
                    </select>
                </div>
            </div>
            <!-- <div class="col-md-2"></div>
            <input type="hidden" id="region_src" value="<?php #print select_box_region_x_sup(3);?>" onchange="return listar_representantes();"> -->
            <?php } ?>

            <div class="col-md-6" style="">
                <div class="form-group" style="position: static;">
                    <label for="field-1" class="control-label" style="color:#404969;">Grupal</label><br>

                    <button type="button" class="btn btn-danger waves-effect waves-light btn-lg" onclick="return table_medicos_x_dia();">
                    <span class="fa fa-address-card-o"></span>&nbsp;Calendario médico</button>

                    <button type="button" class="btn btn-primary waves-effect waves-light btn-lg" onclick="return propagandistas_muestras();">
                    <span class="fa fa-address-card-o"></span>&nbsp;Reporte Visitadores</button>

                    <!-- <button type="button" class="btn btn-primary waves-effect waves-light btn-lg" onclick="return table_stock_count_all();">+
                    <span class="fa fa-address-card-o"></span>&nbsp;Stock Total</button> -->
                    <!-- <button type="button" class="btn btn-default waves-effect waves-light btn-lg" onclick="return medicos_productos_cantidad_periodo();">
                        <span class="fa fa-address-card-o"></span>&nbsp;Reporte producto cantidad</button> -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label  style="color:#404969;" for="field-1" class="control-label">Representante</label>
                    <!-- <select class="form-control input-md" id="representantes" style="z-index:100;background-color:white !important;border-color:#588D9C;text-align-last:center;" data-live-search="true" data-size="auto"></select> -->
                    <select class="form-control" id="representantes" style="z-index:100;background-color:white !important;border-color:#588D9C;text-align-last:center;" data-live-search="true" data-size="auto"></select>
                </div>
            </div>
            <div class="col-md-8" style="">
                <div class="form-group" style="position: static;">
                    <label for="input-text-1" style="color:#404969;">Acciones</label><br>
                    <button type="button" class="btn btn-inverse waves-effect waves-light btn-lg" onclick="return ficha_medica();">
                    <span class="fa fa-hospital-o"></span>&nbsp;Ficha medica</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light btn-lg" onclick="return cobertura();">
                    <span class="fa fa-ambulance"></span>&nbsp;Cobertura</button>
            <!-- <button type="button" class="btn btn-inverse waves-effect waves-light btn-lg" style="font-family:verdana;" onclick="return table_realizadas_detalle();"><span class="fa fa-list-alt"></span>&nbsp;Detalle visitas</button> -->

                    <button type="button" class="btn btn-success waves-effect waves-light btn-lg" onclick="return table_stock_count();">
                    <span class="fa fa-address-card-o"></span>&nbsp;Stock x día</button>
                    <button type="button" class="btn btn-purple waves-effect waves-light btn-lg" onclick="return stock_entregado_resumen();">
                    <span class="fa fa-address-card-o"></span>&nbsp;Stock Resumen</button>
                </div>
            </div>
        </div>
            
            
        
        <div id="content-data" class="col-lg-12 table-responsive">
                <hr>
                
                <div id="ficha-medica-data"></div>
                <div id="cobertura-data" style="font-size:1em !important;" class="table-responsive text-center"></div>
                <input type="hidden" id="name_repre" />
                <div id="div-cobertura-realizada-detalle-data" class="table-responsive text-center"></div>
                <br><hr>
                <div id="div-detalle-data-maestro-medico" class="table-responsive text-center"></div>
        </div>
        <div id="event"></div>
    </div>
</div>