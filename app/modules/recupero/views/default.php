<div class="row">
    <div class="card-box col-lg-12">
        <p class="h1 header-title">
            <b class="h2">Recupero</b>
        </p>
<hr>
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
            <button class="btn btn-primary waves-effect waves-light" onclick="return procesar_escalas();">
            <span class="fa fa-search"></span>
            &nbsp;Consultar</button>
    </div>
    <div id="div-content-data-0" class="table-responsive text-center"></div>
    <hr>
    <div id="div-content-data-1" class="table-responsive text-center"></div>

    </div>
</div>