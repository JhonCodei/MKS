<div class="row">
<div class="card-box col-lg-12">
<!-- PAGOS -->
    <?php $usuarios_ = info_usuario('usuario'); if($usuarios_ == 'zsilva' || $usuarios_ == 'admin'){ ?>

        <p class="h1 header-title">
            <b class="h2">Ruteo</b>
            <br>
            <p class="text-muted m-b-30 font-14">
        </p>
        <div class="input-group col-sm-12 col-md-10 col-lg-5">
            <span  style="border-color:#61B292;" class="input-group-addon bg-primary text-white">
                <span class="ti ti-calendar"></span>
            </span>
            <input type="text" class="form-control text-center border border-secondary" id="periodo-consulta" value="<?php print date("Ym");?>">
            <select id="quincena-consulta" class="form-control border border-secondary">
                <option value="1">Primera</option>
                <option value="2">Segunda</option>
            </select>
            <div class="input-group-append">
                <button id="btn_list" onclick="_listar_ruteo_pagos();" class="btn btn-outline-secondary btn-default text-white waves-effect waves-light" type="button" style="height:38px;">
                    <span class="fa fa-search"></span>
                </button>
            </div>
        </div>
        <hr>
        <div id="estado-ruteo-consulta"></div>
        <div id="estado-consulta">Estado: <label for="" id="estado-consulta" class="h4 font-weight-bold" style="color:#4a9ff5;"> Activo </label></div>
            <button id="btn-change-status" class="btn btn-inverse btn-sm waves-effect waves-light"><span class="fa fa-refresh"></span> Cambiar </button>
        <hr>
        <div id="_div-container_" class="table-responsive text-center"></div>

<!-- PAGOS -->  
    <?php }else{ ?>
        <p class="h1 header-title">
            <b class="h2">Ruteo</b>
            <br>
            <p class="text-muted m-b-30 font-14">
            <!-- <a href="Ruteo/Nuevo" class="btn btn-sm btn-default waves-effect waves-light" style="border-style:2px !important;"> -->
            <button id="btn_new" class="btn btn-sm btn-primary waves-effect waves-light">
                <span class="fa fa-plus"></span>&nbsp;Nuevo</button>
            </p>
        </p>
        <div class="input-group col-sm-12 col-md-10 col-lg-5">
            <span  style="border-color:#61B292;" class="input-group-addon bg-primary text-white">
                <span class="ti ti-calendar"></span>
            </span>
            <input type="text" onchange="_validate_feriados(this.value)" class="form-control text-center" id="fecha-consulta" style="border-color:#61B292;width:60% !important;" value="<?php print date("d/m/Y");?>">
            <select class="form-control" id="select_event_change" onchange="return change_event();" style="border-color:#61B292;width:50% !important;">
                <option value="_listar_ruteo()">Listado</option>      
                <option value="_buscar_ruteo()">Editar</option> 
                <option value="_eliminar_ruteo()">Eliminar</option>
            </select> 
            <div class="input-group-append">
                <button id="btn_events" class="btn btn-outline-secondary btn-default text-white waves-effect waves-light" type="button" style="height:38px;">
                    <span class="fa fa-search"></span>
                </button>
            </div>
        </div>
        <hr>
        <div id="_div-container_" class="table-responsive text-center"></div>
    <?php } ?>
<!-- PAGOS -->
    </div>
</div>
