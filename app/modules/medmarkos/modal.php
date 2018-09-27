<!-- MODAL PRONOSTICO -->
<div class="modal fade" id="modal-cobertura-no-visitados" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="h3 modal-title mt-0">Medicos pronostico</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                  <div class="col-lg-6">
                    <strong style="font-size:0.95em;">
                        <label class="h5"> Especialidad: <b id="espec"></b></label><br>
                        <label class="h5">Categoria:  <b id="categ"></b> </label>
                     </strong>
                  </div>

                  <div class="col-lg-12">
                     <div class="table-responsive">
                        <table style="font-weight:bold;" class="table text-center table-condensed table-bordered dt-responsive nowrap table-sm" cellspacing="0" width="100%">
                           <thead style="background-color: #36404A;">
                              <th  style="color: white;font-size:0.85em;" class="text-center">Medico</th>
                              <!-- <th  style="color: white;font-size:0.7em;" class="text-center">Especialidad</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Categoria</th> -->
                              <th  style="color: white;font-size:0.85em;" class="text-center">Distrito</th>
                              <th  style="color: white;font-size:0.85em;" class="text-center">Direcci&oacute;n</th>
                              <th  style="color: white;font-size:0.85em;" class="text-center">Visita</th>
                           </thead>
                           <tbody id="modal-table-medicos-no-visitados"></tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL PRONOSTICO -->
<!-- MODAL REALIZADO coberturarealizada-->
<div class="modal fade" id="modal-cobertura-visitados" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="h3 modal-title mt-0">Medicos visitados</h3>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                    <div class="col-lg-6">
                        <strong style="font-size:0.95em;">
                            <label class="h5"> Especialidad: <b id="espec2"></b></label><br>
                            <label class="h5">Categoria:  <b id="categ2"></b> </label>
                        </strong>
                    </div>
                  <div class="col-xs-12 text-center">
                     <div class="table-responsive">
                        <table style="font-weight:bold;" class="table text-center table-condensed table-bordered dt-responsive nowrap table-sm" cellspacing="0" width="100%">
                           <thead style="background-color: #36404A;">
                              <th  style="color: white;font-size:0.7em;" class="text-center">Medicos</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Visitas</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Fechas visitadas</th>
                           </thead>
                           <tbody id="modal-table-medicos-visitados"></tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL REALIZADO -->

<!-- MODAL REALIZADO coberturarealizada-->
<div class="modal fade" id="modal-medicos-x-dia"  data-keyboard="false" data-backdrop="static" tabindex="-1000" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="h3 modal-title mt-0">Medicos visitados</h3>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                    <!-- <div class="col-lg-6">
                        <strong style="font-size:0.95em;">
                            <label class="h5"> Especialidad: <b id="espec2"></b></label><br>
                            <label class="h5">Categoria:  <b id="categ2"></b> </label>
                        </strong>
                    </div> -->
                  <div class="col-xs-12 text-center">
                     <div class="table-responsive" id="table-modal-medicos-dia">
                        <!-- <table style="font-weight:bold;" class="table text-center table-condensed table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                           <thead style="background-color: #36404A;">
                              <th  style="color: white;font-size:0.7em;" class="text-center">Medicos</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Visitas</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Fechas visitadas</th>
                           </thead>
                           <tbody id="modal-table-medicos-visitados"></tbody>
                        </table> -->
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL REALIZADO -->


<!-- MODAL REALIZADO_FECHA_PRODUCTOS -->
<div class="modal fade" id="modal-productos-fechas" style="overflow-y: scroll;" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="h3 modal-title mt-0">Productos entregados</h3>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                    <div class="col-lg-12">
                        <strong style="font-size:0.95em;">
                            <label class="h5"> Medico: <b id="medico_desc"></b></label><br>
                        </strong>
                    </div>
                  <div class="col-lg-12">
                     <strong style="font-size:0.8em;"><b id="all_detalle_med"> </b></strong>
                     <div class="table-responsive">
                        <table style="font-weight:bold;" class="table text-center table-condensed table-bordered dt-responsive nowrap table-sm" cellspacing="0" width="100%">
                           <thead style="background-color: #36404A;">
                              <th  style="color: white;font-size:0.7em;" class="text-center">Productos</th>
                              <th  style="color: white;font-size:0.7em;" class="text-center">Cantidad</th>
                           </thead>
                           <tbody id="modal-table-producto-detalle-fecha"></tbody>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL REALIZADO_FECHA_PRODUCTOS -->

<!-- MODAL medicos x producto -->
<div class="modal fade" id="modal-medicos-x-producto" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="h3 modal-title mt-0">Medicos productos</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    </div>
         <div class="modal-body">
            <div class="form-horizontal" role="form">
               <div class="form-group ">
                    <label class="h5" for="">Producto: <span id="prod_name_modal"></span></label>
                   <div class="col-lg-12">
                     <div class="table-responsive" id="tbl_modal_data_medicos_x_productos"></div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- MODAL medicos x producto -->