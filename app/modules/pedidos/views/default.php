<div class="row">
<div class="card-box col-lg-12">

    <p class="h1 header-title">
        <b class="h2">Pedidos</b>
        <p class="text-muted m-b-30 font-14">
        <a href="Pedidos/Nuevo" class="btn btn-sm btn-default waves-effect waves-light" style="border-style:2px !important;">
            <span class="fa fa-plus"></span>&nbsp;Nuevo</a>
        </p>
    </p>
    <?php if($_SESSION['user_user'] == 'admin'){?>
        <div class="input-group col-lg-3">
        <input type="text" id="cod_repre_go" class="form-control" placeholder="codigo_representante"/>
        <input type="text" id="periodo_reload" class="form-control" placeholder="Periodo"/>
        <button class="btn btn-primary btn-sm waves-effect waves-light " onclick="return update_columns_medpro();">
        <span class="fa fa-search"></span> Procesar</button>
        </div>
        <br>
    <?php } ?>
    <div class="input-group col-sm-12 col-md-10 col-lg-5">
        <span  style="border-color:#61B292;" class="input-group-addon bg-primary text-white">
            <span class="ti ti-calendar"></span>
        </span>
        <input type="text" class="form-control text-center" id="fecha-consulta" style="border-color:#61B292;width:60% !important;" value="<?php print date("d/m/Y");?>" readonly="true">
        <select class="form-control" id="select_event_change" onchange="return change_event();" style="border-color:#61B292;width:50% !important;">
            <option value="listar_pedidos_x_dia()">Listado</option>       
        </select>
        <div class="input-group-append">
            <button id="event_button_" class="btn btn-outline-secondary btn-default text-white waves-effect waves-light" type="button" style="height:38px;"> <span class="fa fa-search"></span></button>
        </div>
    </div>
    <hr>
    <div id="div-listado_mm" class="table-responsive text-center"></div>
    </div>
</div>