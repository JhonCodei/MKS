<div class="row">
   <div class="card-box col-lg-12">
      <p class="h1 header-title">
         <b class="h2">Ventas regional</b> &nbsp;
         <!-- <p class="text-muted m-b-30 font-14">Graficos</p> -->
      <p class="text-muted m-b-30 font-14" id="p_update"></p>
      </p>
      <div class="form-inline col-lg-12">
        <?php if(session_usuario_cargo() == 0){?>
        <div class="col-lg-12">
            <form id="formdata" class="form-inline btn-group">
                <input type="file" id="excel" name="excel" class="filestyle col-lg-6" data-buttontext="Explorar">
                <input type="text" id="periodoinp" placeholder="periodo" class="text-center form-control">
                <!-- <input type="text" id="region_id" placeholder="region_id" class="text-center form-control"> -->
                <button type="submit" class="btn btn-primary waves-effect waves-light"><span class="fa fa-load"></span> Cargar</button>
                <button type="button" onclick="generar_reporte_venta();" class="btn btn-inverse waves-effect waves-light">Generar</button>
                <button type="button" onclick="sierra_central_proceso();" class="btn btn-danger waves-effect waves-light">Sierra central</button>
                <!-- <button onclick="list_closeup_data();" class="btn btn-primary waves effect waves-light">Close_UP</button> -->
            </form>
        </div>
        <div class="col-lg-12 form-inline porlet">
            <label for="">GENERAR VISITAS &nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input type="text" id="periodoinp_2" placeholder="periodo" class="text-center form-control">
            <select class="form-control" id="region_select_id" style="text-align-last:center;">
                <option value="1">Lima</option>
                <option value="2">Dimexa Arequipa</option>
                <option value="3">Norte sur chico</option>
                <option value="10">Norte1</option>
                <option value="9">Norte2</option>
                <option value="11">Sierra Central</option>
                <option value="22">Castillo</option>
                </select>
            <button onclick="generar_reporte_visitas();" class="btn btn-primary waves effect waves-light"> Visita</button>
         </div>
        <?php }?>        
      </div>
        <div class="col-lg-12"> 
         <br/>
         <div class="text-center h4" >
            <span id="_sum_all" style="color:black;"></span>
        </div>
         <div id="graph_regional" class="chartp"></div>
         <div id="graph_portafolio" class="chartp"></div>
         <br>
         <div class="col-lg-12 table-responsive">
            <div id="piramidal_regional" class="font-table"></div>
         </div>
         <br><hr>
         <div class="table-responsive">
            <div id="detail_regional" class="font-table"></div>
         </div>
         <br><hr>
        <div class="col-lg-12 table-responsive">
            <div id="detail_clientes" class="font-table"></div>
        </div>
        <div id="event"></div>
   </div>
</div>