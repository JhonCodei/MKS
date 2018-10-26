<div class="row">
 <div class="card-box col-lg-12">
    <p class="h1 header-title">
        <b class="h2">Gastos</b>
    </p>
    <br>
    <div class="row">
        <div class="col-md-2">
            <div class="input-group"> 
                <span class="input-group-addon bg-inverse text-white font-weight-bold border border-dark input-sm">Periodo</span>
                <input type="text" class="form-control input-sm text-center border border-secondary" id="periodo" placeholder="Fecha" onkeypress="return max_length(this.value, 5);" value="<?php print date("Ym");?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group"> 
                <span class="input-group-addon bg-inverse text-white font-weight-bold border border-dark input-sm"><div style="font-size:0.9em;">Quincena</div></span>
                <select class="form-control input-sm border border-secondary " id="quincena" style="height:60% !important;text-align-last:center;">
                    <?php validate_periodo_gastos();?>
                </select>
                <button class="btn btn-primary btn-sm waves-effect waves-light" onclick="listado_gastos();"><span class="fa fa-search"></span>&nbsp; Buscar</button>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-sm waves-effect waves-light" onclick="showModalAdd();"><span class="fa fa-plus"></span>&nbsp;Agregar</button>
        </div>
    </div>
    <hr>
    <div class="table-responsive" id="content-div">
    
    <!-- <table id="listado-gastos" class="table table-bordered">
        <thead>
            <th>Fecha</th>
            <th>Motivo</th>
            <th>Tipo Doc.</th>
            <th>Doc</th>
            <th>Ruc</th>
            <th>Importe</th>
            <th>Ventas</th>
            <th>Ruc Cliente</th>
            <th>Observaciones</th>
        </thead>
    </table> -->
    
    </div>





 </div>
</div>