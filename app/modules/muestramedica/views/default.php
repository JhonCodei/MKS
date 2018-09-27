<div class="row">
<div class="card-box col-lg-12">

    <p class="h1 header-title">
        <b class="h2">Muestras médicas</b>
        <p class="text-muted m-b-30 font-14">
        <!-- <button type="button" onclick="onsite_modal();" class="btn btn-sm btn-inverse waves-effect waves-light" style="border-style:2px !important;">
            <span class="fa fa-circle"></span>&nbsp;Marcar sitio
        </button> -->
        <button type="button" onclick="muestra_medica_form_direct(0);" class="btn btn-sm btn-default waves-effect waves-light" style="border-style:2px !important;">
            <span class="fa fa-plus"></span>&nbsp;Descargar muestras
        </button>
        <button type="button" onclick="productos_stock_representantes();" class="btn btn-sm btn-primary waves-effect waves-light" style="border-style:2px !important;">
            <span class="fa fa-medkit"></span>&nbsp;Ver mi stock
        </button>
        <!-- <button disabled type="button"  class="btn btn-sm btn-primary waves-effect waves-light" style="border-style:2px !important;">
            <span class="fa fa-chart"></span>&nbsp;Cobertura
        </button> -->
        </p>
        <!-- MENSAJE -->
        <!--<div class="alert alert-danger col-md-7">
            <b class="h5"><strong>AVISO</strong></b>
            <p class="">Informales que el día <b>SABADO 23/06/2018 NO SE PODRA ACCEDER AL SISTEMA POR MANTENIMIENTO.</b> Favor ponerse al día en sus 
            muestras médicas.<br>-->
            <!-- <strong>Plazo de ingreso hasta el día 16 de Mayo. 23:59</strong> -->
            <!--</p>
        </div>     -->
        <!-- MENSAJE -->
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
        <input type="text" class="form-control text-center" id="fecha__mm" style="border-color:#61B292;width:60% !important;" value="<?php print date("d/m/Y");?>" readonly="true">
        <select class="form-control" id="select_event_change" onchange="return change_event();" style="border-color:#61B292;width:50% !important;">
            <option value="listado_mm()">Listado</option>
            <option value="cobertura_representante()">Cobertura</option>
            <option value="table_medicos_x_visitar()">Médicos pendiente visita</option>
            <option value="table_medicos_x_dia()">Calendario Médico</option>           
        </select>
        <div class="input-group-append">
            <button id="event_button_" class="btn btn-outline-secondary btn-default text-white waves-effect waves-light" type="button" style="height:38px;"> <span class="fa fa-search"></span></button>
        </div>
    </div>
    <hr>
    <div id="div-listado_mm" class="table-responsive text-center"></div>
    </div>
</div>