<div id="users_modal" class="modal fade show" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0">Mantenimiento usuario</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="control-label">Nombres y apellidos</label>
                            <input type="text" class="form-control text-center" id="nombres" placeholder="Nombres y apellidos">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="control-label">Tipo usuario</label>
                            <select class="form-control" id="root">
                                <option value="0">Admin</option>
                                <option value="1">Gerencia</option>
                                <option value="2">S. General</option>
                                <option value="3">Supervisor</option>
                                <option value="4">Vendedores</option>
                                <option value="5" selected>Propagandistas</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">Usuario</label>
                            <input type="text" class="form-control text-center" id="usuario" placeholder="Usuario">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="field-3" class="control-label">Contraseña</label>
                            <input type="password" class="form-control text-center" id="password" placeholder="Contrase&ntilde;a">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Codigo</label>
                            <input type="text" class="form-control text-center" id="codigo" placeholder="Codigo">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="field-5" class="control-label">Portafolio</label>
                            <input type="text" class="form-control text-center" id="portafolio" placeholder="Portafolio">
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label for="field-6" class="control-label">Region</label>
                            <select class="form-control" id="region">
                                <option value="1">Castillo Arequipa</option>
                                <option value="2">Dimexa Arequipa</option>
                                <option value="3">Norte 1</option>
                                <option value="4">Norte 2</option>
                                <option value="5">Sierra central</option>
                                <option value="6">Norte sur chico</option>
                                <option value="7">Mayorista</option>
                                <option value="8">Lima</option>
                                <option value="T">Todos</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="field-6" class="control-label">Region</label>
                            <select class="form-control" id="region">
                                <option value="22-24">Castillo Arequipa</option>
                                <option value="2-23">Dimexa Arequipa</option>
                                <option value="10-26">Norte 1</option>
                                <option value="9-25">Norte 2</option>
                                <option value="11-27">Sierra central</option>
                                <option value="3-28">Norte sur chico</option>
                                <option value="99-99">Mayorista</option>
                                <option value="1-8">Lima</option>
                                <option value="T-T">Todos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="field-6" class="control-label">Ver</label>
                            <select class="form-control" id="tipo_view">
                                <option value="0">Todos</option>
                                <option value="1">Ventas</option>
                                <option value="2">Visitas</option>
                                <option value="3">Ventas-Visitas</option>
                                <option value="4">Reportes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group no-margin">
                            <label for="field-7" class="control-label">Menus</label>
                            <select class="form-control" id="menu_array"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_events" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Registrar</button>
                <button type="button" class="btn btn-danger waves-effect waves-light" onclick="return mantenimiento_modal(0);" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>