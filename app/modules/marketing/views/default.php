<div class="row">
   <div class="card-box col-lg-12">
      <p class="h1 header-title">
         <b class="h2">Marketing</b> &nbsp;
         <p class="text-muted m-b-30 font-14">Reporte Marketing</p>
      <!-- <p class="text-muted m-b-30 font-14" id="p_update"></p> -->
      </p>
      <div class="col-lg-11 text-center">
            <button type="button" class="btn btn-default waves-effect waves-light btn-lg" onclick="return filter_resumen_ventas();"><span class="fa fa-hospital-o"></span>&nbsp;Resumen RV</button>
            <!-- <button type="button" class="btn btn-danger waves-effect waves-light btn-lg" onclick="return ficha_medica();"><span class="fa fa-hospital-o"></span>&nbsp;Resumen RM</button> -->
            <button type="button" class="btn btn-inverse waves-effect waves-light btn-lg" style="font-family:verdana;" onclick="return filter_cuota_x_producto();"><span class="fa fa-list-alt"></span>&nbsp;Cuota x Producto</button>
            <button type="button" class="btn btn-primary waves-effect waves-light btn-lg" onclick="return filter_prod_zona();"><span class="fa fa-address-card-o"></span>&nbsp;Producto Zona</button>
            <button type="button" class="btn btn-danger waves-effect waves-light btn-lg" onclick="return filter_producto_cliente_zona();"><span class="fa fa-address-card-o"></span>&nbsp;Producto cliente zona</button>
            <button type="button" class="btn btn-purple  waves-effect waves-light btn-lg" onclick="return filter_vendedor_cliente();"><span class="fa fa-address-card-o"></span>&nbsp;Vendedor cliente</button>
            <button type="button" class="btn btn-default  waves-effect waves-light btn-lg" onclick="return filter_productos_region();"><span class="fa fa-list-alt"></span>&nbsp;Precio Producto</button>
            
        </div>
        <hr>
      <div class="row" id="filter_view"></div>
        
      <div class="col-lg-12">
        <br><br>
       
        <div class="col-lg-12 table-responsive text-center">
            <div id="div-content-view-data" class="font-table"></div>
            <div id="div-content-view-data2" class="font-table"></div>
        </div>
        
      </div>
   </div>
</div