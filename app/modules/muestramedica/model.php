<?php

Class MuestramedicaModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
      
/*******************************************************MUESTRA_MEDICA*********************************************************/
public function update_columns_medpro($repre, $year, $mes)
{
    $select_records = $this->db->prepare("SELECT 
                                                medpro_id, medpro_medico_cmp, medpro_vendedor, medpro_fecha
                                            FROM
                                                tbl_medpro_detalle
                                                WHERE
                                                YEAR(medpro_fecha) = :year
                                                    AND MONTH(medpro_fecha) = :mes
                                                    AND medpro_vendedor = :repre;");
    $select_records->bindParam(':repre', $repre);
    $select_records->bindParam(':year', $year);
    $select_records->bindParam(':mes', $mes);
    if($select_records->execute())
    {
        if($select_records->rowCount() > 0)
        {
            while($select_records_r = $select_records->fetch(PDO::FETCH_ASSOC))
            {                   
                $id = $select_records_r['medpro_id'];
                $medico_cmp = $select_records_r['medpro_medico_cmp'];
                $vendedor = $select_records_r['medpro_vendedor'];
                $fecha = $select_records_r['medpro_fecha'];

                $data_array = _data_array_medico($medico_cmp, $vendedor);

                $nombre = $data_array['nombre'];
                $correlativo = $data_array['correlativo'];
                $especialidad = $data_array['especialidad'];
                $direccion = $data_array['direccion'];
                $institucion = $data_array['institucion'];
                $localidad = $data_array['localidad'];
                $categoria = $data_array['categoria'];
                $alta_baja = 'A';
                $ubigeo = $data_array['ubigeo'];

                $update = $this->db->prepare("UPDATE tbl_medpro_detalle 
                                                SET 
                                                    medpro_medico_corr = :correlativo,
                                                    medpro_medico_nombre = :nombre,
                                                    medpro_medico_esp = :especialidad,
                                                    medpro_medico_categoria = :categoria,
                                                    medpro_medico_direccion = :direccion,
                                                    medpro_medico_institucion = :institucion,
                                                    medpro_medico_localidad = :localidad,
                                                    medpro_medico_altabaja = :alta_baja,
                                                    medpro_medico_ubigeo = :ubigeo
                                                WHERE
                                                    medpro_id = :id ");

                $update->bindparam(":nombre", $nombre);
                $update->bindparam(":correlativo", $correlativo);
                $update->bindparam(":especialidad", $especialidad);
                $update->bindparam(":categoria", $categoria);
                $update->bindparam(":direccion", $direccion);
                $update->bindparam(":institucion", $institucion);
                $update->bindparam(":localidad", $localidad);
                $update->bindparam(":alta_baja", $alta_baja);
                $update->bindparam(":ubigeo", $ubigeo);
                $update->bindparam(":id", $id);

                if($update->execute())
                {
                    print "OK<br>";
                }else
                {
                    print errorPDO($update).'<br>';
                }
            }
        }else
        {
            print errorPDO($select_records).'<br>';
        }
    }       
}
public function muestra_medica_form($id)
{
    $output = '<div class="form-inline">
        <div class="input-daterange input-group col-lg-12">
            <span class="input-group-addon bg-primary text-white b-0" style="font-size:0.85em;">Representante</span>
            <span class="input-group-addon text-dark b-0" style="font-size:0.85em;">'.zerofill(info_usuario('codigo'), 3).'</span>
            <span class="input-group-addon text-dark b-0" style="font-size:0.85em;">'.info_usuario('nombre').'</span>
        </div>
    </div>
    <div class="form-inline">
        <div class="input-daterange input-group col-lg-12">
            <span class="input-group-addon bg-primary text-white b-0" style="font-size:0.85em;">Fecha</span>
            <span class="input-group-addon text-dark b-0" style="font-size:0.85em;">&nbsp;&nbsp;&nbsp;&nbsp;'.date("d/m/y").'&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <span class="input-group-addon bg-primary text-white b-0" style="font-size:0.85em;"> &nbsp;&nbsp; Estado : &nbsp;</span>
            <select class="form-control" id="status_mm" style="font-size:0.85em;">
                <option value="1">Emitido</option>
                <option value="0">Visita no presenta</option>
            </select>
        </div>
            
    </div>
    <div class="form-inline">
        <div class="input-daterange input-group col-lg-12">
            <span class="input-group-addon bg-primary text-white b-0" style="font-size:0.85em;">Medico</span>
            <span class="input-group-addon  text-dark b-0" style="font-size:0.85em;">COD_MED</span>
            <span class="input-group-addon text-dark b-0" style="font-size:0.85em;">NAME_MED</span>
        </div>
    </div>
    <div class="form-inline">
        <div class="input-daterange input-group col-lg-12">
            <span class="input-group-addon bg-primary text-white b-0" style="font-size:0.85em;">Direccion</span>
            <span class="input-group-addon  text-dark b-0" style="font-size:0.85em;">DIREC_MED</span>
        </div>
    </div>';

    return $output;
}
public function _buscar_producto($periodo, $user_session, $prod_cod, $target)
{
    #codgio ingresado debe ser tanto el personalizado o normal
    $output = null;
    $WHERE = null;

    if($prod_cod != 'S')
    {
        $WHERE = " AND (stock_style_cod = '$prod_cod' OR stock_codigo_producto = '$prod_cod') ";
    }

    $stock_productos = $this->db->prepare("SELECT 
                                            stock_style_cod as style_cod,
                                            stock_codigo_producto as prod_cod,
                                            stock_descripcion_producto as prod_desc,
                                            stock_cantidad as stock_inicial,
                                            stock_cantidad_tmp as stock_actual
                                        FROM
                                            tbl_stock
                                        WHERE
                                            stock_codigo_vendedor = :user_session
                                                AND stock_periodo = :periodo
                                            $WHERE
                                            ORDER BY stock_style_cod ASC;");
    $stock_productos->bindparam(":user_session", $user_session);
    $stock_productos->bindparam(":periodo", $periodo);
    if($stock_productos->execute())
    {
        if($stock_productos->rowCount() > 0)
        {
            if($WHERE != NULL)
            {
                $stock_productos_r = $stock_productos->fetch(PDO::FETCH_ASSOC);

                $style_cod  = $stock_productos_r['style_cod'];
                $prod_cod  = $stock_productos_r['prod_cod'];
                $prod_desc  = $stock_productos_r['prod_desc'];
                $stock_inicial  = $stock_productos_r['stock_inicial'];
                $stock_actual  = $stock_productos_r['stock_actual'];

                $output = $prod_cod.'~~'.$prod_desc.'~~'.$stock_actual;
                $type = '1';
            }else
            {   $output .= '<div class="text-center" >
                                <label class="h3"> Productos </label>
                                <button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" data-dismiss="modal" aria-hidden="true" onclick="return close_modal('."'modal-events-mm'".');">
                                    <span class="fa fa-close"></span></button>
                            </div>
                            <input type="text" style="border-color:blue;" class="form-control text-center" id="search_input_events" placeholder="Filtrar" onkeyup="return keyup_filter_events()"/>
                                <div class="fixed-table-container" style="padding-bottom: 0px;">
                                <div class="fixed-table-body">
                
                                <table id="table-list-events" data-toggle="table" data-page-size="10" data-pagination="true" class="table-bordered table-condensed table table-hover table-sm" style="display:table;font-size:0.9em;">
                                <thead class="text-white" style="background-color:#588D9C;">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Cod</th>
                                    <th class="text-center">Articulo</th>
                                    <th class="text-center">Desc Articulo</th>
                                    <th class="text-center">Stock</th>
                                </thead>
                                <tbody style="color:black;font-family:verdana;font-size:0.85em;">';
                while($stock_productos_r = $stock_productos->fetch(PDO::FETCH_ASSOC))
                {
                    $style_cod  = $stock_productos_r['style_cod'];
                    $prod_cod  = $stock_productos_r['prod_cod'];
                    $prod_desc  = $stock_productos_r['prod_desc'];
                    $stock_inicial  = $stock_productos_r['stock_inicial'];
                    $stock_actual  = $stock_productos_r['stock_actual'];

                    $output .= ' <tr>
                                    <td class="text-center">
                                        <button class="btn btn-inverse btn-sm waves-effect waves-light" 
                                        onclick="send_field_x4('."'prod_cod_$target'".','."'".$prod_cod."'".', '."'prod_desc_$target'".','."'".$prod_desc."'".','."'stock_$target'".','."'".$stock_actual."'".')"><span class="fa fa-plus"></span></button>
                                    </td>
                                    <td class="text-center">'.$style_cod.'</td>
                                    <td class="text-center">'.$prod_cod.'</td>
                                    <td>'.$prod_desc.'</td>
                                    <td class="text-center">'.$stock_actual.'</td>
                                </tr>';
                }
                $output .= '</tbody></table></div></div>';
                $type = '0';
            }
        }else
        {
            $type = '1';
            $output = '0~~No registrado';
        }   
    }
    return $type.'||'.$output;

}
public function _buscar_supervisor($cod_sup)
{
    $output = null;
    $WHERE = null;

    if($cod_sup != 'S')
    {
        $WHERE = " AND representante_codigo = '$cod_sup' ";
    }

    $supervisor = $this->db->prepare("SELECT 
                                        representante_codigo AS codigo,
                                        representante_nombre AS nombre
                                    FROM
                                        tbl_representantes
                                    WHERE
                                        representante_cargo NOT IN(6, 8, 3)
                                            AND representante_estado = 'A' 
                                            $WHERE;");
    if($supervisor->execute())
    {
        if($supervisor->rowCount() > 0)
        {
            if($WHERE != NULL)
            {
                $supervisor_r = $supervisor->fetch(PDO::FETCH_ASSOC);

                $codigo = $supervisor_r['codigo'];
                $nombre = $supervisor_r['nombre'];

                $output = $codigo.'~~'.$nombre;
                $type = '1';
            }else
            {   $output .= '<div class="text-center" >
                                <label class="h3"> Supervisor </label>
                                <button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" data-dismiss="modal" aria-hidden="true" onclick="return close_modal('."'modal-events-mm'".');">
                                    <span class="fa fa-close"></span></button>
                            </div>
                            <input type="text" class="form-control text-center" id="search_input_events" placeholder="Filtrar" onkeyup="return keyup_filter_events()"/>
                                <div class="fixed-table-container" style="padding-bottom: 0px;">
                                <div class="fixed-table-body">
                
                                <table id="table-list-events" data-toggle="table" data-page-size="10" data-pagination="true" class="table-bordered table-condensed table table-hover" style="display: table;font-size:0.9em;">
                                <thead class="text-white" style="background-color:#588D9C;">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Codigo</th>
                                    <th class="text-center">Nombre</th>
                                </thead>
                                <tbody style="color:black;font-family:verdana;font-size:0.85em;">';
                while($supervisor_r = $supervisor->fetch(PDO::FETCH_ASSOC))
                {
                    $codigo = $supervisor_r['codigo'];
                    $nombre = $supervisor_r['nombre'];

                    $output .= '<tr>
                                    <td>
                                        <button class="btn btn-inverse btn-sm waves-effect waves-light" onclick="send_field_('."'cod_sup'".','."'".$codigo."'".', '."'name_sup'".', '."'".$nombre."'".')"><span class="fa fa-plus"></span></button>
                                    </td>
                                    <td>'.$codigo.'</td>
                                    <td>'.$nombre.'</td>
                                </tr>';
                }
                $output .= '</tbody></table></div></div>';
                $type = '0';
            }
        }else
        {
            $type = '1';
            $output = $cod_sup.'~~No registrado';
        }   
    }
    return $type.'||'.$output;
}
public function _buscar_medicos($cmp, $correlativo, $user_session)
{
    #codgio ingresado debe ser tanto el personalizado o normal
    //$user_session = info_usuario('usuario');
    $output = null;
    $WHERE = null;
    $WHERE1 = null;

    $goSQL = null;

    $user_existe_medico = user_existe_medico($user_session);

    if($cmp != 'S')
    {
        $WHERE = " AND medico_cmp = '$cmp' AND medico_correlativo = '$correlativo' ";
        $WHERE1 = " AND medico_cmp = '$cmp' ";
    }

    $SQL_MED1 = "SELECT 
                    medico_cmp AS cmp,
                    medico_correlativo AS correlativo,
                    medico_nombre AS nombre,
                    medico_categoria AS categoria
                FROM
                    tbl_maestro_medicos
                WHERE
                    medico_especialidad != ''
                        AND medico_categoria != ''
                        AND medico_alta_baja != 'B'
                        AND medico_zona = (SELECT 
                            representante_zonag
                        FROM
                            tbl_representantes
                        WHERE
                            representante_codigo = :user_session)
                        AND medico_representante = :user_session
                        $WHERE1";
    $SQL_MED2 = "SELECT 
                    medico_cmp AS cmp,
                    medico_correlativo AS correlativo,
                    medico_nombre AS nombre,
                    medico_categoria AS categoria
                FROM
                    tbl_maestro_medicos
                WHERE
                    medico_especialidad != ''
                        AND medico_categoria != ''
                        AND medico_alta_baja != 'B'
                        AND medico_zona = (SELECT 
                            representante_zonag
                        FROM
                            tbl_representantes
                        WHERE
                            representante_codigo = :user_session)
                    $WHERE 
                      AND medico_representante IS NULL;";

    if($user_existe_medico == 1)
    {
        $goSQL = $SQL_MED1;
    }else
    {
        $goSQL = $SQL_MED2;
    }
    $medicos = $this->db->prepare($goSQL);
    $medicos->bindparam(':user_session', $user_session);
    if($medicos->execute())
    {
        if($medicos->rowCount() > 0)
        {
            if($WHERE != NULL)
            {
                $medicos_r = $medicos->fetch(PDO::FETCH_ASSOC);

                $cmp = $medicos_r['cmp'];
                $nombre = $medicos_r['nombre'];
                $correlativo = $medicos_r['correlativo'];

                $cmp1 = $cmp.'*'.$correlativo;

                $output = $cmp1.'~~'.$nombre;

                $type = '1';

            }else
            {   $output .= '<div class="text-left" style="">
                                <label class="h3"> Listado medicos </label>
                                <button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" data-dismiss="modal" aria-hidden="true" onclick="return close_modal('."'modal-events-mm'".');">
                                    <span class="fa fa-close"></span></button>
                            </div>
                            <input type="text" class="form-control text-center" style="border-color:black;" id="search_input_events" placeholder="Filtrar" onkeyup="return keyup_filter_events()"/>
                                <div class="fixed-table-container" style="padding-bottom: 0px;">
                                <div class="fixed-table-body">
                
                                <table id="table-list-events" data-toggle="table" data-page-size="10" data-pagination="true" class="table-condensed table table-hover table-bordered table-sm" style="display: table;font-size:0.9em;">
                                <thead class="text-white " style="background-color:#588D9C;">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Codigo</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Categ.</th>
                                </thead>
                                <tbody style="color:black;font-family:verdana;font-size:0.85em;">';
                while($medicos_r = $medicos->fetch(PDO::FETCH_ASSOC))
                {
                    $cmp = $medicos_r['cmp'];
                    $nombre = $medicos_r['nombre'];
                    $correlative = $medicos_r['correlativo'];
                    $categoria = $medicos_r['categoria'];
                    $cmp_corr = $cmp.'*'.$correlative;

                    $output .= ' <tr>
                                    <td>
                                        <button class="btn btn-inverse btn-sm waves-effect waves-light" onclick="send_field_('."'med_cmp'".','."'".$cmp_corr."'".', '."'med_name'".', '."'".$nombre."'".')"><span class="fa fa-plus"></span></button>
                                    </td>
                                    <td>'.$cmp_corr.'</td>
                                    <td>'.$nombre.'</td>
                                    <td class="text-center">'.$categoria.'</td>
                                </tr>';
                }

                $output .= '</tbody></table></div></div>';
                $type = '0';
            }

        }else
        {
            $output = $cmp.'*'.$correlativo.'~~No registrado';
            $type = '1';
        }   
    }
    return $type.'||'.$output;

}
public function insert_muestra_medica($user_data, $med_cod, $prod_cod, $prod_name, $prod_cant, $fecha_mm, $estado, $supervisor, $periodo, $stock)
{
    $output = null;

    $data_array = _data_array_medico($med_cod, $user_data);
    #array='nombre||correlativo||localidad||especialidad||institucion||direccion||categoria||alta_baja||ubigeo';
    if(is_array($prod_cod))
    {
        $prod_cod = explode(',', implode($prod_cod, ","));
        $prod_name = explode(',', implode($prod_name, ","));
        $prod_cant = explode(',', implode($prod_cant, ","));
        $stock = explode(',', implode($stock, ","));
    }else
    {
        $prod_cod[] = $prod_cod;
        $prod_name[] = $prod_name;
        $prod_cant[] = $prod_cant;
        $stock[] = $stock;
    }

    $nombre_r = $data_array['nombre'];
    $correlativo_r = $data_array['correlativo'];
    $especialidad_r = $data_array['especialidad'];
    $direccion_r = $data_array['direccion'];
    $institucion_r = $data_array['institucion'];
    $localidad_r = $data_array['localidad'];
    $categoria_r = $data_array['categoria'];
    $alta_baja_r = 'A';
    $ubigeo_r = $data_array['ubigeo'];

    if($estado != 1)
    {
        $prod_cod = 0;
        $prod_name = 0;
        $prod_cant = 0;
        $stock = 0;
    }

    if($supervisor == null)
    {
        $supervisor = 0;
    }
  

    $insert = $this->db->prepare('INSERT INTO tbl_medpro_detalle(medpro_vendedor, medpro_medico_cmp, 
                                                    medpro_medico_corr, medpro_medico_nombre, medpro_medico_esp,
                                                    medpro_medico_categoria, medpro_medico_direccion, medpro_medico_localidad,
                                                    medpro_medico_institucion, medpro_medico_altabaja,
                                                    medpro_medico_ubigeo, medpro_producto, 
                                                    medpro_descripcion_producto, medpro_cantidad, medpro_fecha, 
                                                    medpro_estado, medpro_supervisor) 
                                    VALUES (:user_data, :med_cod, :correlativo_r, :nombre_r, :especialidad_r, :categoria_r, :direccion_r, 
                                            :localidad_r, :institucion_r, :alta_baja_r, :ubigeo_r, :prod_cod, :prod_name, :prod_cant, 
                                            :fecha_mm, :estado, :supervisor)');
    
    for($i = 0; $i <= count($prod_cod) - 1; $i++)
    {
        if($prod_cod[$i] == null)
        {
            $prod_cod_var = 0;
        }else
        {
            $prod_cod_var = $prod_cod[$i];
        }

        if($prod_name[$i] == null)
        {
            $prod_name_var = 0;
        }else
        {
            $prod_name_var = $prod_name[$i];
        }

        if($prod_cant[$i] == null)
        {
            $prod_cant_var = 0;
        }else
        {
            $prod_cant_var = $prod_cant[$i];
        }

        $insert->bindParam(':user_data', $user_data);
        $insert->bindParam(':med_cod', $med_cod);
        $insert->bindParam(':correlativo_r', $correlativo_r);
        $insert->bindParam(':nombre_r', $nombre_r);
        $insert->bindParam(':especialidad_r', $especialidad_r);
        $insert->bindParam(':categoria_r', $categoria_r);
        $insert->bindParam(':direccion_r', $direccion_r);
        $insert->bindParam(':localidad_r', $localidad_r);
        $insert->bindParam(':institucion_r', $institucion_r);
        $insert->bindParam(':alta_baja_r', $alta_baja_r);
        $insert->bindParam(':ubigeo_r', $ubigeo_r);
        $insert->bindParam(':prod_cod', $prod_cod_var);
        $insert->bindParam(':prod_name', $prod_name_var);
        $insert->bindParam(':prod_cant', $prod_cant_var);
        $insert->bindParam(":fecha_mm", $fecha_mm);
        $insert->bindParam(":estado", $estado);
        $insert->bindParam(":supervisor", $supervisor);
        if($insert->execute())
        {
            $stock_var = $stock[$i];
    
            $new_stock_var = @$stock_var-$prod_cant_var;

            $update_stock = $this->db->prepare("UPDATE tbl_stock 
                                                SET stock_cantidad_tmp = :new_stock_var 
                                                WHERE stock_codigo_vendedor = :user_data 
                                                AND stock_codigo_producto = :prod_cod_var
                                                AND stock_periodo = :periodo");
            $update_stock->bindParam(":new_stock_var", $new_stock_var);      
            $update_stock->bindParam(":user_data", $user_data);
            $update_stock->bindParam(":prod_cod_var", $prod_cod_var);
            $update_stock->bindParam(":periodo", $periodo);
            if($update_stock->execute())
            {
                $output = 1;
            }else
            {
                $output = errorPDO($update_stock);
                return false;
            }
        }else
        {
            $output = errorPDO($insert);
            return false;
        }               
    }
    return $output;
    // print "<br>user_data = " .$user_data. "<br>med_cod = " .$med_cod. "<br>prod_cod = " .implode(',', $prod_cod). "<br>prod_name = " .implode(',', $prod_name)."<br>prod_cant = " .implode(',', $prod_cant)."<br>fecha_mm = " .$fecha_mm.'<br>';     
}
public function listado_mm($user_session, $fecha__mm)
{
    $select = $this->db->prepare(" SELECT 
                                        medpro_id,
                                        medpro_medico_cmp,
                                        medpro_medico_corr,
                                        medpro_medico_nombre,
                                        GROUP_CONCAT(medpro_producto ORDER BY medpro_registro DESC) AS medpro_producto,
                                        GROUP_CONCAT(medpro_descripcion_producto ORDER BY medpro_registro DESC) AS medpro_descripcion_producto,
                                        GROUP_CONCAT(medpro_cantidad ORDER BY medpro_registro DESC) AS medpro_cantidad,
                                        medpro_fecha,
                                        GROUP_CONCAT(medpro_estado ORDER BY medpro_registro DESC) AS medpro_estado
                                    FROM
                                        tbl_medpro_detalle
                                    WHERE medpro_vendedor = :user_session
                                        AND medpro_fecha = :fecha__mm
                                    GROUP BY medpro_medico_cmp");

    $select->bindparam(':user_session', $user_session);
    $select->bindparam(':fecha__mm', $fecha__mm);
    if($select->execute())
    {
        if($select->rowCount() > 0)
        {
            $output = '<table id="tbl_listado_mm" class="table table-bordered table-condensed table-sm" style="width:auto !important;font-size:0.9em;color:black;font-family:verdana;">
                            <thead class="text-white" style="background-color:#4D7CAE;font-size:0.9em;">
                                <th class="text-center print_this">ID</th>
                                <th class="text-center print_this">CMP</th>
                                <th class="text-center print_this">Medico</th>
                                <th class="text-center print_this">Cod Prod</th>
                                <th class="text-center print_this">Producto</th>
                                <th class="text-center print_this">Cantidad</th>
                                <th class="text-center print_this">Estado</th>
                                <th class="text-center print_this">Fecha</th>
                                <th class="text-center">Acciones</th>
                            </thead>
                        </table>';

            while($select_r = $select->fetch(PDO::FETCH_ASSOC))
            {
                $medpro_id = $select_r['medpro_id'];
                $medpro_medico_cmp = $select_r['medpro_medico_cmp'];
                $medpro_medico_corr = $select_r['medpro_medico_corr'];
                $medpro_medico_name = $select_r['medpro_medico_nombre'];#search_name_med($medpro_medico_cmp, $user_session);                 
                $medpro_fecha = fecha_db_to_view($select_r['medpro_fecha']);
                
                if(strpos(',', $select_r['medpro_estado']) !== FALSE)
                {
                    $medpro_estado = end((explode(',', $select_r['medpro_estado'])));
                }else
                {
                    $medpro_estado = $select_r['medpro_estado'];
                }

                $fecha_ex = explode('-', $select_r['medpro_fecha']);

                if(strpos(',', $select_r['medpro_producto']) !== FALSE && $medpro_estado == 2 || $medpro_estado == 0 || $medpro_estado == 1 || $medpro_estado == 5)
                {
                    $medpro_producto_ex = explode(',', $select_r['medpro_producto']);
                    $medpro_producto_no_0 = array_diff($medpro_producto_ex, array(0));
                    $medpro_producto = str_replace(",", "<br>", implode($medpro_producto_no_0, ','));

                    $medpro_descripcion_producto_ex = explode(',', $select_r['medpro_descripcion_producto']);
                    $medpro_descripcion_producto_no_0 = array_diff($medpro_descripcion_producto_ex, array(0));
                    $medpro_descripcion_producto = str_replace(",", "<br>", implode($medpro_descripcion_producto_no_0, ','));

                    $medpro_cantidad_ex = explode(',', $select_r['medpro_cantidad']);
                    $medpro_cantidad_no_0 = array_diff($medpro_cantidad_ex, array(0));
                    $medpro_cantidad = str_replace(",", "<br>", implode($medpro_cantidad_no_0, ','));

                    // $medpro_cantidad = str_replace(",", "<br>",$select_r['medpro_cantidad']);

                }else
                {
                    $medpro_producto = $select_r['medpro_producto'];
                    $medpro_descripcion_producto = $select_r['medpro_descripcion_producto'];
                    $medpro_cantidad = $select_r['medpro_cantidad'];
                }

                #$medpro_acciones = '<button class="btn btn-sm btn-danger waves-light waves-effect" disabled>Eliminar</button>';
                // $medpro_acciones = '<button class="btn btn-sm btn-primary waves-light waves-effect" onclick="cobertura_fecha_productos('."'".$medpro_medico_cmp."'".','."'".$fecha_ex[2]."'".','."'".$fecha_ex[1]."'".','."'".$fecha_ex[0]."'".','."'".$user_session."'".','."'".$medpro_medico_name."'".')"><span class="fa fa-list-alt"></span>&nbsp;Ver</button>
                // <button class="btn btn-sm btn-danger waves-light waves-effect" onclick="eliminar_mm('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')"><span class="fa fa-close"></span>&nbsp;Anular</button>';

                // if($medpro_estado)
                // {
                //     $medpro_acciones = '<button class="btn btn-sm btn-primary waves-light waves-effect" onclick="cobertura_fecha_productos('."'".$medpro_medico_cmp."'".','."'".$fecha_ex[2]."'".','."'".$fecha_ex[1]."'".','."'".$fecha_ex[0]."'".','."'".$user_session."'".','."'".$medpro_medico_name."'".')"><span class="fa fa-list-alt"></span>&nbsp;Ver</button>
                //     <button class="btn btn-sm btn-danger waves-light waves-effect" onclick="eliminar_mm('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')"><span class="fa fa-close"></span>&nbsp;Anular</button>';
                // }else if()
                // {
                //     $medpro_acciones = '<button class="btn btn-sm btn-primary waves-light waves-effect" onclick="cobertura_fecha_productos('."'".$medpro_medico_cmp."'".','."'".$fecha_ex[2]."'".','."'".$fecha_ex[1]."'".','."'".$fecha_ex[0]."'".','."'".$user_session."'".','."'".$medpro_medico_name."'".')"><span class="fa fa-list-alt"></span>&nbsp;Ver</button>
                // <button class="btn btn-sm btn-danger waves-light waves-effect" onclick="eliminar_mm('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')"><span class="fa fa-close"></span>&nbsp;Anular</button>';
                // }else
                // {
                //     $medpro_acciones = '<button class="btn btn-sm btn-primary waves-light waves-effect" onclick="cobertura_fecha_productos('."'".$medpro_medico_cmp."'".','."'".$fecha_ex[2]."'".','."'".$fecha_ex[1]."'".','."'".$fecha_ex[0]."'".','."'".$user_session."'".','."'".$medpro_medico_name."'".')"><span class="fa fa-list-alt"></span>&nbsp;Ver</button>
                //     <button class="btn btn-sm btn-danger waves-light waves-effect" onclick="eliminar_mm('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')"><span class="fa fa-close"></span>&nbsp;Anular</button>';
                // }

                #cobertura_fecha_productos($codigo_medico, $dia, $mes, $year, $representantes);
                if($medpro_estado == 1)
                {
                    $medpro_estado = 'Emitido';
                    $medpro_acciones = '<button class="btn btn-sm btn-primary waves-light waves-effect" 
                                        onclick="cobertura_fecha_productos('."'".$medpro_medico_cmp."'".','."'".$fecha_ex[2]."'".','."'".$fecha_ex[1]."'".','."'".$fecha_ex[0]."'".','."'".$user_session."'".','."'".$medpro_medico_name."'".')">
                                        <span class="fa fa-list-alt"></span>&nbsp;Ver</button><br>
                                        <button class="btn btn-sm btn-danger waves-light waves-effect" 
                                        onclick="eliminar_mm('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')">
                                        <span class="fa fa-close"></span>&nbsp;Anular</button>';
                }else if($medpro_estado == 2)
                {
                    $medpro_estado = 'Visita sin muestra medica';
                    $medpro_acciones = '<button class="btn btn-sm btn-danger waves-light waves-effect" 
                                        onclick="eliminar_mm('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')">
                                        <span class="fa fa-close"></span>&nbsp;Anular</button>';
                }else if($medpro_estado == 0)
                {
                    $medpro_estado = 'Visita no presente';
                    $medpro_acciones = '<button class="btn btn-sm btn-danger waves-light waves-effect"
                                        onclick="eliminar_mm('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')">
                                        <span class="fa fa-close"></span>&nbsp;Anular</button>';
                }else if($medpro_estado == 4)
                {
                    $medpro_estado = 'Contacto';
                    $medpro_acciones = '<button class="btn btn-sm btn-default waves-light waves-effect" 
                                        onclick="finalizar_marcacion('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')">
                                        <span class="fa fa-close"></span>&nbsp;Finalizar</button>';
                                        // <br>
                                        // <button class="btn btn-sm btn-danger waves-light waves-effect" 
                                        // onclick="eliminar_ingreso_salida('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')">
                                        // <span class="fa fa-close"></span>&nbsp;Anular</button>';
                }
                else if($medpro_estado == 5)
                {
                    $med_complete = $medpro_medico_cmp.'*'.$medpro_medico_corr;

                    $medpro_estado = 'Fin Contacto';
                    $medpro_acciones = '<button class="btn btn-sm btn-default waves-light waves-effect" 
                                        onclick="launch_modal_mm('."'".$med_complete."'".','."'".$medpro_medico_name."'".','."'".fecha_db_to_view_2($select_r['medpro_fecha'])."'".')">
                                        <span class="fa fa-plus"></span>&nbsp;Muestra medica</button>';
                                        // <br>
                                        // <button class="btn btn-sm btn-danger waves-light waves-effect" 
                                        // onclick="eliminar_ingreso_salida('."'".$medpro_medico_cmp."'".','."'".$select_r['medpro_fecha']."'".')">
                                        // <span class="fa fa-close"></span>&nbsp;Anular</button>';
                    
                }

                // if(validate_mm_cmp_today_punto($medpro_medico_cmp, $fecha__mm, $user_session, '1,4') != 0)
                // {
                //     if($medpro_estado != 4 || $medpro_estado != 5)
                //     {
                //         $result['medpro_id'] = $medpro_id;
                //         $result['medpro_medico_cmp'] = $medpro_medico_cmp;
                //         $result['medpro_medico_name'] = $medpro_medico_name;
                //         $result['medpro_producto'] = $medpro_producto;
                //         $result['medpro_descripcion_producto'] = $medpro_descripcion_producto;
                //         $result['medpro_cantidad'] = $medpro_cantidad;
                //         $result['medpro_estado'] = $medpro_estado;
                //         $result['medpro_fecha'] = $medpro_fecha;
                //         $result['medpro_acciones'] = $medpro_acciones;
    
                //         $data['data'][] = $result;
                //     }
                // }else
                // {
                //     $result['medpro_id'] = $medpro_id;
                //     $result['medpro_medico_cmp'] = $medpro_medico_cmp;
                //     $result['medpro_medico_name'] = $medpro_medico_name;
                //     $result['medpro_producto'] = $medpro_producto;
                //     $result['medpro_descripcion_producto'] = $medpro_descripcion_producto;
                //     $result['medpro_cantidad'] = $medpro_cantidad;
                //     $result['medpro_estado'] = $medpro_estado;
                //     $result['medpro_fecha'] = $medpro_fecha;
                //     $result['medpro_acciones'] = $medpro_acciones;

                //     $data['data'][] = $result;
                // }

                $result['medpro_id'] = $medpro_id;
                $result['medpro_medico_cmp'] = $medpro_medico_cmp;
                $result['medpro_medico_name'] = $medpro_medico_name;
                $result['medpro_producto'] = $medpro_producto;
                $result['medpro_descripcion_producto'] = $medpro_descripcion_producto;
                $result['medpro_cantidad'] = $medpro_cantidad;
                $result['medpro_estado'] = $medpro_estado;
                $result['medpro_fecha'] = $medpro_fecha;
                $result['medpro_acciones'] = $medpro_acciones;

                $data['data'][] = $result;
            }
        }else
        {
            $output = 1;
            $data = 'No hay datos';
        }
    }

    if($data != 0)
    {
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
    }else
    {
        $json_data = 0;
    }

    return $output.'|~|'.$json_data;
}
public function eliminar_mm($cmp, $fecha, $user_session)
{
    $output = null;

    $select = $this->db->prepare("SELECT 
                                    medpro_producto,
                                    medpro_descripcion_producto,
                                    medpro_cantidad
                                FROM
                                    tbl_medpro_detalle
                                WHERE
                                    medpro_vendedor = :user_session
                                        AND medpro_fecha = :fecha
                                        AND medpro_medico_cmp = :cmp
                                        AND medpro_estado NOT IN(4,5)");
    $select->bindParam(':user_session', $user_session);
    $select->bindParam(':fecha', $fecha);
    $select->bindParam(':cmp', $cmp);
    if($select->execute())
    {
        if($select->rowCount() > 0)
        {
            while($select_r = $select->fetch(PDO::FETCH_ASSOC))
            {
                $medpro_producto = $select_r['medpro_producto'];
                $medpro_descripcion_producto = $select_r['medpro_descripcion_producto'];
                $medpro_cantidad = $select_r['medpro_cantidad'];

                $periodo = fecha_to_periodo($fecha);
                
                $stock_tmp = _stock_actual_x_prod($user_session, $medpro_producto , $periodo);

                $new_stock_var = @$stock_tmp+$medpro_cantidad;

            
                $delete = $this->db->prepare('DELETE FROM tbl_medpro_detalle WHERE
                                                medpro_vendedor = :user_session
                                                    AND medpro_fecha = :fecha
                                                    AND medpro_medico_cmp = :cmp
                                                    AND medpro_producto = :medpro_producto');
                
                $delete->bindparam(':user_session', $user_session);
                $delete->bindparam(':fecha', $fecha);
                $delete->bindparam(':cmp', $cmp);
                $delete->bindparam(':medpro_producto', $medpro_producto);
                $delete->execute();
                    
                $reload_stock = $this->db->prepare("UPDATE tbl_stock 
                                                    SET stock_cantidad_tmp = :new_stock_var 
                                                    WHERE stock_codigo_vendedor = :user_data 
                                                    AND stock_codigo_producto = :medpro_producto
                                                    AND stock_periodo = :periodo");
                $reload_stock->bindparam(":new_stock_var", $new_stock_var);      
                $reload_stock->bindparam(":user_data", $user_session);
                $reload_stock->bindparam(":medpro_producto", $medpro_producto);
                $reload_stock->bindparam(":periodo", $periodo);
                $reload_stock->execute();
                
                $output = 1;
            }
        }else
        {
            $output = 'No se econtro registro';
        }
    }else
    {
        $output = errorPDO($select);
    }
    return $output;
}
public function eliminar_ingreso_salida($cmp, $fecha, $user_session)
{
    $output = null;

    $select = $this->db->prepare("SELECT 
                                    medpro_producto,
                                    medpro_descripcion_producto,
                                    medpro_cantidad
                                FROM
                                    tbl_medpro_detalle
                                WHERE
                                    medpro_vendedor = :user_session
                                        AND medpro_fecha = :fecha
                                        AND medpro_medico_cmp = :cmp
                                        AND medpro_estado IN(4,5)");
    $select->bindParam(':user_session', $user_session);
    $select->bindParam(':fecha', $fecha);
    $select->bindParam(':cmp', $cmp);
    if($select->execute())
    {
        if($select->rowCount() > 0)
        {
            while($select_r = $select->fetch(PDO::FETCH_ASSOC))
            {
                $medpro_producto = $select_r['medpro_producto'];
                $medpro_descripcion_producto = $select_r['medpro_descripcion_producto'];
                $medpro_cantidad = $select_r['medpro_cantidad'];

                $periodo = fecha_to_periodo($fecha);
                
                $stock_tmp = _stock_actual_x_prod($user_session, $medpro_producto , $periodo);

                $new_stock_var = @$stock_tmp+$medpro_cantidad;

            
                $delete = $this->db->prepare('DELETE FROM tbl_medpro_detalle WHERE
                                                medpro_vendedor = :user_session
                                                    AND medpro_fecha = :fecha
                                                    AND medpro_medico_cmp = :cmp
                                                    AND medpro_estado IN(4,5)');
                
                $delete->bindparam(':user_session', $user_session);
                $delete->bindparam(':fecha', $fecha);
                $delete->bindparam(':cmp', $cmp);
                if($delete->execute())
                {
                    $output = 1;
                }else
                {
                    $output = errorPDO($delete);
                }                   
            }
        }else
        {
            $output = 'No se econtro registro';
        }
    }else
    {
        $output = errorPDO($select);
    }
    return $output;
}
public function table_medicos_x_dia($representantes, $mes, $year)
{
    $sumadias = 0;
    $cob = 0;
    $Pendiente = 0;

    $days_x_month = cal_days_in_month(CAL_GREGORIAN, $mes , $year);

    try
    {
        $output = '<div class="h4 text-center" style="color:black;"> Calendario médico</div>
        
                    <table id="table_medicos_x_dia" class="table table-striped table-bordered table-condensed table-responsive table-sm" style="width:auto !important">
                            <thead style="color: white;background-color: #36404A;font-size:1em;" class="text-center">
                            <tr>';

        for ($i=1; $i <= $days_x_month ; $i++)
        {
            // if($mes < 10)
            // {
            //     $mes = '0'.$mes;
            // }

            $create_date = $year.'-'.$mes.'-'.$i;

            if(date('w',strtotime($create_date)) == 0 )
            {
                $output .= '<th style="color: white !important;font-size:0.7em;background-color:red !important;" class="text-center">'.$i.'</th>';
            }else
            {
                $output .= '<th style="color: white !important;font-size:0.7em;" class="text-center">'.$i.'</th>';
            }               
        }
            $output .=' <th style="color: white !important;font-size:0.7em;" class="text-center">Total</th>
                        </tr>
                        </thead><tbody style=" color:black;font-weight:bold;">';

                        // $output .= '<td style="font-size:0.75em;color:black;">Medicos</td>';
        for ($f=1; $f <= $days_x_month ; $f++)
        {
            $f_core = $year.'-'.$mes.'-'.$f;

            $f_complete = date('Y-m-d', strtotime($f_core));
                            
            $visitasxdia = $this->db->prepare("SELECT 
                                                    COUNT(DISTINCT medpro_medico_cmp) AS cantidad
                                                FROM
                                                    tbl_medpro_detalle
                                                WHERE
                                                    medpro_vendedor = :representantes
                                                        AND medpro_fecha = :f_complete");
                                                        #AND movimiento_cantidad != 0
            $visitasxdia->bindParam(":representantes", $representantes, PDO::PARAM_INT);
            $visitasxdia->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);
            
            if($visitasxdia->execute())
            {
                
                while ( $visitasxdia_r = $visitasxdia->fetch(PDO::FETCH_ASSOC)  )
                {
                    
                    if($visitasxdia_r['cantidad'] == 0)
                    {
                        $output .= '<td style="font-size:0.75em;color:black;">'.$visitasxdia_r['cantidad'].'</td>';
                    }else
                    {
                        $output .= '<td  class="text-white" style="font-size:0.65em;background-color:#5EA3A6;font-weight:bold;cursor:pointer;" onclick="cobertura_visitados_detalle('."'".$representantes."'".','."'".$f_complete."'".');">'.$visitasxdia_r['cantidad'].'</td>';
                                        #cobertura_fecha_productos('."'".$med_cod."'".','."'".$f."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');
                    }

                    $sumadias += (int)$visitasxdia_r['cantidad'];
                }
            }else
            {
                $output = errorPDO($visitasxdia);
            }
        }

            $cob = '-';

            $output .= '<td style="font-size:0.75em;color:black;">'.$sumadias.'</td></tr>';


            $output .= '</tbody></table>';
            
    }catch(PDOException $e)
    {
        $output = $e->getMessage();
    }
    return $output;
}
public function cobertura_visitados_detalle($representantes, $fecha)
{
    $output = null;

    $explode_fecha = explode("-", $fecha);

    $dia = $explode_fecha[2];
    $mes = $explode_fecha[1];
    $year = $explode_fecha[0];

    try
    {
        // $consulta = $this->db->prepare("SELECT 
        //                                     medico_cmp AS med_cod,
        //                                     medico_nombre AS med_nom,
        //                                     medico_especialidad AS med_esp,
        //                                     medico_categoria AS med_cat,
        //                                     medico_direccion AS med_dir,
        //                                     medico_localidad AS med_local
        //                                 FROM
        //                                     tbl_medpro_detalle
        //                                         INNER JOIN
        //                                     tbl_maestro_medicos ON medico_cmp = medpro_medico_cmp
        //                                 WHERE
        //                                     medico_zona = (SELECT representante_zonag FROM tbl_representantes WHERE representante_codigo = :representantes)
        //                                     AND medpro_vendedor = :representantes
        //                                         AND medpro_fecha = :fecha
        //                                 GROUP BY 1
        //                                 ORDER BY 6 ASC;");
        $consulta = $this->db->prepare("SELECT 
                                            medpro_medico_cmp AS med_cod,
                                            medpro_medico_nombre AS med_nom,
                                            medpro_medico_esp AS med_esp,
                                            medpro_medico_categoria AS med_cat,
                                            medpro_medico_direccion AS med_dir,
                                            medpro_medico_localidad AS med_local
                                        FROM
                                            tbl_medpro_detalle
                                        WHERE
                                            medpro_fecha = :fecha
                                                AND medpro_vendedor = :representantes
                                        GROUP BY 1
                                        ORDER BY medpro_registro DESC;");
        $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
        $consulta->bindParam(":fecha", $fecha, PDO::PARAM_STR);
        if($consulta->execute())
        {
            if($consulta->rowCount() > 0)
            {
                $output .= '<table class="table table-striped table-condensed table-bordered table-responsive table-sm">
                            <thead style="background-color:#3E615B;font-size:0.8em; color:black;" class="text-white ">
                                <th class="text-center">Medico</th>
                                <th class="text-center">Especialidad</th>
                                <th class="text-center">Categoria</th>
                                <th class="text-center">Direccion</th>
                                <th class="text-center">Distrito</th>
                                <th class="text-center">Detalle</th>
                            </thead>
                            <tbody style="font-size:0.9em; color:black;">';
                while($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                {

                    $med_cod = $consulta_r['med_cod'];
                    $med_nom = $consulta_r['med_nom'];
                    $med_esp = $consulta_r['med_esp'];
                    $med_cat = $consulta_r['med_cat'];
                    $med_dir = $consulta_r['med_dir'];
                    $med_local = $consulta_r['med_local'];

                    $output .= '<tr>
                                    <td style="font-size:0.75em;" class="text-center">'.$med_nom.'</td>
                                    <td style="font-size:0.75em;" class="text-center">'.$med_esp.'</td>
                                    <td style="font-size:0.75em;" class="text-center">'.$med_cat.'</td>
                                    <td style="font-size:0.75em;" class="text-center">'.$med_dir.'</td>
                                    <td style="font-size:0.75em;" class="text-center">'.$med_local.'</td>
                                    <td>
                                        <button type="button" class="btn btn-primary waves-effect waves-light" onclick="cobertura_fecha_productos('."'".$med_cod."'".','."'".$dia."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$med_nom."'".');"><span class="fa fa-list-alt"></span>&nbsp;Ver</button>
                                    </td>
                                </tr>';

                }
                $output .= '</tbody></table>';
            }else
            {
                $output = errorPDO($consulta);
            }
        }else
        {
            $output = errorPDO($consulta);
        }
    }catch(PDOException $e)
    {
        $output = $e->getMessage();
    }
    return $output;
}
public function cobertura_fecha_productos($codigo_medico, $dia, $mes, $year, $representantes)
{
    $output = null;
     
    $f_core = $year.'-'.$mes.'-'.$dia;
    $f_complete = date('Y-m-d', strtotime($f_core));

    try
    {
        $consulta = $this->db->prepare("SELECT 
                                            medpro_descripcion_producto AS producto_nombre,
                                            medpro_cantidad AS cantidad
                                        FROM
                                            tbl_medpro_detalle
                                        WHERE
                                            medpro_fecha = :f_complete
                                                AND medpro_medico_cmp = :codigo_medico
                                                AND medpro_vendedor = :representantes
                                        ORDER BY 2 DESC;");
                                                #AND movimiento_cantidad != 0                                            

        $consulta->bindParam(":f_complete", $f_complete, PDO::PARAM_STR);
        $consulta->bindParam(":codigo_medico", $codigo_medico, PDO::PARAM_STR);
        $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
        if($consulta->execute())
        {
            if($consulta->rowCount() > 0)
            {
                while ($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                {
                    $output .= '<tr>
                                    <td style="font-size:0.8em;" class="text-left">'.$consulta_r['producto_nombre'].'</td>
                                    <td style="font-size:0.8em;" class="text-center">'.$consulta_r['cantidad'].'</td>
                                </tr>';
                }
            }else
            {
                $output .= '<tr>
                                <td style="font-size:0.8em;" class="text-left"> - </td>
                                <td style="font-size:0.8em;" class="text-center"> - </td>
                            </tr>';
            }
        }else
        {
            $output = errorPDO($consulta);
        }
    }catch(PDOException $e)
    {
        $output = $e->getMessage();
    }
    return $output;
}
public function productos_stock_representantes($periodo, $user_session)
{
    #codgio ingresado debe ser tanto el personalizado o normal
    $output = null;
    $WHERE = null;
    $suma_inicio = 0;
    $suma_entregados = 0;
    $suma_actual = 0;


    $stock_productos = $this->db->prepare("SELECT 
                                            stock_style_cod as style_cod,
                                            stock_codigo_producto as prod_cod,
                                            stock_descripcion_producto as prod_desc,
                                            stock_cantidad as stock_inicial,
                                            stock_cantidad_tmp as stock_actual
                                        FROM
                                            tbl_stock
                                        WHERE
                                            stock_codigo_vendedor = :user_session
                                                AND stock_periodo = :periodo
                                            ORDER BY stock_style_cod ASC;");
    $stock_productos->bindparam(":user_session", $user_session);
    $stock_productos->bindparam(":periodo", $periodo);
    if($stock_productos->execute())
    {
        if($stock_productos->rowCount() > 0)
        {
            $output .= '<table id="table-list-events" data-toggle="table" data-page-size="10" data-pagination="true" class="table-bordered table-condensed table table-sm" style="width:auto !important;display:table;font-size:0.9em;">
                                <thead class="text-white" style="background-color:#588D9C;">
                                    <th class="text-center">Cod Simple</th>
                                    <th class="text-center">Cod Articulo</th>
                                    <th class="text-center">Desc Articulo</th>
                                    <th class="text-center">Stock Inicial</th>
                                    <th class="text-center">Entregados</th>
                                    <th class="text-center">Stock Actual</th>
                                </thead>
                                <tbody style="color:black !important;font-family:verdana;font-size:0.85em;">';
                while($stock_productos_r = $stock_productos->fetch(PDO::FETCH_ASSOC))
                { 
                    $style_cod  = $stock_productos_r['style_cod'];
                    $prod_cod  = $stock_productos_r['prod_cod'];
                    $prod_desc  = $stock_productos_r['prod_desc'];
                    $stock_inicial  = $stock_productos_r['stock_inicial'];
                    $stock_actual  = $stock_productos_r['stock_actual'];

                    $stock_entregados = $stock_inicial - $stock_actual;

                    $output .= ' <tr style="background-color:#E2EFDA;">
                                    <td class="text-center">'.$style_cod.'</td>
                                    <td class="text-center">'.$prod_cod.'</td>
                                    <td class="text-left">'.$prod_desc.'</td>
                                    <td class="text-center">'.$stock_inicial.'</td>
                                    <td class="text-center">'.$stock_entregados.'</td>
                                    <td class="text-center">'.$stock_actual.'</td>
                                </tr>';

                    $suma_inicio += $stock_inicial;
                    $suma_entregados += $stock_entregados;
                    $suma_actual += $stock_actual;
                }
                $output .= ' <tr >
                                <td class="text-center text-white font-weight-bold" colspan="3" style="background-color:#588D9C;">Stock actual</td>
                                <td class="text-center" style="background-color:#FFC000;">'.$suma_inicio.'</td>
                                <td class="text-center" style="background-color:#FFC000;">'.$suma_entregados.'</td>
                                <td class="text-center" style="background-color:#FFC000;">'.$suma_actual.'</td>
                            </tr>';
            $output .= '</tbody></table>';
        }else
        {
            $output = 'No registrado';
        }   
    }
    return $output;
}
public function insert_punto_marcacion($user_data, $med_cod, $fecha_mm, $estado, $periodo)
{
    $output = null;

    $data_array = _data_array_medico($med_cod, $user_data);

    $nombre_r = $data_array['nombre'];
    $correlativo_r = $data_array['correlativo'];
    $especialidad_r = $data_array['especialidad'];
    $direccion_r = $data_array['direccion'];
    $institucion_r = $data_array['institucion'];
    $localidad_r = $data_array['localidad'];
    $categoria_r = $data_array['categoria'];
    $alta_baja_r = 'A';
    $ubigeo_r = $data_array['ubigeo'];

    $prod_cod = 0;
    $prod_name = 0;
    $prod_cant = 0;
    $supervisor = 0;    

    $insert = $this->db->prepare('INSERT INTO tbl_medpro_detalle(medpro_vendedor, medpro_medico_cmp, 
                                                    medpro_medico_corr, medpro_medico_nombre, medpro_medico_esp,
                                                    medpro_medico_categoria, medpro_medico_direccion, medpro_medico_localidad,
                                                    medpro_medico_institucion, medpro_medico_altabaja,
                                                    medpro_medico_ubigeo, medpro_producto, 
                                                    medpro_descripcion_producto, medpro_cantidad, medpro_fecha, 
                                                    medpro_estado, medpro_supervisor) 
                                    VALUES (:user_data, :med_cod, :correlativo_r, :nombre_r, :especialidad_r, :categoria_r, :direccion_r, 
                                            :localidad_r, :institucion_r, :alta_baja_r, :ubigeo_r, :prod_cod, :prod_name, :prod_cant, 
                                            :fecha_mm, :estado, :supervisor)');
    
    $insert->bindParam(':user_data', $user_data);
    $insert->bindParam(':med_cod', $med_cod);
    $insert->bindParam(':correlativo_r', $correlativo_r);
    $insert->bindParam(':nombre_r', $nombre_r);
    $insert->bindParam(':especialidad_r', $especialidad_r);
    $insert->bindParam(':categoria_r', $categoria_r);
    $insert->bindParam(':direccion_r', $direccion_r);
    $insert->bindParam(':localidad_r', $localidad_r);
    $insert->bindParam(':institucion_r', $institucion_r);
    $insert->bindParam(':alta_baja_r', $alta_baja_r);
    $insert->bindParam(':ubigeo_r', $ubigeo_r);
    $insert->bindParam(':prod_cod', $prod_cod);
    $insert->bindParam(':prod_name', $prod_name);
    $insert->bindParam(':prod_cant', $prod_cant);
    $insert->bindParam(":fecha_mm", $fecha_mm);
    $insert->bindParam(":estado", $estado);
    $insert->bindParam(":supervisor", $supervisor);
    if($insert->execute())
    {
        $output = date(' d/m/Y H:i ');
    }else
    {
        $output = errorPDO($insert);
        return false;
    }  
    return $output;
}
public function finalizar_marcacion($med_cod, $fecha_mm, $user_data)
{
    $output = null;
    $data_array = _data_array_medico($med_cod, $user_data);
    $estado = 5;
    $nombre_r = $data_array['nombre'];
    $correlativo_r = $data_array['correlativo'];
    $especialidad_r = $data_array['especialidad'];
    $direccion_r = $data_array['direccion'];
    $institucion_r = $data_array['institucion'];
    $localidad_r = $data_array['localidad'];
    $categoria_r = $data_array['categoria'];
    $alta_baja_r = 'A';
    $ubigeo_r = $data_array['ubigeo'];

    $prod_cod = 0;
    $prod_name = 0;
    $prod_cant = 0;
    $supervisor = 0;    

    $insert = $this->db->prepare('INSERT INTO tbl_medpro_detalle(medpro_vendedor, medpro_medico_cmp, 
                                                    medpro_medico_corr, medpro_medico_nombre, medpro_medico_esp,
                                                    medpro_medico_categoria, medpro_medico_direccion, medpro_medico_localidad,
                                                    medpro_medico_institucion, medpro_medico_altabaja,
                                                    medpro_medico_ubigeo, medpro_producto, 
                                                    medpro_descripcion_producto, medpro_cantidad, medpro_fecha, 
                                                    medpro_estado, medpro_supervisor) 
                                    VALUES (:user_data, :med_cod, :correlativo_r, :nombre_r, :especialidad_r, :categoria_r, :direccion_r, 
                                            :localidad_r, :institucion_r, :alta_baja_r, :ubigeo_r, :prod_cod, :prod_name, :prod_cant, 
                                            :fecha_mm, :estado, :supervisor)');
    
    $insert->bindParam(':user_data', $user_data);
    $insert->bindParam(':med_cod', $med_cod);
    $insert->bindParam(':correlativo_r', $correlativo_r);
    $insert->bindParam(':nombre_r', $nombre_r);
    $insert->bindParam(':especialidad_r', $especialidad_r);
    $insert->bindParam(':categoria_r', $categoria_r);
    $insert->bindParam(':direccion_r', $direccion_r);
    $insert->bindParam(':localidad_r', $localidad_r);
    $insert->bindParam(':institucion_r', $institucion_r);
    $insert->bindParam(':alta_baja_r', $alta_baja_r);
    $insert->bindParam(':ubigeo_r', $ubigeo_r);
    $insert->bindParam(':prod_cod', $prod_cod);
    $insert->bindParam(':prod_name', $prod_name);
    $insert->bindParam(':prod_cant', $prod_cant);
    $insert->bindParam(":fecha_mm", $fecha_mm);
    $insert->bindParam(":estado", $estado);
    $insert->bindParam(":supervisor", $supervisor);
    if($insert->execute())
    {
        $output = date(' d/m/Y H:i ');
    }else
    {
        $output = errorPDO($insert);
        return false;
    }  
    return $output;
}
public function table_medicos_x_visitar($year, $month, $user_session)
{
    #codgio ingresado debe ser tanto el personalizado o normal
    //$user_session = info_usuario('usuario');
    $output = null;

    $goSQL = null;

    $user_existe_medico = user_existe_medico($user_session);

    $SQL_MED1 = "SELECT 
                    medico_cmp AS cmp,
                    medico_nombre AS nombre,
                    medico_especialidad AS especialidad,
                    medico_categoria AS categoria,
                    medico_direccion AS direccion,
                    medico_institucion AS institucion,
                    medico_localidad AS localidad
                FROM
                    tbl_maestro_medicos
                WHERE
                    medico_especialidad != ''
                        AND medico_categoria != ''
                        AND medico_alta_baja != 'B'
                        AND medico_zona = (SELECT 
                            representante_zonag
                        FROM
                            tbl_representantes
                        WHERE
                            representante_codigo = :user_session)
                        AND medico_representante = :user_session";
    $SQL_MED2 = "SELECT 
                    medico_cmp AS cmp,
                    medico_nombre AS nombre,
                    medico_especialidad AS especialidad,
                    medico_categoria AS categoria,
                    medico_direccion AS direccion,
                    medico_institucion AS institucion,
                    medico_localidad AS localidad
                FROM
                    tbl_maestro_medicos
                WHERE
                    medico_especialidad != ''
                        AND medico_categoria != ''
                        AND medico_alta_baja != 'B'
                        AND medico_zona = (SELECT 
                            representante_zonag
                        FROM
                            tbl_representantes
                        WHERE
                            representante_codigo = :user_session)
                      AND medico_representante IS NULL;";

    if($user_existe_medico == 1)
    {
        $goSQL = $SQL_MED1;
    }else
    {
        $goSQL = $SQL_MED2;
    }
    $medicos = $this->db->prepare($goSQL);
    $medicos->bindparam(':user_session', $user_session);
    if($medicos->execute())
    {
        if($medicos->rowCount() > 0)
        {
            $output .= '<table id="table-medicos_x_visitar" class="table-condensed table table-hover table-bordered table-sm"
                        style="width:auto !important;font-size:0.85em;font-weight: regular;font-family: Montserrat, sans-serif;">
                    <thead class="text-white " style="background-color:#588D9C;">
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Esp.</th>
                        <th class="text-center">Catg.</th>
                        <th class="text-center">Institución</th>
                        <th class="text-center">Dirección</th>
                        <th class="text-center">Estado visita</th>
                    </thead></table>';
            while($medicos_r = $medicos->fetch(PDO::FETCH_ASSOC))
            {
                $cmp = $medicos_r['cmp'];
                $nombre = $medicos_r['nombre'];
                // $correlative = $medicos_r['correlativo'];
                $especialidad = $medicos_r['especialidad'];
                $categoria = $medicos_r['categoria'];
                $direccion = $medicos_r['direccion'];
                $institucion = $medicos_r['institucion'];
                $localidad = $medicos_r['localidad'];

                $validar_medico_visitas = medico_visitado_x_categoria($user_session, $cmp, $categoria, $year, $month);
                $explo_validator = explode('|~|', $validar_medico_visitas);

                $visualizar_ = $explo_validator[0];
                $mensaje_ = $explo_validator[1];

                if($visualizar_ != 0)
                {
                    $result['cmp'] = $cmp;
                    $result['nombre'] = $nombre;
                    $result['especialidad'] = $especialidad;
                    $result['categoria'] = $categoria;
                    $result['institucion'] = $institucion;
                    $result['direccion'] = $direccion.' - '.$localidad;;
                    $result['mensaje_'] = $mensaje_;
                    // $result['localidad'] = $localidad;

                    $data['data'][] = $result;
                }
            }
        }else
        {
            $output = '0';  
            $data['error'] = 'No hay registros';
        }   
    }
    if($data != 0)
    {
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
    }else
    {
        $json_data = 0;
    }
    return $output.'|~|'.$json_data;
}
public function cobertura_representante($representantes, $mes, $year)#cobertura
{
    $output = null;

    $Exec_ESP = null;
    $Exec_Realizado = null;
    $Exec_Pronostico = null;

    $user_existe_medico = user_existe_medico($representantes);

    $Query_ESP1 = "SELECT DISTINCT
                        (medico_especialidad) AS especialidades
                    FROM
                        tbl_maestro_medicos
                    WHERE
                        medico_zona = (SELECT 
                                representante_zonag
                            FROM
                                tbl_representantes
                            WHERE
                                representante_codigo = :representantes)
                            AND medico_alta_baja != 'B'
                            AND medico_representante = :representantes
                    GROUP BY medico_especialidad
                    ORDER BY 1 ASC;";

    $Query_ESP2 = "SELECT DISTINCT
                        (medico_especialidad) AS especialidades
                    FROM
                        tbl_maestro_medicos
                    WHERE
                        medico_zona = (SELECT 
                                representante_zonag
                            FROM
                                tbl_representantes
                            WHERE
                                representante_codigo = :representantes)
                            AND medico_alta_baja != 'B'
                            AND medico_representante IS NULL
                    GROUP BY medico_especialidad
                    ORDER BY 1 ASC;";

    $Query_REALIZADO0 = "SELECT 
                            COUNT(DISTINCT medpro_medico_cmp, medpro_fecha) AS realizado
                        FROM
                            tbl_medpro_detalle
                        WHERE
                            medpro_vendedor = :representantes
                                AND YEAR(medpro_fecha) = :year
                                AND MONTH(medpro_fecha) = :mes
                                AND medpro_medico_esp = :especialidades
                                AND medpro_medico_categoria = :categorias
                                AND medpro_estado IN(1,2);";

    $Query_PRONOSTICO1 = "SELECT 
                                COUNT(medico_cmp) AS pronostico
                            FROM
                                tbl_maestro_medicos
                            WHERE
                                medico_categoria = :categorias
                                    AND medico_especialidad = :especialidades
                                    AND medico_representante = :representantes;";
    $Query_PRONOSTICO2 = "SELECT 
                                COUNT(medico_cmp) AS pronostico
                            FROM
                                tbl_maestro_medicos
                            WHERE
                                medico_categoria = :categorias
                                    AND medico_especialidad = :especialidades
                                    AND medico_zona = (SELECT 
                                            representante_zonag
                                        FROM
                                            tbl_representantes
                                        WHERE
                                            representante_codigo = :representantes
                                            AND medico_alta_baja = 'A')
                                    AND medico_representante IS NULL;";


    if($user_existe_medico == 1)
    {
        $Exec_ESP = $Query_ESP1;
        $Exec_Realizado = $Query_REALIZADO0;
        $Exec_Pronostico = $Query_PRONOSTICO1;
    }else
    {
        $Exec_ESP = $Query_ESP2;
        $Exec_Realizado = $Query_REALIZADO0;
        $Exec_Pronostico = $Query_PRONOSTICO2;
    }


    try
    {
        $especialidad_q = $this->db->prepare($Exec_ESP);
        $especialidad_q->bindParam(":representantes", $representantes);
        if($especialidad_q->execute())
        {
            if($especialidad_q->rowCount() > 0)
            {
                $output .= '<div class="" style="max-width:150px;">
                    <div class="input-group form-group" >
                        <span class="input-group-addon bg-primary text-white input-sm">
                            <b class="">Cobertura</b>
                        </span>
                        <span class="form-control text-center input-sm col-lg-1" style="border-color:#78BBE6;font-weight:bold;" id="avance-cobertura"></span>
                    </div>
                </div>
                <div class="col-md-12" >
                <table id="tablecobertura" class="table table-bordered table-condensed table-striped table-sm" style="width:auto !important;font-size:0.85em;font-weight: regular;font-family: Montserrat, sans-serif;">
                    <thead>
                        <th style="background-color:#95A792;" class="tablesmall2 text-center text-white">Esp.</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">AA</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">AA</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">A</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">A</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">B</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">B</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">C</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">C</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">Total</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">Total</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">Pendiente</th>
                    </thead><tbody class="text-center" style="color:black;">';

                    /*$output .= '<div class="col-md-2" >
                    <div class="input-group form-group" >
                        <span class="input-group-addon bg-primary text-white">
                            <span class="">Cobertura: </span>
                        </span>
                        <span class="form-control text-center" style="border-color:#78BBE6;" id="avance-cobertura"></span>
                    </div>
                </div>
                <div class="col-md-12" >
                <table id="tablecobertura" class="table table-bordered table-condensed table-striped" style="width:auto;">
                    <thead>
                        <th style="background-color:#95A792;" class="tablesmall2 text-center text-white">Especialidad</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">AA</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">AA</th>
                        <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">A</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">A</th>
                        <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">B</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">B</th>
                        <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">C</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">C</th>
                        <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">Total</th>
                        <th style="background-color:#415B90;" class="tablesmall2 text-center text-white">Total</th>
                        <th style="background-color:#6088BB;" class="tablesmall2 text-center text-white">Cob(%)</th>
                        <th style="background-color:#F1E290;" class="tablesmall2 text-center">Pendiente</th>
                    </thead><tbody class="text-center" style="font-size:1em !important;">';*/

                while($especialidad_r = $especialidad_q->fetch(PDO::FETCH_ASSOC))
                {
                    $especialidades = $especialidad_r['especialidades'];

                    $categoriasArray = array(0 => 'AA', 1 => 'A', 2 => 'B', 3 => 'C');
                    
                    $mp = 0;
                    $tota1 = 0;
                    $totalpro = 0;
                    $totalvisi = 0;

                    $circle = null;

                    if($especialidades == '')
                    {
                        $output .= '<tr class=""><td>   </td>';
                    }else
                    {
                        $output .= '<tr class=""><td>'.$especialidades.'</td>';
                    }

                    for($i = 0; $i <= count($categoriasArray) - 1; $i++)
                    {
                        $categorias = $categoriasArray[$i];

                        $realizado_q = $this->db->prepare($Exec_Realizado);

                        $realizado_q->bindParam(":representantes", $representantes, PDO::PARAM_INT);
                        $realizado_q->bindParam(":mes", $mes, PDO::PARAM_INT);
                        $realizado_q->bindParam(":year", $year, PDO::PARAM_INT);
                        $realizado_q->bindParam(":categorias", $categorias, PDO::PARAM_STR);
                        $realizado_q->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
                    
                        $pronostico_q = $this->db->prepare($Exec_Pronostico);
                        $pronostico_q->bindParam(":categorias", $categorias, PDO::PARAM_STR);
                        $pronostico_q->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
                        $pronostico_q->bindParam(":representantes", $representantes, PDO::PARAM_INT);

                        if($realizado_q->execute() && $pronostico_q->execute())
                        {
                            if($categorias == 'AA'){$mp = 4;}elseif($categorias == 'A'){$mp = 3;}elseif($categorias == 'B'){$mp = 2;}elseif($categorias == 'C'){$mp = 1;}else{$mp = 0;}

                            $realizado_r = $realizado_q->fetch(PDO::FETCH_ASSOC);
                            $pronostico_r = $pronostico_q->fetch(PDO::FETCH_ASSOC);

                            $Realizado = $realizado_r['realizado'];
                            $Pronosticado = ($pronostico_r['pronostico'] * $mp);

                            if($Pronosticado == 0)
                            {
                                $output .= '<td>'.$Pronosticado.'</td>';
                            }else
                            {
                                $output .= '<td style="cursor:pointer;color:#2980B9 !important;" class="tablesmall2 font-weight" onclick="cobertura_no_visitados('."'".$especialidades."'".','."'".$categorias."'".','."'".$representantes."'".','."'".$mes."'".','."'".$year."'".');"><b>'.$Pronosticado.'</b></td>';
                            }
                            if($Realizado == 0)
                            {
                                $output .= '<td>'.$Realizado.'</td>';
                            }else
                            {
                                $output .= '<td style="cursor:pointer;color:#2980B9 !important;" class="tablesmall2 font-weight" onclick="cobertura_visitados('."'".$especialidades."'".','."'".$categorias."'".','."'".$representantes."'".','."'".$mes."'".','."'".$year."'".');"><b>'.$Realizado.'</b></td>';
                            }

                            @$total2 = round(($Realizado * 100)/$Pronosticado);

                            if($total2 >= 75)
                            {
                                $circle = '<span style="color:#6088BB;"><i class="fa fa-circle"></i></span>';
                            }elseif($total2 >= 60)
                            {
                                $circle = '<span style="color:#F1E290;"><i class="fa fa-circle"></i></span>';
                            }
                            else
                            {
                                $circle = '<span style="color:#C70039;"><i class="fa fa-circle"></i></span>';
                            }

                            // if($total2 == 0)
                            // {
                            //     $output .= '<td>'.$circle.'&nbsp;'.$total2.'%</td>';
                            // }else
                            // {
                            //     if(is_nan($total2))
                            //     {
                            //         $total2 = 0;
                            //     }else
                            //     {
                            //         $total2 = $total2;
                            //     }
                            //     $output .= '<td>'.$circle.'&nbsp;'.$total2.'%</td>';
                            // }

                            $totalpro += $Pronosticado;
                            $totalvisi += $Realizado;
                        }
                    }
                        @$totalcob = round(($totalvisi * 100)/$totalpro);
                        if($totalcob >= 75)
                        {
                            $circle = '<span style="color:#6088BB;"><i class="fa fa-circle"></i></span>';
                        }elseif($totalcob >= 60)
                        {
                            $circle = '<span style="color:#F1E290;"><i class="fa fa-circle"></i></span>';
                        }else
                        {
                            $circle = '<span style="color:#C70039;"><i class="fa fa-circle"></i></span>';}

                        $output .= '<td class="tablesmall2">'.$totalpro.'</td>';
                        $output .= '<td class="tablesmall2">'.$totalvisi.'</td>';
                        // $output .= '<td class="tablesmall2">'.$circle.'&nbsp;'.$totalcob.'%</td>';
                        $output .= '<td id="" class="tablesmall2">'.($totalpro - $totalvisi).'</td>';

                        $output .= '</tr>';
                }
            }
            $output .= '</tbody>
                            <tfoot style="color:black;border-color:blue !important;" class="text-center">
                                <tr>
                                    <td style="background-color:#6088BB;" class="text-white tablesmall2">Total</td>
                                    <td id="tfooter1" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter2" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter4" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter5" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter7" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter8" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter10" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter11" style="background-color:#C2DBC1;"></td>
                                    <td id="tfooter13" class="text-white" style="background-color:#73B1C1;"></td>
                                    <td id="tfooter14" class="text-white" style="background-color:#DD3E3E;"></td>
                                    <td id="tfooter16" style="background-color:#C2DBC1;"></td>
                                </tr>
                            </tfoot>
                        </table></div><div class="col-md-2"></div>';
        }else
        {
            $output = errorPDO($espcialidad_q);
        }   
    }catch(PDOException $e)
    {
        $output = $e->getMessage();
    }
    return $output;
}
/****************** PASTE ********************* */
public function cobertura_no_visitados($especialidades, $categorias, $representantes, $mes, $year)
{
    $output = null;

    $Exec_Query1 = null;
    $Exec_Realizado = null;
    $Exec_Pronostico = null;

    $user_existe_medico = user_existe_medico($representantes);

    $qqq = "SELECT 
            medico_cmp AS med_cod,
            medico_nombre AS med_name,
            medico_direccion AS med_dir,
            medico_especialidad AS med_esp,
            medico_categoria AS med_cat,
            medico_localidad AS med_local
        FROM
            tbl_maestro_medicos
        WHERE
            medico_zona = (SELECT 
                    representante_zonag
                FROM
                    tbl_representantes
                WHERE
                    representante_codigo = :representantes)

                AND medico_representante = :representantes
                
                AND medico_categoria = :categorias
                AND medico_especialidad = :especialidades
                AND medico_cmp NOT IN (SELECT DISTINCT
                    medpro_medico_cmp
                FROM
                    tbl_medpro_detalle
                WHERE
                    medpro_vendedor = :representantes
                        AND YEAR(medpro_fecha) = :year
                        AND MONTH(medpro_fecha) = :mes)
                AND medico_alta_baja != 'B'
        GROUP BY 1
        ORDER BY 6 ASC;";
        
    $SQL_Query1 = "SELECT 
                        medico_cmp AS med_cod,
                        medico_nombre AS med_name,
                        medico_direccion AS med_dir,
                        medico_especialidad AS med_esp,
                        medico_categoria AS med_cat,
                        medico_localidad AS med_local
                    FROM
                        tbl_maestro_medicos
                    WHERE
                        medico_zona = (SELECT 
                                representante_zonag
                            FROM
                                tbl_representantes
                            WHERE
                                representante_codigo = :representantes)
                        AND medico_representante = :representantes
                        AND medico_categoria = :categorias
                        AND medico_especialidad = :especialidades
                        AND medico_alta_baja != 'B'
                    GROUP BY 1
                    ORDER BY 6 ASC;";
    
    $SQL_Query2 = "SELECT 
                        medico_cmp AS med_cod,
                        medico_nombre AS med_name,
                        medico_direccion AS med_dir,
                        medico_especialidad AS med_esp,
                        medico_categoria AS med_cat,
                        medico_localidad AS med_local
                    FROM
                        tbl_maestro_medicos
                    WHERE
                        medico_zona = (SELECT 
                                representante_zonag
                            FROM
                                tbl_representantes
                            WHERE
                                representante_codigo = :representantes)
                        AND medico_representante IS NULL
                        AND medico_categoria = :categorias
                        AND medico_especialidad = :especialidades
                        AND medico_alta_baja != 'B'
                    GROUP BY 1
                    ORDER BY 6 ASC;";

    if($user_existe_medico == 1)
    {
        $Exec_Query1 = $SQL_Query1;
    }else
    {
        $Exec_Query1 = $SQL_Query2;
    }
    
    try
    {
        $consulta = $this->db->prepare($Exec_Query1);

        $consulta->bindParam(":categorias", $categorias, PDO::PARAM_STR);
        $consulta->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
        $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
        // $consulta->bindParam(":mes", $mes, PDO::PARAM_INT);
        // $consulta->bindParam(":year", $year, PDO::PARAM_INT);
        if($consulta->execute())
        {
            if($consulta->rowCount() > 0)
            {
                while ($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                {
                    $med_cod = $consulta_r['med_cod'];
                    $med_name = $consulta_r['med_name'];
                    $med_dir = $consulta_r['med_dir'];
                    $med_esp = $consulta_r['med_esp'];
                    $med_cat = $consulta_r['med_cat'];
                    $med_local = $consulta_r['med_local'];

                    $cant_visitado =  _validate_medico_visitado($med_cod, $representantes, $mes, $year);
                    $visitado_ = null;

                    if($cant_visitado > 0)
                    {
                        $visitado_ = 'Visitado';
                    }else
                    {
                        $visitado_ = 'No visitado';
                    }
                    $output .= '<tr style="border-color:blue;">
                                
                                <td style="font-size:0.75em;" class="text-left">'.$med_name.'</td>
                                <td style="font-size:0.75em;" class="text-center">'.$med_local.'</td>
                                <td style="font-size:0.75em;" class="text-left">'.$med_dir.'</td>
                                <td style="font-size:0.75em;" class="text-center">'.$visitado_.'</td>
                              </tr>';
                            //   <td style="font-size:0.75em;" class="text-left">'.$med_cod.'</td>
                    /*<td style="font-size:0.75em;" class="text-center">'.$med_esp.'</td>
                    <td style="font-size:0.75em;" class="text-center">'.$med_cat.'</td>*/
                }
            }else
            {
                $output = '<tr>
                                <td style="font-size:0.75em;" class="text-center">-</td>
                                <td style="font-size:0.75em;" class="text-center">-</td>
                                <td style="font-size:0.75em;" class="text-center">-</td>
                                <td style="font-size:0.75em;" class="text-center">-</td>
                            </tr>';
            }
        }else
        {
            $output = errorPDO($consulta);
        }
    }catch(PDOException $e)
    {
        $output = $e->getMessage();
    }
    return $output;
}
public function cobertura_visitados($especialidades, $categorias, $representantes, $mes, $year)
{
    $output = null;

    try
    {
        $consulta = $this->db->prepare("SELECT 
                                            medpro_medico_cmp AS codigo_medico,
                                            medpro_medico_nombre AS nombre_medico,
                                            COUNT(DISTINCT medpro_medico_cmp, medpro_fecha) AS cantidad,
                                            GROUP_CONCAT(DISTINCT DAY(medpro_fecha) ORDER BY medpro_fecha ASC) AS fecha
                                        FROM
                                            tbl_medpro_detalle
                                        WHERE
                                            medpro_vendedor = :representantes
                                                AND medpro_medico_altabaja != 'B'
                                                AND medpro_medico_esp = :especialidades
                                                AND medpro_medico_categoria = :categorias
                                                AND MONTH(medpro_fecha) = :mes
                                                AND YEAR(medpro_fecha) = :year
                                        GROUP BY medpro_medico_nombre , medpro_medico_cmp
                                        ORDER BY 3 DESC");
        $consulta->bindParam(":especialidades", $especialidades, PDO::PARAM_STR);
        $consulta->bindParam(":categorias", $categorias, PDO::PARAM_STR);
        $consulta->bindParam(":representantes", $representantes, PDO::PARAM_INT);
        $consulta->bindParam(":mes", $mes, PDO::PARAM_INT);
        $consulta->bindParam(":year", $year, PDO::PARAM_INT);
        if($consulta->execute())
        {
            if($consulta->rowCount() > 0)
            {
                while($consulta_r = $consulta->fetch(PDO::FETCH_ASSOC))
                {
                    $codigo_medico__ = $consulta_r['codigo_medico'];
                    $medico = $consulta_r['nombre_medico'];
                    $visitas = $consulta_r['cantidad'];
                    $fechas_visitadas = explode(',', $consulta_r['fecha']);

                    $fechashref = '';

                    if(count($fechas_visitadas) > 0)
                    {
                        $dias = '';
                        for ($f = 0; $f <= count($fechas_visitadas) - 1; $f++)
                        {
                            $fechashref .= ' - <a href="javascript:void(0);" onclick="cobertura_fecha_productos('."'".$consulta_r['codigo_medico']."'".','."'".$fechas_visitadas[$f]."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');">'.$fechas_visitadas[$f].'</a>';
                        }
                    }else
                    {
                        $fechashref = '<a href="javascript:void(0);" onclick="cobertura_fecha_productos('."'".$consulta_r['codigo_medico']."'".','."'".$consulta_r['fecha']."'".','."'".$mes."'".','."'".$year."'".','."'".$representantes."'".','."'".$consulta_r['nombre_medico']."'".');">D&iacute;a'.$consulta_r['fecha'].'</a>';
                    }

                    $output .= '<tr>
                                    
                                    <td style="font-size:0.75em;" class="text-center">'.$medico.'</td>
                                    <td style="font-size:0.75em;" class="text-center">'.$visitas.'</td>
                                    <td style="font-size:0.75em;" class="text-center">Dias '.trim($fechashref, '-').'</td>';
                                    // <td style="font-size:0.75em;" class="text-center">'.$codigo_medico__.'</td>
                }
            }else
            {
                $output = errorPDO($consulta);
            }
        }else
        {
            $output = errorPDO($consulta);
        }
    }catch(PDOException $e)
    {
        $output = $e->getMessage();
    }
    return $output;
}

    
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}