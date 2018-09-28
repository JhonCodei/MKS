<?php

Class PedidosModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function validar_cliente_ruc($ruc)
    {
        $output = null;

        $query = $this->db->prepare("SELECT 
                                                        nombre_comercial
                                                    FROM
                                                        maestro_clientes
                                                        WHERE ruc = :ruc
                                                    LIMIT 1;");
        $query->bindParam(":ruc", $ruc);
        if($query->execute())
        {
            if($query->rowCount() == 0)
            {
                $insert =  $this->db->prepare("INSERT INTO maestro_clientes(ruc, nombre_comercial)
                                                                        VALUES(:ruc,'No registrado')");
                $insert->bindParam(":ruc", $ruc);
                $insert->execute();    
            }
        }
    }
    public function _buscar_cliente($cliente_ruc)
    {
        #codgio ingresado debe ser tanto el personalizado o normal
        //$user_session = info_usuario('usuario');
        $output = null;
        $WHERE = NULL;

        $goSQL = null;

        if($cliente_ruc != 'S')
        {
            $WHERE = " WHERE ruc = '$cliente_ruc' ";
        }

        $SQL = "SELECT 
								    ruc, nombre_comercial, razon_social
								FROM
								    maestro_clientes
								$WHERE GROUP BY id;";

        $clientes = $this->db->prepare($SQL);
        if($clientes->execute())
        {
            if($clientes->rowCount() > 0)
            {
                if($WHERE != NULL)
                {
                    $clientes_r = $clientes->fetch(PDO::FETCH_ASSOC);

                    $ruc = $clientes_r['ruc'];
                    $nombre_comercial = $clientes_r['nombre_comercial'];
                    $razon_social = $clientes_r['razon_social'];

                    #$output = $ruc.'~~'.$nombre_comercial. ' - ' . $razon_social;
                    $output = $ruc.'~~'.$nombre_comercial;

                    $type = '1';

                }else
                {   $output .= '<label class="h3">Clientes</label>
                                    <button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" 
                                    data-dismiss="modal" aria-hidden="true" onclick="return close_modal('."'_modal_buscar_cliente'".');">
                                    <span class="fa fa-close"></span></button>
                                    <table id="table-listar-clientes" class="table table-condensed table-hover table-bordered table-sm display" style="width:100% !important;font-size:0.9em;">
                                    <thead class="text-white " style="background-color:#588D9C;max-width:100% !important;">
                                        <th class="text-center">#</th>
                                        <th class="text-center">Ruc</th>
                                        <th class="text-center">Nombre C.</th>
                                        <th class="text-center">Razon S.</th>
                                    </thead>
                                    <tbody style="color:black;font-family:verdana;font-size:0.85em;">';
                    while($clientes_r = $clientes->fetch(PDO::FETCH_ASSOC))
                    {
                        $ruc = $clientes_r['ruc'];
	                    $nombre_comercial = $clientes_r['nombre_comercial'];
	                    $razon_social = $clientes_r['razon_social'];

                        $output .= ' <tr>
                                        <td>
                                            <button class="btn btn-default btn-sm waves-effect waves-light" onclick="send_field_('."'cliente_ruc'".','."'".$ruc."'".', '."'cliente_name'".', '."'".$nombre_comercial."'".')"><span class="fa fa-plus"></span></button>
                                        </td>
                                        <td>'.$ruc.'</td>
                                        <td class="text-left">'.$nombre_comercial.'</td>
                                        <td class="text-center">'.$razon_social.'</td>
                                    </tr>';
                    }
                    $output .= '</tbody></table>';
                    $type = '0';
                }
            }else
            {
                $output = $cliente_ruc.'~~No registrado';
                $type = '1';
            }   
        }
        return $type.'||'.$output;
    }
    public function _buscar_vendedor($cod_sup)
    {
        $output = null;
        $WHERE = null;

        if($cod_sup != 'S')
        {
            $WHERE = " AND representante_codigo = '$cod_sup' ";
        }

        $vendedor = $this->db->prepare("SELECT 
                                            representante_codigo AS codigo,
                                            representante_nombre AS nombre
                                        FROM
                                            tbl_representantes
                                        WHERE
                                            representante_cargo IN(3)
                                            AND representante_region IN (99,1)
                                            AND representante_codigo NOT IN(218,51)
                                                AND representante_estado = 'A' 
                                                $WHERE;");
        if($vendedor->execute())
        {
            if($vendedor->rowCount() > 0)
            {
                if($WHERE != NULL)
                {
                    $vendedor_r = $vendedor->fetch(PDO::FETCH_ASSOC);

                    $codigo = $vendedor_r['codigo'];
                    $nombre = strtoupper($vendedor_r['nombre']);

                    $output = $codigo.'~~'.$nombre;
                    $type = '1';
                }else
                {   $output .= '<label class="h3">Representante</label>
                                        <button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" 
                                        data-dismiss="modal" aria-hidden="true" onclick="return close_modal('."'_modal_buscar_vendedor'".');">
                                        <span class="fa fa-close"></span></button>
                                    <table id="table-listar-vendedor" class="table table-condensed table-hover table-bordered table-sm display" style="width:100% !important;font-size:0.9em;">
                                    <thead class="text-white" style="background-color:#588D9C;">
                                        <th class="text-center">#</th>
                                        <th class="text-center">Codigo</th>
                                        <th class="text-center">Nombre</th>
                                    </thead>
                                    <tbody style="color:black;font-family:verdana;font-size:0.85em;">';
                    while($vendedor_r = $vendedor->fetch(PDO::FETCH_ASSOC))
                    {
                        $codigo = $vendedor_r['codigo'];
                        $nombre = strtoupper($vendedor_r['nombre']);

                        $output .= ' <tr>
                                        <td>
                                            <button class="btn btn-default btn-sm waves-effect waves-light" onclick="__send_values__('."'cod_vend~~name_vend'".', '."'".$codigo."~~".$nombre."' ".', '."'val~~val'".', '."'_modal_buscar_vendedor'".');">
                                            <span class="fa fa-plus"></span></button>
                                        </td>
                                        <td>'.$codigo.'</td>
                                        <td class="text-left">'.$nombre.'</td>
                                    </tr>';
                    }
                    $output .= '</tbody></table>';
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
    public function _buscar_producto($prod_cod, $target)
    {
        #codgio ingresado debe ser tanto el personalizado o normal
        $output = null;
        $WHERE = null;

        if($prod_cod != 'S')
        {
            $WHERE = "  WHERE codigo_mks = '$prod_cod' OR codigo = '$prod_cod'; ";
        }
        $productos = $this->db->prepare("SELECT 
															    codigo_mks, codigo, descripcion
															FROM
															    productos_pedidos $WHERE ");
        if($productos->execute())
        {
            if($productos->rowCount() > 0)
            {
                if($WHERE != NULL)
                {
                    $productos_r = $productos->fetch(PDO::FETCH_ASSOC);

                    $codigo_mks  = $productos_r['codigo_mks'];
                    $codigo  = $productos_r['codigo'];
                    $descripcion  = $productos_r['descripcion'];

                    $output = $codigo.'~~'.$descripcion;

                    $type = '1';
                }else
                {   
                    $output .= '<label class="h3">Productos</label>
                                        <button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" 
                                        data-dismiss="modal" aria-hidden="true" onclick="return close_modal('."'_modal_buscar_productos'".');">
                                        <span class="fa fa-close"></span></button>
                                        <table id="table-listar-productos" class="table table-condensed table-hover table-bordered table-sm display" style="width:100% !important;font-size:0.9em;">
                                        <thead class="text-white" style="background-color:#588D9C;font-size:0.8em;">
                                            <th class="text-center">#</th>
                                            <th class="text-center">Cod</th>
                                            <th class="text-center">Articulo</th>
                                            <th class="text-center">Desc Articulo</th>
                                        </thead>
                                        <tbody style="color:black;font-family:verdana;font-size:0.78em;">';
                    while($productos_r = $productos->fetch(PDO::FETCH_ASSOC))
                    {
                        $codigo_mks  = $productos_r['codigo_mks'];
	                    $codigo  = $productos_r['codigo'];
	                    $descripcion  = $productos_r['descripcion'];

                        $output .= ' <tr>
                                        <td class="text-center">
                                            <button class="btn btn-default btn-sm waves-effect waves-light" 
                                            onclick="__send_values__inter('."'$target'".','."'prod_cod_$target~~prod_desc_$target'".', '."'$codigo~~$descripcion'".', '."'val~~text'".',  '."'_modal_buscar_productos'".')">
                                            <span class="fa fa-plus"></span></button>
                                            
                                        </td>
                                        <td class="text-center">'.$codigo_mks.'</td>
                                        <td class="text-center">'.$codigo.'</td>
                                        <td class="text-left" style="font-size:9px;">'.$descripcion.'</td>
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
    public function distribuidoras($select)
    {
        $output = null;

        $query = $this->db->prepare("SELECT distribuidora_codigo AS codigo, 
                                                                        distribuidora_descripcion AS descripcion
                                                            FROM
                                                                        tbl_distribuidoras ORDER BY 1 ASC;");
        if($query->execute())
        {
            if($query->rowCount() > 0)
            {
                while ($rQuery0 = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $dst_cod = $rQuery0['codigo'];
                    $dst_desc = ucfirst(strtolower($rQuery0['descripcion']));    

                    if($select == $dst_cod)
                    {
                        $output .= '<option value="'.$dst_cod.'" selectd>'.$dst_desc.'</option>';
                    }else
                    {
                        $output .= '<option value="'.$dst_cod.'">'.$dst_desc.'</option>';
                    }
                    
                }
            }else
            {
                $output = 0;
            }
        }

        return $output;
    }
    public function precio_lista_x_prod_x_dist($periodo, $codigo_producto, $distribuidora)
    {
        $output = "0||0";

            $SQL = "SELECT 
                            producto_id, descuento, precio_lista
                        FROM
                            precio_productos_distribuidoras
                        WHERE
                                fin >= :periodo AND inicio <= :periodo
                                AND distribuidora = :distribuidora
                                AND producto_id = :codigo_producto;";

            $runQuery1 = $this->db->prepare($SQL);
            $runQuery1->bindParam(":periodo", $periodo);
            $runQuery1->bindParam(":codigo_producto", $codigo_producto);
            $runQuery1->bindParam(":distribuidora", $distribuidora);

            if($runQuery1->execute())
            {
                if($runQuery1->rowCount() > 0)
                {
                    $Query1 = $runQuery1->fetch(PDO::FETCH_ASSOC);
                    $descuento = $Query1['descuento'];
                    $precio_lista = $Query1['precio_lista'];

                    $output = round($precio_lista, 2)."||".round($descuento, 2);
                }
            }
            return $output;
    }
    public function descuento_base($periodo, $codigo_producto, $cantidad)
    {
        $output = 0;

            $SQL = "SELECT 
                            producto_id AS id,
                            unidad_venta_inicio AS u_inicio,
                            unidad_venta_fin AS u_fin,
                            descuento_base AS desc_base,
                            inicio,
                            fin
                        FROM
                            escalas
                        WHERE
                                fin >= :periodo AND inicio <= :periodo
                                AND producto_id = :codigo_producto
                                AND unidad_venta_inicio <= :cantidad
                                AND unidad_venta_fin >= :cantidad;";

            $runQuery1 = $this->db->prepare($SQL);
            $runQuery1->bindParam(":periodo", $periodo);
            $runQuery1->bindParam(":codigo_producto", $codigo_producto);
            $runQuery1->bindParam(":cantidad", $cantidad);

            if($runQuery1->execute())
            {
                if($runQuery1->rowCount() > 0)
                {
                    $Query1 = $runQuery1->fetch(PDO::FETCH_ASSOC);
                    $desc_base = $Query1['desc_base'];
                    $output = $desc_base;
                }
            }
            return $output;
    }
    public function _insertar_pedido($data)
    {
        $output = null;

        $cliente = $data->cliente;
        $condicion_pago = $data->condicion_pago;
        $fecha = $data->fecha;
        $distribuidora = $data->distribuidora;
        $estado_ = $data->estado_;
        $vendedor = $data->vendedor;
        $vendedor_registro = $data->vendedor_registro;

        $array_prod_cod = $data->array_prod_cod;
        $array_prod_name = $data->array_prod_name;
        $array_prod_cant = $data->array_prod_cant;
        $array_prec_list = $data->array_prec_list;
        $array_porc_dscu = $data->array_porc_dscu;
        $array_prec_uni = $data->array_prec_uni;
        $array_prec_uni_igv = $data->array_prec_uni_igv;
        $array_monto_desc = $data->array_monto_desc;
        $array_valor_neto = $data->array_valor_neto;
        $array_monto_line_total = $data->array_monto_line_total;

        $precio = array_sum($array_valor_neto);
        $impuesto = round($precio* 0.18, 2);
        $precio_total = $precio + $impuesto;

        if(is_array($array_prod_cod))
        {
            $prod_cod = explode('||', implode($array_prod_cod, "||"));
            $prod_name = explode('||', implode($array_prod_name, "||"));
            $prod_cant = explode('||', implode($array_prod_cant, "||"));
            $precio_lista = explode('||', implode($array_prec_list, "||"));
            $descuento_ = explode('||', implode($array_porc_dscu, "||"));
            $precio_unitario = explode('||', implode($array_prec_uni, "||"));
            $prec_uni_igv = explode('||', implode($array_prec_uni_igv, "||"));
            $monto_desc = explode('||', implode($array_monto_desc, "||"));
            $valor_neto = explode('||', implode($array_valor_neto, "||"));
            $precio_linea_ = explode('||', implode($array_monto_line_total, "||"));
        }else
        {
            $prod_cod = $array_prod_cod;
            $prod_name = $array_prod_name;
            $prod_cant = $array_prod_cant;
            $precio_lista = $array_prec_list;
            $descuento_ = $array_porc_dscu;
            $precio_unitario = $array_prec_uni;
            $prec_uni_igv = $array_prec_uni_igv;
            $monto_desc = $array_monto_desc;
            $valor_neto = $array_valor_neto;
            $precio_linea_ = $array_monto_line_total;
            
        }

        $pedido = $this->db->prepare("INSERT INTO orden_pedido(cliente_id, vendedor_id, 
                                                        distribuidora_id, fecha_orden, precio, impuesto, precio_total, pago_condicion, vendedor_registro) 
                                        VALUES (:cliente, :vendedor, :distribuidora, :fecha, :precio, :impuesto, :precio_total, :condicion_pago, :vendedor_registro)");
        $pedido->bindParam(":cliente", $cliente);
        $pedido->bindParam(":vendedor", $vendedor);
        $pedido->bindParam(":distribuidora", $distribuidora);
        $pedido->bindParam(":fecha", $fecha);
        $pedido->bindParam(":precio", $precio);
        $pedido->bindParam(":impuesto", $impuesto);
        $pedido->bindParam(":precio_total", $precio_total);
        $pedido->bindParam(":condicion_pago", $condicion_pago);
        $pedido->bindParam(":vendedor_registro", $vendedor_registro);

        if($pedido->execute())
        {
            $orden_pedido_id = $this->db->lastInsertId();

            $pedido_items = $this->db->prepare("INSERT INTO orden_pedido_items(producto_id, producto_dsc, unidades, precio_lista, precio_unitario, descuento, impuesto,precio_linea, orden_pedido_id) 
                                                VALUES (:prod_cod_var, :prod_name_var, :prod_cant_var, :precio_lista, :precio_unitario, :descuento, :impuesto, :precio_linea, :orden_pedido_id);");
        
            for($i = 0; $i <= count($prod_cod) - 1; $i++)
            {
                if($prod_cod[$i] == null){$prod_cod_var = 0;}else{$prod_cod_var = $prod_cod[$i];}
                if($prod_name[$i] == null){$prod_name_var = 0;}else{$prod_name_var = $prod_name[$i];}
                if($prod_cant[$i] == null){$prod_cant_var = 0;}else{$prod_cant_var = $prod_cant[$i];}

                if($precio_lista[$i] == null){$precio_lista_var = 0;}else{$precio_lista_var = $precio_lista[$i];}
                if($precio_unitario[$i] == null){$precio_unitario_var = 0;$impuesto_var = 0;}else{$precio_unitario_var = $precio_unitario[$i];$impuesto_var = round($precio_unitario_var * 0.18,2);}
                if($descuento_[$i] == null){$descuento_var = 0;}else{$descuento_var = $descuento_[$i];}
                #if($impuesto_[$i] == null){$impuesto_var = 0;}else{$impuesto_var = $impuesto_[$i];}
                if($precio_linea_[$i] == null){$precio_linea_var = 0;}else{$precio_linea_var = $precio_linea_[$i];}

                $pedido_items->bindParam(':prod_cod_var', $prod_cod_var);
                $pedido_items->bindParam(':prod_name_var', $prod_name_var);
                $pedido_items->bindParam(':prod_cant_var', $prod_cant_var);
                $pedido_items->bindParam(':precio_lista', $precio_lista_var);
                $pedido_items->bindParam(':precio_unitario', $precio_unitario_var);
                $pedido_items->bindParam(':descuento', $descuento_var);
                $pedido_items->bindParam(':impuesto', $impuesto_var);
                $pedido_items->bindParam(':precio_linea', $precio_linea_var);
                $pedido_items->bindParam(':orden_pedido_id', $orden_pedido_id);

                if($pedido_items->execute())
                {
                    $output = 1;
                }else
                {
                    $output = "ERROR 2 = ".errorPDO($pedido_items);
                    return false;
                }
            }
        }
        return $output;
    }
    public function _buscar_pedido(int $id)
    {
        $output = null;

        $runQuery1 = $this->db->prepare(buscar_pedido_sql());
        $runQuery1->bindParam(":id", $id);
        if($runQuery1->execute())
        {
            $Query1 = $runQuery1->fetch(PDO::FETCH_ASSOC);

            $id = $Query1['id'];
            $vendedor = $Query1['vendedor'];
            $name_vendedor = nombre_corto($vendedor);
            $cliente_ruc = $Query1['cliente_ruc'];
            $nombre_comercial = $Query1['nombre_comercial'];
            $cod_dist = $Query1['cod_dist'];
            $desc_dist = $Query1['desc_dist'];
            $fecha = fecha_db_to_view_2($Query1['fecha']);
            $condicion_pago = $Query1['condicion_pago'];
            $creador_pedido = $Query1['creador_pedido'];
            $id_items = $Query1['id_items'];
            $codigo_producto = $Query1['codigo_producto'];
            $desc_producto = $Query1['desc_producto'];
            $cantidad = $Query1['cantidad'];

            if(strpos($codigo_producto, '||') !== FALSE)
            {
                $codigo_producto_ = explode('||', $codigo_producto);
                $desc_producto_ = explode('||', $desc_producto);
                $cantidad_ = explode('||', $cantidad);
            }else
            {
                $codigo_producto_[] = $codigo_producto; 
                $desc_producto_[] = $desc_producto;
                $cantidad_[] = $cantidad; 
            }
        }else
        {
            $output = errorPDO($runQuery1);
        }
        return $output;
    }
    public function _actualizar_pedidos(array $data)
    {
    }
    public function _eliminar_pedidos(array $data)
    { 
    }
    public function _listar_pedidos($vendedor, $fecha)
    {
        $output = null;

        $runQuery1 = $this->db->prepare(listar_pedido_sql());
        $runQuery1->bindParam(":fecha", $fecha);
        $runQuery1->bindParam(":vendedor", $vendedor);
        if($runQuery1->execute())
        {
            if($runQuery1->rowCount() > 0)
            {
                $output =   '<table id="_listado_pedidos_" class="table table-bordered table-condensed table-sm" style="width:auto !important;font-size:0.9em;color:black;font-family:verdana;">
                                <thead class="text-white" style="background-color:#4D7CAE;font-size:0.9em;">
                                    <th class="text-center print_this">ID</th>
                                    <th class="text-center print_this">Vendedor</th>
                                    <th class="text-center print_this">Ruc</th>
                                    <th class="text-center print_this">Cliente</th>
                                    <th class="text-center print_this">Distribuidora</th>
                                    <th class="text-center print_this">condicion pago</th>
                                    <th class="text-center print_this">Producto código</th>
                                    <th class="text-center print_this">Producto descripción</th>
                                    <th class="text-center print_this">Cantidad</th>
                                    <th class="text-center">Acciones</th>
                                </thead>
                            </table>';
                while($Query1 = $runQuery1->fetch(PDO::FETCH_ASSOC))
                {
                    $id = $Query1['id'];
                    $vendedor = $Query1['vendedor'];
                    $name_vendedor = nombre_corto($vendedor);
                    $cliente_ruc = $Query1['cliente_ruc'];
                    $nombre_comercial = $Query1['nombre_comercial'];
                    $cod_dist = $Query1['cod_dist'];
                    $desc_dist = $Query1['desc_dist'];
                    $fecha = fecha_db_to_view($Query1['fecha']);
                    $condicion_pago = condicion_pago_short($Query1['condicion_pago']);
                    $creador_pedido = $Query1['creador_pedido'];
                    $id_items = $Query1['id_items'];
                    $codigo_producto = $Query1['codigo_producto'];
                    $desc_producto = $Query1['desc_producto'];
                    $cantidad = $Query1['cantidad'];

                    $button1 = '<a href="Pedidos/Editar?id=1" class="btn btn-default btn-sm waves-effect waves-light">Editar</a><br>';
                    // $button1 = '<button class="btn btn-default btn-sm waves-effect waves-light" onclick="return _buscar_pedido('.$id.');">Editar</button><br>';
                    $button2 = '<button class="btn btn-danger btn-sm waves-effect waves-light" onclick="return _eliminar_pedido('.$id.');">Eliminar</button><br>';

                    if(strpos($codigo_producto, '||') !== FALSE)
                    {
                        $codigo_producto_ex = explode('||', $codigo_producto);
                        $codigo_producto_no_0 = array_diff($codigo_producto_ex, array(0));
                        $codigo_producto_f = str_replace("||", "<br>", implode($codigo_producto_no_0, '||'));

                        $desc_producto_producto_ex = explode('||', $desc_producto);
                        $desc_producto_producto_no_0 = array_diff($desc_producto_producto_ex, array(0));
                        $desc_producto_f = str_replace("||", "<br>", implode($desc_producto_producto_no_0, '||'));

                        $cantidad_ex = explode('||', $cantidad);
                        $cantidad_no_0 = array_diff($cantidad_ex, array(0));
                        $cantidad_f = str_replace("||", "<br>", implode($cantidad_no_0, '||'));
                    }else
                    {
                        $codigo_producto_f = $codigo_producto;
                        $desc_producto_f = $desc_producto;
                        $cantidad_f = $cantidad;
                    }

                    $result['id'] = $id;
                    $result['vendedor'] = $name_vendedor;
                    $result['cliente_ruc'] = $cliente_ruc;
                    $result['nombre_comercial'] =  $nombre_comercial;
                    $result['desc_dist'] = $desc_dist;
                    $result['condicion_pago'] = $condicion_pago;
                    $result['codigo_producto'] = '<p style="font-size:0.85em;font-weight:bold;">'.$codigo_producto_f.'</p>';
                    $result['desc_producto'] = '<p style="font-size:0.85em;font-weight:bold;">'.$desc_producto_f.'</p>';
                    $result['cantidad'] = $cantidad_f;
                    $result['acciones'] = $button1.$button2;

                    $data['data'][] = $result;
                }
            }else
            {
                $output = 0;
                $data['data']['error'] = "-";
            }
        }else
        {
            $output = 0;
            $data['data']['error'] = errorPDO($runQuery1);
        }
        return $output.'|~|'.json_encode($data);
    }

    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}