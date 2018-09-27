<?php

Class MarketingModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function list_representantes($periodo, $region)
    {
        $WHERE = null;
        $output = null;

        if($region == 'T')
        {
            $region = null;
        }else
        {
            $region = " drenaje_region = '$region' AND ";
        }

        $WHERE = $region;

        $query1 = $this->db->prepare("SELECT DISTINCT drenaje_repre_cod, 
                                             drenaje_repre_name 
                                        FROM tbl_drenaje_ventas 
                                      WHERE $WHERE drenaje_periodo = :periodo;");
        $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                $output .= '<option value="T">Todos</option>';
                while($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $output .= '<option value="'.$query1_r['drenaje_repre_cod'].'">'.$query1_r['drenaje_repre_name'].'</option>';
                }
            }else
            {
                $output = '<option value="T">sin datos</option>';
            }
        }
        return $output;
    }
    public function list_distribuidoras()
    {

        $output = null;

        $query1 = $this->db->prepare("SELECT DISTINCT
                                            drenaje_dist_cod AS dist_cod,
                                            drenaje_dist_name AS dist_name
                                        FROM
                                            tbl_drenaje_ventas
                                        ORDER BY 1 ASC;");
        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                $output .= '<option value="T">Todos</option>';
                while($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $output .= '<option value="'.$query1_r['dist_cod'].'">'.$query1_r['dist_name'].'</option>';
                }
            }else
            {
                $output = '<option value="T">sin datos</option>';
            }
        }
        return $output;
    }
    public function list_productos($periodo)
    {
        // $WHERE = null;
        $output = null;

        // if($region == 'T')
        // {
        //     $region = null;
        // }else
        // {
        //     $region = " drenaje_region = '$region' AND ";
        // }
        // $WHERE = $region;

        $query1 = $this->db->prepare("SELECT DISTINCT
                                            drenaje_prod_cod AS prod_cod, 
                                            drenaje_prod_name AS prod_name
                                        FROM
                                            tbl_drenaje_ventas
                                        WHERE
                                            drenaje_periodo = :periodo
                                            AND drenaje_prod_name != '_'
                                            AND drenaje_prod_cod != ''
                                            ORDER BY 2 ASC;");
        $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                $output .= '<option value="T">Todos</option>';

                while($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $output .= '<option value="'.$query1_r['prod_cod'].'">'.$query1_r['prod_name'].'</option>';
                }
            }else
            {
                $output = '<option value="T">sin datos</option>';
            }
        }
        return $output;
    }
    public function list_productos_region($periodo, $region)
    {
        // $WHERE = null;
        $output = null;

        if($region == 'T')
        {
            $region = null;
        }else
        {
            $region = " AND drenaje_region = $region ";
        }
        $WHERE = $region;

        $query1 = $this->db->prepare("SELECT DISTINCT
                                            drenaje_prod_cod AS prod_cod, 
                                            drenaje_prod_name AS prod_name
                                        FROM
                                            tbl_drenaje_ventas
                                        WHERE
                                            drenaje_periodo = :periodo
                                            $WHERE
                                            AND drenaje_prod_name != '_'
                                            AND drenaje_prod_cod != ''
                                            ORDER BY 2 ASC;");
        $query1->bindParam(":periodo", $periodo);
        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                while($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $output .= '<option value="'.$query1_r['prod_cod'].'">'.$query1_r['prod_name'].'</option>';
                }
            }else
            {
                $output = '<option value="T">sin datos</option>';
            }
        }
        return $output;
    }
    public function list_productos_x_distribuidoras($periodo, $distribuidora)
    {
        $output = null;
        $dst = null;

        if( $distribuidora == 'T')
        {
            $WHERE = null;
        }else{
            $WHERE = "  AND drenaje_dist_cod = $distribuidora  "; 
        }

        $WHERE = $WHERE;

        $query1 = $this->db->prepare("SELECT DISTINCT
                                            drenaje_prod_cod AS prod_cod, drenaje_prod_name AS prod_name
                                        FROM
                                            tbl_drenaje_ventas
                                        WHERE
                                            drenaje_periodo = 201803
                                                AND drenaje_prod_name != '_'
                                                AND drenaje_prod_cod != ''
                                                $WHERE
                                        ORDER BY 2 ASC;");
        $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                $output .= '<option value="T">Todos</option>';

                while($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $output .= '<option value="'.$query1_r['prod_cod'].'">'.$query1_r['prod_name'].'</option>';
                }
            }else
            {
                $output = '<option value="T">sin datos</option>';
            }
        }
        return $output;
    }
    public function producto_cliente_zona($periodo, $distribuidora, $producto)
    {
        #CORE WHERE
        $WHERE = null;
        #$WHERE = "WHERE personal_tipo = '$puesto'";

        if($distribuidora == 'T')
        {
            $distribuidora = null;
        }else
        {
            $distribuidora = " drenaje_dist_cod = '$distribuidora' AND ";
        }

        if($producto == 'T')
        {
            $producto = null;
        }else
        {
            $producto = " drenaje_prod_cod = '$producto' AND";
        }

        $WHERE = $distribuidora . $producto;

        // $WHERE = trim($WHERE, "AND");
       #ORDER BY 1;
        try
        {
            // $query1 = $this->db->prepare("SELECT 
            //                                     drenaje_region AS region_code,
            //                                     drenaje_zona_g AS zona_g,
            //                                     drenaje_repre_cod AS repre_cod,
            //                                     drenaje_repre_name As repre_name, 
            //                                     drenaje_cliente_ruc AS cliente_ruc,
            //                                     drenaje_cliente_name AS cliente_name,
            //                                     drenaje_zona_desc AS distrito,
            //                                     drenaje_cantidad AS unidad,
            //                                     drenaje_valor as valores,
            //                                     ROUND((drenaje_valor/drenaje_cantidad) * 1.18, 2) AS prom_pvf
            //                                 FROM tbl_drenaje_ventas
            //                                 WHERE $WHERE
            //                                 drenaje_periodo = :periodo;");
            // $query1 = $this->db->prepare("SELECT 
            //                                     region_id AS id,
            //                                     drenaje_region AS region_code,
            //                                     drenaje_zona_g AS zona_g,
            //                                     drenaje_repre_cod AS repre_cod,
            //                                     drenaje_repre_name AS repre_name,
            //                                     drenaje_cliente_ruc AS cliente_ruc,
            //                                     drenaje_cliente_name AS cliente_name,
            //                                     drenaje_zona_desc AS distrito,
            //                                     drenaje_cantidad AS unidad,
            //                                     drenaje_valor AS valores,
            //                                     ROUND((drenaje_valor / drenaje_cantidad) * 1.18,
            //                                             2) AS prom_pvf
            //                                 FROM
            //                                     tbl_drenaje_ventas
            //                                         INNER JOIN
            //                                     tbl_maestro_regiones ON region_codigo2 = drenaje_region
            //                                 WHERE
            //                                     drenaje_periodo = :periodo
            //                                 ORDER BY region_id ASC , region_code DESC , zona_g ASC;");          
            $query1 = $this->db->prepare("SELECT 
                                                region_id AS id,
                                                drenaje_region AS region_code,
                                                drenaje_zona_g AS zona_g,
                                                drenaje_repre_cod AS repre_cod,
                                                drenaje_repre_name AS repre_name,
                                                drenaje_cliente_ruc AS cliente_ruc,
                                                drenaje_cliente_name AS cliente_name,
                                                drenaje_zona_desc AS distrito,
                                                COALESCE(SUM(drenaje_cantidad), 0) AS unidad,
                                                COALESCE(SUM(drenaje_valor), 0) AS valores,
                                                ROUND((drenaje_cantidad / drenaje_valor) * 1.18,
                                                        2) AS prom_pvf
                                            FROM
                                                tbl_drenaje_ventas
                                                    INNER JOIN
                                                tbl_maestro_regiones ON region_codigo2 = drenaje_region
                                            WHERE
                                                $WHERE
                                                drenaje_periodo = :periodo
                                            GROUP BY drenaje_region , drenaje_zona_g , drenaje_cliente_ruc , drenaje_zona_desc
                                            ORDER BY region_id ASC , region_code DESC , zona_g ASC;");                                  
            $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
            if($query1->execute())
            {
                if($query1->rowCount() >0)
                {
                    $output = '<table class="table table-bordered table-condensed table-responsive table-sm" style="width:auto !important;color:black;font-size:0.8em;font-family:Calibri;" id="table-producto_cliente_zona-data">
                                    <thead class="text-white" style="background-color:#588D9C;">
                                        <th class="text-center this_order">COD SUP</th>
                                        <th class="text-center ">SUPERVISOR</th>
                                        <th class="text-center this_order2">COD REPRE</th>
                                        <th class="text-center">REPRESENTANTE</th>
                                        <th class="text-center">RUC</th>
                                        <th class="text-center">CLIENTE NOMBRE</th>
                                        <th class="text-center">DISTRITO</th>
                                        <th class="text-center">UNIDAD</th>
                                        <th class="text-center">VALORES</th>
                                        <th class="text-center">P.Prom. PVF</th>
                                    </thead>
                                    <tfoot style="background-color:#588D9C;font-size:0.95em;" class="text-white">
                                        <td class="text-center"><b>COD SUP</b></td>
                                        <td class="text-center"><b>SUPERVISOR</b></td>
                                        <td class="text-center"><b>COD REPRE</b></td>
                                        <td class="text-center"><b>REPRESENTANTE</b></td>
                                        <td class="text-center"><b>RUC</b></td>
                                        <td class="text-center"><b>CLIENTE NOMBRE</b></td>
                                        <td class="text-center"><b>DISTRITO</b></td>
                                        <td class="text-center"><b>UNIDAD</b></td>
                                        <td class="text-center">-</td>
                                        <td class="text-center"><b>P.Prom. PVF</b></td>
                                    </tfoot>
                                </table>';
                    
                    while ($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                    {
                        $region_code = $query1_r['region_code'];
                        $zona_g = $query1_r['zona_g'];
                        $repre_cod = $query1_r['repre_cod'];
                        $repre_name = $query1_r['repre_name'];
                        $cliente_ruc = $query1_r['cliente_ruc'];
                        $cliente_name = $query1_r['cliente_name'];
                        $distrito = $query1_r['distrito'];
                        $unidad = $query1_r['unidad'];
                        $valores = $query1_r['valores'];
                        $PROM_PVF = $query1_r['prom_pvf'];
                        $supervisor = sup_region_name($region_code);

                        if($region_code == 13){$supervisor = 'CADENAS - LIMA';}

                        $explo_cant = explode(".", $unidad);

                        $total = 0;

                        if($explo_cant[1] == 0){$unidad = $explo_cant[0];}else{$unidad = $unidad;}

                        if($repre_cod == 810){$zona_g = 22;}

                        if($repre_cod == 632){$zona_g = 21;}

                        $result['region_code'] = $region_code;
                        $result['supervisor'] = $supervisor;
                        $result['zona_g'] = $zona_g;
                        $result['repre_name'] = $repre_name;
                        $result['cliente_ruc'] = $cliente_ruc;
                        $result['cliente_name'] = $cliente_name;
                        $result['distrito'] = $distrito;
                        $result['unidad'] = $unidad;
                        $result['valores'] = $valores;
                        $result['prom_pvf'] = number_format($PROM_PVF, 2, '.', ',').'%';
                                
                        $data['data'][] = $result;
                    }
                }else
                {
                    $output = '<b>No hay datos</b>';
                    $data['data']['error'] = '';
                }
            }else
            {
                $output = errorPDO($query1);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output.'||'. json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function producto_region_periodo($periodo, $region, $producto)
    {
        #CORE WHERE
        $WHERE = null;
        #$WHERE = "WHERE personal_tipo = '$puesto'";

        if($region == 'T')
        {
            $region = null;
        }else
        {
            $region = " AND drenaje_region = $region ";
        }

        // if($producto == 'T')
        // {
        //     $producto = null;
        // }else
        // {
        //     $producto = " drenaje_prod_cod = '$producto' AND";
        // }

        // $WHERE = $distribuidora . $producto;
        $WHERE = $region;

        $WHERE = trim($WHERE, "AND");
       #ORDER BY 1;
        try
        {
                
            $query1 = $this->db->prepare("SELECT 
                                                region_id AS id,
                                                drenaje_region AS region_code,
                                                drenaje_zona_g AS zona_g,
                                                drenaje_repre_cod AS repre_cod,
                                                drenaje_repre_name AS repre_name,
                                                drenaje_cliente_ruc AS cliente_ruc,
                                                drenaje_cliente_name AS cliente_name,
                                                COALESCE(SUM(drenaje_cantidad), 0) AS unidad,
                                                COALESCE(SUM(drenaje_valor), 0) AS valores
                                            FROM
                                                tbl_drenaje_ventas
                                                    INNER JOIN
                                                tbl_maestro_regiones ON region_codigo2 = drenaje_region
                                            WHERE
                                                    drenaje_periodo = :periodo
                                                    AND drenaje_prod_cod = :producto
                                                    $WHERE
                                            GROUP BY drenaje_region , drenaje_zona_g , drenaje_cliente_ruc , drenaje_zona_desc
                                            ORDER BY region_id ASC , region_code DESC , zona_g ASC;");                                  
            $query1->bindParam(":periodo", $periodo);
            $query1->bindParam(":producto", $producto);
            if($query1->execute())
            {
                if($query1->rowCount() >0)
                {
                    $output = '<table class="table table-bordered table-condensed table-responsive table-sm"
                                style="width:auto !important;color:black;font-size:0.8em;font-family:Calibri;" id="table_producto_region_periodo_data">
                                    <thead class="text-white" style="background-color:#588D9C;">
                                        <th class="text-center this_order">COD SUP</th>
                                        <th class="text-center ">SUPERVISOR</th>
                                        <th class="text-center this_order2">Zona</th>
                                        <th class="text-center">REPRESENTANTE</th>
                                        <th class="text-center">RUC</th>
                                        <th class="text-center">CLIENTE NOMBRE</th>
                                        
                                        <th class="text-center">UNIDAD</th>
                                        <th class="text-center">VALORES</th>
                                        <th class="text-center">Precio Uni</th>
                                    </thead>
                                    <tfoot class="text-white" style="background-color:#588D9C;">
                                        <td class="text-center">COD SUP</td>
                                        <td class="text-center ">SUPERVISOR</td>
                                        <td class="text-center">Zona</td>
                                        <td class="text-center">REPRESENTANTE</td>
                                        <td class="text-center">RUC</td>
                                        <td class="text-center">CLIENTE NOMBRE</td>
                                        
                                        <td class="text-center">UNIDAD</td>
                                        <td class="text-center">VALORES</td>
                                        <td class="text-center">Precio Uni</td>
                                    </tfoot>
                                </table>';
                    
                    while ($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                    {
                        $region_code = $query1_r['region_code'];
                        $zona_g = $query1_r['zona_g'];
                        $repre_cod = $query1_r['repre_cod'];
                        $repre_name = $query1_r['repre_name'];
                        $cliente_ruc = $query1_r['cliente_ruc'];
                        $cliente_name = $query1_r['cliente_name'];
                        $distrito = "-";
                        $unidad = $query1_r['unidad'];
                        $valores = $query1_r['valores'];
                        if($valores == 0 || $unidad == 0)
                        {
                            $precio_uni = 0;
                        }else
                        {
                            $precio_uni = round(($valores/$unidad), 2);
                        }
                        
                        $supervisor = sup_region_name($region_code);

                        if($region_code == 13){$supervisor = 'CADENAS - LIMA';}

                        $explo_cant = explode(".", $unidad);

                        $total = 0;

                        if($explo_cant[1] == 0){$unidad = $explo_cant[0];}else{$unidad = $unidad;}

                        if($repre_cod == 810){$zona_g = 22;}

                        if($repre_cod == 632){$zona_g = 21;}

                        $result['region_code_sup'] = $region_code;
                        $result['supervisor'] = $supervisor;
                        $result['zona_g'] = $zona_g;
                        $result['repre_name'] = $repre_name;
                        $result['cliente_ruc'] = $cliente_ruc;
                        $result['cliente_name'] = $cliente_name;
                        // $result['distrito'] = $distrito;
                        $result['unidades'] = $unidad;
                        $result['valores'] = $valores;
                        $result['precio_uni'] = $precio_uni;
                                
                        $data['data'][] = $result;
                    }
                }else
                {
                    $output = '<b>No hay datos</b>';
                    $data['data']['error'] = '';
                }
            }else
            {
                $output = errorPDO($query1);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }
        return $output.'||'. json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function vendedor_cliente($periodo, $region, $vendedor)
    {
        #CORE WHERE
        $WHERE = null;
        #$WHERE = "WHERE personal_tipo = '$puesto'";
        if($region == 'T')
        {
            $region = null;
        }else
        {
            $region = " drenaje_region = '$region' AND ";
        }

        if($vendedor == 'T')
        {
            $vendedor = null;
        }else
        {
            $vendedor = " drenaje_repre_cod = '$vendedor' AND";
        }

        $WHERE = $region . $vendedor;

        // $WHERE = trim($WHERE, "AND");
       
        try
        {
            $query1 = $this->db->prepare("SELECT 
                                                drenaje_repre_cod AS vendedor_cod,
                                                drenaje_cliente_ruc AS cliente_ruc,
                                                drenaje_cliente_name AS cliente_name,
                                                drenaje_dist_name AS distribuidora_name,
                                                drenaje_prod_name AS prod_name,
                                                drenaje_cantidad AS cantidad,
                                                drenaje_valor AS valor,
                                                drenaje_region AS region
                                            FROM
                                                tbl_drenaje_ventas
                                            WHERE
                                            $WHERE
                                                drenaje_periodo = :periodo
                                                AND drenaje_prod_cod != '';");
            $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
            if($query1->execute())
            {
                if($query1->rowCount() > 0)
                {
                    $output =   '<table class="table table-bordered table-condensed table-responsive table-sm" cellspacing="0" style="color:black;font-size:0.8em;font-family:Calibri;" id="table-vendedor-cliente-data">
                                    <thead class="text-white" style="background-color:#588D9C;">
                                        <th class="text-center">RUC</th>
                                        <th class="text-center">DESCRIPCION</th>
                                        <th class="text-center">DISTRIBUIDORA</th>
                                        <th class="text-center">PRODUCTO DESCRIPCION</th>
                                        <th class="text-center">UNIDAD</th>
                                        <th class="text-center this_order">VALOR</th>
                                        <th class="text-center">P.Prom. PVF</th>
                                    </thead>
                                    <tfoot style="background-color:#588D9C;font-size:0.95em;">
                                            <td class="text-white"><b>RUC</b></td>
                                            <td class="text-white"><b>DESCRIPCION</b></td>
                                            <td class="text-white"><b>DISTRIBUIDORA</b></td>
                                            <td class="text-white"><b>PRODUCTO DESCRIPCION</b></td>
                                            <td class="text-white "><b>Cantidad</b></td>
                                            <td class="text-white" id="total">-</td>
                                            <td class="text-white"><b>P.Prom. PVF</b></td>
                                    </tfoot>
                                </table>';
                    
                    while ($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                    {
                        $explo_cant = explode(".", $query1_r['cantidad']);

                        $total = 0;

                        if($explo_cant[1] == 0)
                        {
                            $cantidad_ = $explo_cant[0];
                        }else
                        {
                            $cantidad_ = $query1_r['cantidad'];
                        }

                        $total = 0;

                        if($cantidad_ == 0)
                        {
                            $total = 0;
                        }else
                        {
                            @$total = round((($cantidad_/$query1_r['valor']) * 1.18),2);
                        }
                        

                        $cliente_ruc = $query1_r['cliente_ruc'];
                        $cliente_name = $query1_r['cliente_name'];
                        $distribuidora_name = $query1_r['distribuidora_name'];
                        $prod_name = $query1_r['prod_name'];
                        $cantidad_ = $cantidad_;
                        $valor = $query1_r['valor'];
                        $valor_total = number_format($total, 2, '.', ',');

                        $result['cliente_ruc'] = $cliente_ruc;
                        $result['cliente_name'] = $cliente_name;
                        $result['distribuidora_name'] = $distribuidora_name;
                        $result['prod_name'] = $prod_name;
                        $result['cantidad'] = $cantidad_;
                        $result['valor'] = $valor;
                        $result['valor_total'] = $valor_total;
                    
                        $data['data'][] = array_map("utf8_encode", $result);
                    }
                }else
                {
                    $output = '<b>No hay datos</b>';
                    $data['data']['error'] = '';
                }
            }else
            {
                $output = errorPDO($query1);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }

        return $output.'||'.json_encode($data);
    }
    public function resumen_ventas($periodo)
    {
        $session_cod_ = session_codigo_vendedor();
        $session_portafolio_ = session_portafolio();
        $session_root_ = session_usuario_cargo();
        $_where_cod_vendedor = null;
        // $Region_ = $region;

        
        ###NSC
        if($session_cod_ == 40)
        {
            $session_cod_ = 771;
        }else if($session_cod_ == 758)
        {
            $session_cod_ = 771;
        }else if($session_cod_ == 771)
        {
            $session_cod_ = 771;
        }else
        {
            $session_cod_ = $session_cod_;
        }
        ###NSC
        if($session_root_ == 4)
        {
            $_where_cod_vendedor = " AND rv_vendedor_codigo = $session_cod_";
        }else
        {
            $_where_cod_vendedor = '';
        }

        if($session_portafolio_ != 'T')
        {
            if($session_portafolio_ != $Portafolio)
            {
                return 1;
            }
        }

        $WHERE = null;
        $output = null;
        $query0 = $this->db->prepare("SELECT region_codigo2 
                                        FROM tbl_maestro_regiones 
                                        WHERE region_codigo2 != 7 ORDER BY region_id ASC");
        
        $sumatotal_cuotas = sum_total_cuotas_regiones($periodo);
        $sumatotal_ventas = sum_total_regiones($periodo);
        $porcentaje_total = round(procentaje_calcular($sumatotal_cuotas, $sumatotal_ventas), 2);

        $output = '<div class="col-sm-12">
                    <div class="card-box widget-inline">
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="widget-inline-box text-center">
                                    <h3><i class="text-custom">S/</i> <b data-plugin="counterup">'.number_format($sumatotal_cuotas, 2, '.', ',').'</b></h3>
                                    <h4 class="text-muted font-18">Total cuotas</h4>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="widget-inline-box text-center">
                                    <h3><i class="text-custom">S/</i> <b data-plugin="counterup">'.number_format($sumatotal_ventas, 2, '.', ',').'</b></h3>
                                    <h4 class="text-muted font-18">Total ventas</h4>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="widget-inline-box text-center">
                                    <h3><b data-plugin="counterup">'.$porcentaje_total.'</b><i class="text-primary">%</i></h3>
                                    <h4 class="text-muted font-18">Porcentaje</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><hr>';
        if($query0->execute())
        {
            if($query0->rowCount() > 0)
            {
                $contenedor = 1;

                $output .= '<div id="accordion">';

                while($rquery0 = $query0->fetch(PDO::FETCH_ASSOC))
                {
                    $region = $rquery0['region_codigo2'];

                    $query1 = $this->db->prepare("SELECT 
                                                        rv_zona AS zona,
                                                        rv_vendedor_codigo AS repre_cod,
                                                        rv_vendedor_nombre AS repre_name,
                                                        rv_distribuidoras_valores AS valor_dist,
                                                        rv_cuota AS cuota,
                                                        rv_valor AS vendido,
                                                        rv_cliente_portafolio AS cliente_portafolio,
                                                        rv_cliente_ventas AS cliente_venta
                                                    FROM
                                                        reporte_drenaje_ventas
                                                    WHERE
                                                        rv_periodo = :periodo 
                                                        AND rv_region_2 = :region ORDER BY 1 ASC");
                                                        
                    $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
                    $query1->bindParam(":region", $region, PDO::PARAM_INT);
                    if($query1->execute())
                    {
                        if($query1->rowCount() > 0)
                        {
                            #CONTENEDOR
                        
                            $output .= '<div class="card">
                                        <div class="card-header" id="headingOne" style="background-color:'.array_colores($contenedor).';">
                                        <div style="padding:1em;position:absolute;" >
                                            <a href="#contenido'.$contenedor.'"  data-toggle="collapse" aria-expanded="false" aria-controls="collapseOne" class="btn btn-primary btn-sm waves-effect waves-light pull-left" >
                                            <span class="fa fa-plus"></span></a>
                                        </div>
                                        <h7 class="m-0 pull-left" style="padding-left:4em;" >
                                                <a  class="text-white collapsed text-left">
                                                    <p class="">'.sup_region_name_2($region).' </p>
                                                    <i>
                                                        <b class="pull-left h6"> Cuotas: S/ '.number_format(sum_cuotas_x_regiones($region, $periodo), 2, '.', ',').' - Ventas: S/ '.number_format(sum_x_regiones($region, $periodo), 2, '.', ',').' - Avance: '.round(procentaje_calcular(sum_cuotas_x_regiones($region, $periodo), sum_x_regiones($region, $periodo)), 2).'%</b>
                                                    </i>
                                                </a>
                                            </h7>
                                        </div>
                            
                                        <div id="contenido'.$contenedor.'" class="collapse" aria-labelledby="headingOne" data-parent="#accordion" style="">
                                            <div class="card-body">
                                        <table  class="table table-bordered table-condensed table-striped table-hover table-sm table-responsive table-reflow" style="width:auto;font-size:0.82em;font-family:verdana;">
                                            <thead onclick="test('.$contenedor.');" class="text-white" style="background-color:#476D7C;cursor:pointer;">
                                                <th class="text-center">Zona</th>
                                                <th class="text-center">Representante</th>
                                                <th class="text-center" >Cuota</th>
                                                <th class="text-center">Ventas</th>
                                                <th class="text-center order_rg">Porcentaje</th>
                                                <th class="text-center">Clientes</th>
                                                <th class="text-center">Cli Atend</th>
                                                <th class="text-center">Client %</th>';

                            $query2 = $this->db->prepare("SELECT 
                                                                rv_distribuidoras_nombres AS distribuidoras
                                                            FROM
                                                                reporte_drenaje_ventas
                                                            WHERE
                                                                rv_periodo = :periodo AND rv_region_2 = :region;");
                            $query2->bindParam(':region', $region, PDO::PARAM_INT);
                            $query2->bindParam(':periodo', $periodo, PDO::PARAM_INT);

                            if($query2->execute() && $query2->rowCount() > 0)
                            {
                                $rquery2 = $query2->fetch(PDO::FETCH_ASSOC);

                                $explode_header_dis = explode(",",$rquery2['distribuidoras']);

                                for ($i = 0; $i <= count($explode_header_dis)-1; $i++)
                                { 
                                    $name_distribuidora = _short_name_($explode_header_dis[$i]);
                                    $output .= '<th class="text-center export-col-x-regional_ desktop tablet">'.$name_distribuidora.'</th>';  
                                }
                            }
                    
                            /*$output .= '<th class="text-center export-col-x-regional_ desktop tablet">Ventas</th>
                                        <th class="text-center export-col-x-regional_ desktop tablet fablet phone order_rg">Porcentaje</th>
                                        <th class="text-center export-col-x-regional_ desktop tablet">80%</th>
                                        <th class="text-center export-col-x-regional_ desktop tablet">90%</th>
                                        <th class="text-center export-col-x-regional_ desktop tablet">100%</th>
                                        <th class="text-center desktop tablet" style="border-radius:0 8px 0 0;">Detalle</th>
                                    </thead>
                                    <tbody class="text-font-black" style="color:black;">';*/
                            $output .= '</thead>
                                    <tbody class="text-font-black" style="color:black;" id="body_'.$contenedor.'">';

                            $sumcuotas = 0;
                            $sumventas = 0;

                            while($rquery1 = $query1->fetch(PDO::FETCH_ASSOC))
                            {
                                $total = 0;

                                $zona = $rquery1['zona'];
                                $repre_cod = $rquery1['repre_cod'];
                                $repre_name = $rquery1['repre_name'];
                                $valor_dist = explode(",", $rquery1['valor_dist']);
                                $cuota = $rquery1['cuota'];
                                $vendido = $rquery1['vendido'];
                                $cliente_portafolio = $rquery1['cliente_portafolio'];
                                $cliente_venta = $rquery1['cliente_venta'];

                                $sumcuotas += $cuota;
                                $sumventas += $vendido;

                                if($repre_cod == 810)
                                {
                                    $zona = 22;
                                }elseif ($repre_cod == 632)
                                {
                                    $zona = 21;
                                }else
                                {
                                    $zona = $zona;
                                }

                                $porcentaje = @round((($vendido * 100)/ $cuota), 2).'%';
                                $valortotal = @number_format($vendido, 2, '.', ',');
                                $porcentaje_cliente = "0 %";

                                $output .= '<tr>
                                                <td class=" col-md-offset-5 text-center">'.$zona.'</td>
                                                <td class="text-left">'.$repre_name.'</td>
                                                <td class="text-center">'.number_format($cuota, 2, '.', ',').'</td>
                                                <td>'.number_format($vendido, 2, '.', ',').'</td>
                                                <td class="text-center">'.$porcentaje.'</td>
                                                <td class="text-center">'.$cliente_portafolio.'</td>
                                                <td class="text-center">'.$cliente_venta.'</td>
                                                <td class="text-center">'.$porcentaje_cliente.'</td>';
                                
                                    for ($a = 0; $a <= count($valor_dist)-1; $a++)
                                    { 
                                        $output .= '<td class="text-center">'.@number_format($valor_dist[$a], 2, '.', ',').'</td>';

                                        @$total += $valor_dist[$a];
                                    }
                                    
                                    
                                    // $_80 = @number_format((($cuota * 0.8) - ($total)), 2, '.', ',');
                                    // $_90 = @number_format((($cuota * 0.9) - ($total)), 2, '.', ',');
                                    // $_100 =  @number_format((($cuota * 1) - ($total)), 2, '.', ',');                      

                                    // if($_80 < 0)
                                    // {
                                    //     $_80 = "-";
                                    // }   
                                    // if($_90 < 0)
                                    // {
                                    //     $_90 = "-";
                                    // }   
                                    // if($_100 < 0)
                                    // {
                                    //     $_100 = "-";
                                    // }  

                                

                                $output .= '</tr>';
                            }

                            $output .= '</tbody>';
                            $output .= '<tfoot class="text-white" style="background-color:#476D7C;">
                                                <td class="text-center">Zona</td>
                                                <td class="text-center">Representante</td>
                                                <td class="text-center"><b>'.@number_format($sumcuotas, 2, '.', ',').'</b></td>
                                                <td class="text-center"><b>'.@number_format($sumventas, 2, '.', ',').'</b></td>
                                                <td class="text-center"><b>'.@round(procentaje_calcular($sumcuotas, $sumventas), 2).'%</b></td>
                                                <td class="text-center">Clientes</td>
                                                <td class="text-center">Cant cli</td>
                                                <td class="text-center">Porcentaje</td>';

                            $tfoot = $this->db->prepare("SELECT 
                                                                rv_distribuidoras_nombres AS distribuidoras
                                                            FROM
                                                                reporte_drenaje_ventas
                                                            WHERE
                                                                rv_periodo = :periodo AND rv_region_2 = :region;");
                            $tfoot->bindParam(':region', $region, PDO::PARAM_INT);
                            $tfoot->bindParam(':periodo', $periodo, PDO::PARAM_INT);

                            if($tfoot->execute() && $tfoot->rowCount() > 0)
                            {
                                $rtfoot = $tfoot->fetch(PDO::FETCH_ASSOC);

                                $explode_header_dis = explode(",",$rtfoot['distribuidoras']);

                                for ($i = 0; $i <= count($explode_header_dis)-1; $i++)
                                { 
                                    $name_distribuidora = _short_name_($explode_header_dis[$i]);

                                    $output .= '<td class="text-center">'.$name_distribuidora.'</td>';  
                                }
                            }
                    
                            $output .= '</tfoot></table>
                                            </div>
                                        </div>
                                        </div>';
                        }else
                        {
                            $output .= "";
                        }
                    }else
                    {
                        $output = errorPDO($query1);
                    }
                    $contenedor++;

                    $output .= '</div>';
                }
            }else
            {
                $output = '0';
            }
        }else
        {
            $output = errorPDO($query0);
        }

        
        return $output;
    }
    public function cuota_x_producto($periodo, $producto)
    {
        #CORE WHERE
        $prod_code = $producto;
        $WHERE = null;
        #$WHERE = "WHERE personal_tipo = '$puesto'";

        if($producto == 'T')
        {
            $producto = null;
        }else
        {
            $producto = " drenaje_prod_cod = '$producto' AND";
        }

        $WHERE = $producto;
        try
        {
            $query1 = $this->db->prepare("SELECT 
                                                drenaje_region AS region_cod,
                                                region_supervisor AS region_sup,
                                                drenaje_zona_g AS repre_zona,
                                                drenaje_repre_cod AS repre_cod,
                                                drenaje_repre_name AS repre_name,
                                                COALESCE(SUM(drenaje_cantidad), 0) AS cantidad_vendida,
                                                COALESCE(SUM(drenaje_valor), 0) AS valor_vendida
                                            FROM
                                                tbl_drenaje_ventas
                                                    INNER JOIN
                                                tbl_maestro_regiones ON region_codigo2 = drenaje_region
                                            WHERE $WHERE
                                                drenaje_periodo = :periodo
                                            GROUP BY drenaje_repre_cod , region_supervisor
                                            ORDER BY region_id ASC, region_supervisor ASC, drenaje_zona_g ASC");

            $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
            if($query1->execute())
            {
                if($query1->rowCount() >0)
                {
                    $output = '<table class="table table-bordered table-condensed table-responsive table-sm" cellspacing="0" 
                                style="width:auto !important;color:black;font-size:0.8em;font-family:Calibri;" id="table-cuota_x_producto-data">
                                    <thead class="text-white" style="background-color:#36626A;">
                                        <th class="text-center this_order1">COD SUP</th>
                                        <th class="text-center this_order2">SUPERVISOR</th>
                                        <th class="text-center">COD</th>
                                        <th class="text-center" style="width:21% !important;">REPRESENTANTE</th>
                                        <th class="text-center">CUOTA UNI.</th>
                                        <th class="text-center">VENTA UNI.</th>
                                        <th class="text-center">CUOTA VALOR</th>
                                        <th class="text-center">VENTA</th>
                                        <th class="text-center">ALCANCE</th>
                                    </thead>
                                    <tfoot style="background-color:#588D9C;font-size:0.95em;" class="text-white">
                                        <th class="text-center"><b>COD SUP</b></th>
                                        <th class="text-center"><b>SUPERVISOR</b></th>
                                        <th class="text-center"><b>COD</b></th>
                                        <th class="text-center"><b>REPRESENTANTE</b></th>
                                        <th class="text-center"><b>CUOTA UNI.</b></th>
                                        <th class="text-center"><b>VENTA UNI.</b></th>
                                        <th class="text-center"><b>CUOTA VALOR</b></th>
                                        <th class="text-center"><b>VENTA</b></th>
                                        <th class="text-center"><b>ALCANCE</b></th>
                                    </tfoot>
                                </table>';

                    $region_cod_rep = 0;
                    $region_cod_last = 0;
                    

                    while ($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                    {
                        $region_cod = $query1_r['region_cod'];
                        $region_sup = $query1_r['region_sup'];
                        $repre_cod = $query1_r['repre_cod'];
                        $repre_zona = $query1_r['repre_zona'];
                        if($repre_cod == 810)
                        {
                            $repre_zona = 22;
                        }else if($repre_cod == 632)
                        {
                            $repre_zona = 21;
                        }
                        else
                        {
                            $repre_zona = $repre_zona;
                        }
                        if($region_cod == 3)
                        {
                            $region_sup = "VACANTE NSC";
                        }
                       
                        $repre_name = $query1_r['repre_name'];

                        $data_cuota_prod = explode("||", _search_cuota_data($prod_code, $region_cod, $repre_zona, $periodo));
                        
                        $cuota_uni = $data_cuota_prod[0];#0;

                        $cantidad_vendida = (int)$query1_r['cantidad_vendida'];

                        $venta_uni = (int)$data_cuota_prod[1];#0;                      
                        $valor_vendida = $query1_r['valor_vendida'];
                        $alcance = @round((($valor_vendida*100)/$data_cuota_prod[1]), 2);

                        if(is_infinite($alcance))
                        {
                            $alcance = "0.00%";
                        }else
                        {
                            $alcance = $alcance."%";
                        }                   
                        
                        $result['region_cod'] = $region_cod;
                        $result['region_sup'] = $region_sup;
                        $result['repre_zona'] = $repre_zona;
                        $result['repre_name'] = $repre_name;
                        $result['cuota_uni'] = $cuota_uni;
                        $result['cantidad_vendida'] = $cantidad_vendida;
                        $result['venta_uni'] = $venta_uni;
                        $result['valor_vendida'] = $valor_vendida;
                        $result['alcance'] = $alcance;
                                
                        $data['data'][] = array_map("utf8_encode", $result);

                    }
                }else
                {
                    $output = '<b>No hay datos</b>';
                    $data['data']['error'] = '';
                }
            }else
            {
                $output = errorPDO($query1);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }

        return $output.'||'. json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function prod_zona($periodo)
    {
        try
        {
            $query1 = $this->db->prepare("SELECT 
                                            drenaje_region AS reg_sup,
                                            drenaje_prod_cod AS prod_cod,
                                            drenaje_prod_name AS prod_name,
                                            COALESCE(SUM(drenaje_cantidad), 0) AS venta_uni,
                                            COALESCE(SUM(drenaje_valor), 0) AS venta_valor
                                        FROM
                                            tbl_drenaje_ventas
                                        WHERE
                                            drenaje_periodo = :periodo AND
                                            drenaje_prod_cod != ''
                                        GROUP BY drenaje_region , drenaje_prod_cod
                                        ORDER BY drenaje_region ASC , venta_uni DESC;");

            $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
            if($query1->execute())
            {
                if($query1->rowCount() >0)
                {// style="width:21% !important;"
                    $output = '<table class="table table-bordered table-condensed table-responsive table-sm" cellspacing="0" 
                                style="width:auto;color:black;font-size:0.95em;font-family:Calibri;" id="table-prod_zona-data">
                                    <thead class="text-white" style="background-color:#36626A;">
                                        <th class="text-center">Grupo</th>
                                        <th class="text-center">Supervisor</th>
                                        <th class="text-center">Producto</th>
                                        <th class="text-center">Cuot Uni</th>
                                        <th class="text-center">Vent Uni</th>
                                        <th class="text-center">P.V.F.Prom</th>
                                        <th class="text-center">Saldo Uni</th>
                                        <td class="text-center">valor</td>
                                    </thead>
                                <tfoot style="background-color:#588D9C;font-size:0.95em;" class="text-white">
                                    <td class="text-center">Grupo</td>
                                    <th class="text-center">Supervisor</th>
                                    <td class="text-center">Producto</td>
                                    <td class="text-center">Cuot Uni</td>
                                    <td class="text-center">Vent Uni</td>
                                    <td class="text-center">P.V.F.Prom</td>
                                    <td class="text-center">Saldo Uni</td>
                                    <td class="text-center">valor</td>
                                </tfoot>
                                </table>';
                                
                    while ($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                    {               
                        $reg_sup = $query1_r['reg_sup'];
                        $prod_cod = $query1_r['prod_cod'];
                        $prod_name = $query1_r['prod_name'];
                        $cuota_uni = _search_cuota_reg($prod_cod, $reg_sup, $periodo);#0;
                        $venta_uni = (int)$query1_r['venta_uni'];
                        $venta_valor = (int)$query1_r['venta_valor'];
                        
                        
                        $reg_sup_name = sup_region_name($reg_sup);
                        
                        if($venta_uni == 0)
                        {
                            $pvf_prom = 0;
                        }else
                        {
                            $divi = @($venta_uni/$venta_valor);
                            $pvf_prom = round(($divi * 1.18), 2);
                        }

                        if(is_infinite($pvf_prom))
                        {
                            $pvf_prom = 0;
                        }

                        $saldo = ($cuota_uni - $venta_uni)*(-1); 

                        // if($saldo < 0)
                        // {
                        //     $saldo = '<b style="color:red;">'.$saldo.'</b>';
                        // }else
                        // {
                        //     $saldo = '<b style="color:black;">'.$saldo.'</b>';
                        // }
                        
                        $result['reg_sup'] = $reg_sup;
                        $result['reg_sup_name'] = $reg_sup_name;
                        $result['prod_name'] = $prod_name;
                        $result['cuota_uni'] = $cuota_uni;
                        $result['venta_uni'] = $venta_uni;
                        $result['pvf_prom'] = $pvf_prom;
                        $result['saldo'] = $saldo;
                        $result['venta_valor'] = $venta_valor;
                                
                        $data['data'][] = array_map("utf8_encode", $result);

                    }
                }else
                {
                    $output = '<b>No hay datos</b>';
                    $data['data']['error'] = '';
                }
            }else
            {
                $output = errorPDO($query1);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }

        return $output.'||'. json_encode($data);
    }
    public function prod_zona_supervisor($periodo, $region)
    {
        try
        {
            $query1 = $this->db->prepare("SELECT 
                                                drenaje_region AS reg_sup,
                                                drenaje_zona_g as zona,
                                                drenaje_repre_cod AS repre_cod,
                                                drenaje_repre_name AS repre_name,
                                                drenaje_prod_cod AS prod_cod,
                                                drenaje_prod_name AS prod_name,
                                                COALESCE(SUM(drenaje_cantidad), 0) AS venta_uni,
                                                COALESCE(SUM(drenaje_valor), 0) AS venta_valor
                                            FROM
                                                tbl_drenaje_ventas
                                            WHERE
                                                drenaje_periodo = :periodo
                                                AND drenaje_region = :region
                                                AND drenaje_prod_cod != ''
                                            GROUP BY drenaje_repre_cod ,drenaje_prod_cod
                                            ORDER BY drenaje_repre_cod ASC , prod_name ASC;");

            $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
            $query1->bindParam(":region", $region, PDO::PARAM_INT);
            if($query1->execute())
            {
                if($query1->rowCount() > 0)
                {// style="width:21% !important;"
                    $output = '<br><hr><br>
                                <label id="reg_sup" class="h5 pull-left" style="color:black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.sup_region_name($region).'</label>
                                <br><hr>
                                <table class="table table-bordered table-condensed table-responsive table-sm" cellspacing="0" 
                                style="width:auto;color:black;font-size:0.95em;font-family:Calibri;" id="table-prod_zona_supervisor-data">
                                    <thead class="text-white" style="background-color:#36626A;">
                                        <th class="text-center xdddd">Zona</th>
                                        <th class="text-center xdddd">Cod</th>
                                        <th class="text-center xdddd">Representante</th>
                                        <th class="text-center xdddd">Producto</th>
                                        <th class="text-center xdddd">Cuot Uni</th>
                                        <th class="text-center xdddd">Vent Uni</th>
                                        <th class="text-center xdddd">P.V.F.Prom</th>
                                        <th class="text-center xdddd">Saldo Uni</th>
                                        <th class="text-center xdddd">Valor</th>
                                    </thead>
                                <tfoot style="background-color:#588D9C;font-size:0.95em;" class="text-white">
                                    <td class="text-center">Zona</td>
                                    <td class="text-center">Cod</td>
                                    <th class="text-center">Representante</th>
                                    <td class="text-center">Producto</td>
                                    <td class="text-center">Cuot Uni</td>
                                    <td class="text-center">Vent Uni</td>
                                    <td class="text-center">P.V.F.Prom</td>
                                    <td class="text-center">Saldo Uni</td>
                                    <td class="text-center">Valor</td>
                                </tfoot>
                                </table>';
                                
                    while ($query1_r = $query1->fetch(PDO::FETCH_ASSOC))
                    {               
                        $reg_sup = $query1_r['reg_sup'];
                        $zona = $query1_r['zona'];
                        $repre_cod = $query1_r['repre_cod'];
                        $repre_name = $query1_r['repre_name'];
                        $prod_cod = $query1_r['prod_cod'];
                        $prod_name = $query1_r['prod_name'];
                        
                        $venta_uni = (int)$query1_r['venta_uni'];
                        $venta_valor = (int)$query1_r['venta_valor'];
                        
                                                
                        // $result['reg_sup'] = $reg_sup;
                        if($repre_cod == 810)
                        {
                            $zona = 21;
                        }
                        if($repre_cod == 632)
                        {
                            $zona = 22;
                        }
                        if($repre_cod == 218)
                        {
                            $zona = 9;
                        }

                        $cuotas_data_explo = explode('||', _search_cuota_data($prod_cod, $region, $zona, $periodo));
                        $cuota_uni = (int)$cuotas_data_explo[0];
                       
                        if($venta_uni == 0)
                        {
                            $pvf_prom = 0;
                        }else
                        {
                            $divi = @($venta_uni/$venta_valor);
                            $pvf_prom = round(($divi * 1.18), 2);
                        }

                        if(is_infinite($pvf_prom))
                        {
                            $pvf_prom = 0;
                        }

                        $saldo = ($cuota_uni - $venta_uni)*(-1); 

                        $result['zona'] = $zona;
                        $result['repre_cod'] = $repre_cod;
                        $result['repre_name'] = $repre_name;
                        $result['prod_name'] = $prod_name;
                        $result['cuota_uni'] = $cuota_uni;
                        $result['venta_uni'] = $venta_uni;
                        $result['pvf_prom'] = $pvf_prom;
                        $result['saldo'] = $saldo;
                        $result['venta_valor'] = $venta_valor;
                                
                        $data['data'][] = $result;
                    }
                }else
                {
                    $output = '<b>No hay datos</b>';
                    $data['data']['error'] = 'VACIO';
                }
            }else
            {
                $output = errorPDO($query1);
                $data['data']['error'] = errorPDO($query1);
            }
        }catch(PDOException $e)
        {
            $output = $e->getMessage();
        }

        return $output.'||'. json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}