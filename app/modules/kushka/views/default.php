<div class="row">
   <div class="card-box col-lg-12">
      <p class="h1 header-title">
         <b class="h2">KUSHKA</b> &nbsp;
         <!-- <p class="text-muted m-b-30 font-14">Graficos</p> -->
      <p class="text-muted m-b-30 font-14" id="p_update"></p>
      </p>
      <div class="form-inline col-lg-12">
        <?php if(session_usuario_cargo() == 0){?>
        <div class="col-lg-12">
            <form id="formdata" class="form-inline btn-group">
                <input type="file" id="excel" name="excel" class="filestyle col-lg-6" data-buttontext="Explorar">
                <input type="text" id="periodoinp" placeholder="periodo" class="text-center form-control">
                <select class="form-control" id="empresa">
                    <option value="3">MKS</option>
                    <option value="6" selected>MARKOS</option>
                </select>
                <select class="form-control" id="tipocarga">
                    <option value="locales">Cargar locales</option>
                    <option value="drenaje" selected>Cargar drenaje</option>
                </select>
                <button type="submit" class="btn btn-primary waves-effect waves-light"><span class="fa fa-load"></span> Cargar</button>
                <button type="button" onclick="generar_data();" class="btn btn-inverse waves-effect waves-light">Generar</button>
                <!-- <button type="button" onclick="sierra_central_proceso();" class="btn btn-danger waves-effect waves-light">Sierra central</button> -->
            </form>
         </div>
        <?php }?>
         
         
        <div class="col-lg-3"></div>
        <br><br><br>
         <div class="input-group col-lg-4">
            <span class="input-group-addon bg-primary text-white b-0"> Periodo </span>
            <input type="number" id="periodo_data" placeholder="periodo" class="text-center form-control">
            <button class="btn btn-md btn-md waves-effect waves-light btn-default" onclick="return listar_reporte();"><span class="fa fa-search"></span>&nbsp; Buscar</button>
         </div>
      </div>
      <!-- <div class="col-lg-12"> -->

         <br>
         <div class="col-lg-12 table-responsive">
            <div id="div-content-search-1" class="font-table"></div>
         </div>
         <br><hr>
         <div class="table-responsive">
            <div id="detail_regional" class="font-table"></div>
         </div>
        <br>
        <div id="event"></div>
   </div>
</div>