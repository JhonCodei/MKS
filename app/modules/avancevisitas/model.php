<?php

Class AvancevisitasModel
{
    public function __construct()
    {
        $this->db = Database::Connection();
    }
    public function listar_avance_visitas($periodo, $tipo, $region, $vendedor)
    {
        $WHERE = null;
        $output = null;        
        $region_visita = $region;

        $region_venta = _reg_visita_ventas_($region);

        if($vendedor == 999)
        {
            $vendedor = 117;
        }

        if( $tipo == 5)
        {
            $WHERE = " AND rv_vendedor_codigo = $vendedor AND rv_region_2 = $region ";
        }
        if($region != 'T' && $tipo != 5)
        {
            $WHERE = " AND rv_region_2 = $region ";

            if($region_visita == 8)
            {
              $supervisor =  "LUIS LOAYZA - LIMA "  ;
            }else if($region_visita == 28)
            {
              $supervisor =  "LUIS LOAYZA - NORTE SUR CHICO "  ;
            }else
            {
                $supervisor = sup_region_name_2($region_venta);
            }

            $output .='<h7 class="m-0 pull-left" style="padding-left:2em;" >
                                <a  class="collapsed text-left">
                                    <p class="">'.$supervisor.' - VISITA </p>
                                    <i>
                                        <b class="pull-left h6"> Cuotas: S/ '.number_format(sum_cuotas_x_regiones($region_visita, $periodo), 2, '.', ',').' 
                                        <br> Ventas: S/ '.number_format(sum_x_regiones($region_visita, $periodo), 2, '.', ',').' 
                                        <br> Avance: '.round(procentaje_calcular(sum_cuotas_x_regiones($region_visita, $periodo), sum_x_regiones($region_visita, $periodo)), 2).'%</b>
                                    </i>
                                </a>
                            </h7><br><br><br><br><br>';

                            if($region_venta == 10)
                            {
                                $output .= '<button class="btn btn-inverse waves-effect waves-light" onclick="return reporte();">
                                            <span class="fa fa-search"></span>
                                        &nbsp;Venta por zona</button><br><br>';
                            }
        }
        if($vendedor == 756 && $tipo == 5)
        {
            $WHERE = " AND rv_vendedor_codigo = $vendedor AND rv_region_2 = $region ";
        }

        $query1 = $this->db->prepare("SELECT 
                                            rv_zona AS zona,
                                            rv_vendedor_codigo AS repre_cod,
                                            rv_vendedor_nombre AS repre_name,
                                            rv_vendedor_portafolio AS repre_port,
                                            rv_distribuidoras_valores AS valor_dist,
                                            rv_zona_view AS zona_view,
                                            rv_prod_port AS prod_port,
                                            rv_cuota AS cuota,
                                            rv_valor AS vendido
                                        FROM
                                            reporte_drenaje_ventas
                                        WHERE
                                            rv_periodo = :periodo 
                                            $WHERE;");
        $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        // $query1->bindParam(":region", $region, PDO::PARAM_INT);
        if($query1->execute())
        {
            if($query1->rowCount() > 0)
            {
                /*<b style="color:#3A4750;" class="h4">'.trim(ucfirst(search_region_name($region))).', '.only_mes_($periodo).'</b> 
                            <br> tipo='.$tipo.' region= '.$region.';*/
                            $output .= '
                            <table class="table table-bordered table-condensed table-striped table-hover table-sm" id="table_regional_" style="width:auto;font-size:0.78em;font-weight: regular;font-family: Montserrat, sans-serif;">
                                <thead class="text-white" style="background-color:#476D7C;">
                                    <th class="text-center export-col-x-regional_ desktop tablet fablet phone" style="border-radius:8px 0 0 0;">Zona</th>
                                    <th class="text-center export-col-x-regional_ desktop tablet fablet phone" >Cod.</th>
                                    <th class="text-center export-col-x-regional_ desktop tablet fablet phone" >Repre.</th>
                                    <th class="text-center export-col-x-regional_ desktop tablet" >Cuota</th>';

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
        
                $output .= '<th class="text-center export-col-x-regional_ desktop tablet">Ventas</th>
                            <th class="text-center export-col-x-regional_ desktop tablet fablet phone order_rg">Porcentaje</th>
                            <th class="text-center export-col-x-regional_ desktop tablet">80%</th>
                            <th class="text-center export-col-x-regional_ desktop tablet">90%</th>
                            <th class="text-center export-col-x-regional_ desktop tablet">100%</th>
                            <th class="text-center desktop tablet" style="border-radius:0 8px 0 0;">Detalle</th>
                        </thead>
                        <tbody class="text-font-black" style="color:black;">';
            
                while($rquery1 = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $total = 0;
                    $zona = $rquery1['zona'];
                    $porta = $rquery1['repre_port'];

                    $zona_view = $rquery1['zona_view'];
                    $prod_port = $rquery1['prod_port'];

                    $tmp_zona_port = $zona.'*'.$porta;

                    $repre_cod = $rquery1['repre_cod'];
                    #$repre_name = $rquery1['repre_name'];
                    $repre_name = nombre_corto($rquery1['repre_cod']);
                    $valor_dist = explode(",", $rquery1['valor_dist']);
                    $cuota = $rquery1['cuota'];
                    $vendido = $rquery1['vendido'];

                    $output .= '<tr>
                                    <td class=" col-md-offset-5 text-center">'.$tmp_zona_port.'</td>
                                    <td>'.$repre_cod.'</td>
                                    <td>'.$repre_name.'</td>
                                    <td class="text-center">'.number_format($cuota, 2, '.', ',').'</td>';
                    
                        for ($a = 0; $a <= count($valor_dist)-1; $a++)
                        { 
                            $output .= '<td class="text-center">'.@number_format($valor_dist[$a], 2, '.', ',').'</td>';

                            @$total += $valor_dist[$a];
                        }
                        $porcentaje = @round((($total * 100)/ $cuota), 2).'%';
                        $valortotal = @number_format($total, 2, '.', ',');
                        
                        $_80 = @number_format((($cuota * 0.8) - ($total)), 2, '.', ',');
                        $_90 = @number_format((($cuota * 0.9) - ($total)), 2, '.', ',');
                        $_100 =  @number_format((($cuota * 1) - ($total)), 2, '.', ',');                      

                        if($_80 < 0)
                        {
                            $_80 = "-";
                        }   
                        if($_90 < 0)
                        {
                            $_90 = "-";
                        }   
                        if($_100 < 0)
                        {
                            $_100 = "-";
                        }  

                        if($repre_cod == 40)
                        {
                            $repre_cod = 771;
                        }else if($repre_cod == 758)
                        {
                            $repre_cod = 771;
                        }else if($repre_cod == 771)
                        {
                            $repre_cod = 771;
                        }else
                        {
                            $repre_cod = $repre_cod;
                        }

                        // if($region_venta == 1)
                        // {
                        //     $region_venta = 8;
                        // }
                    // $prod_port = ltrim($prod_port, "'");
                    // $prod_port = rtrim($prod_port, "'");
                        if(strpos($prod_port, ',') !== FALSE) #'A'
                        {
                            $prod_port = str_replace("'", "#", $prod_port);
                        }else
                        {
                            $prod_port = str_replace(",", "|", str_replace("'", "#", $prod_port));
                        }
                    
                    $output .='<td>'.$valortotal.'</td>
                                    <td class="text-center">'.color_x_monto($porcentaje).'</td>
                                    <td class="text-center">'.$_80.'</td>
                                    <td class="text-center">'.$_90.'</td>
                                    <td class="text-center">'.$_100.'</td>
                                    <td class="text-center">
                                        <button style="font-size:1em;font-weight: bold;font-family: Montserrat, sans-serif;" type="button" class="btn btn-sm waves-effect waves-light btn-primary" 
                                        onclick="detail_clientes_visita('."'".$zona_view."'".','."'".$periodo."'".','."'".$prod_port."'".','."'".$region_venta."'".');">
                                        Clientes</button>
                                        <button style="font-size:1em;font-weight: bold;font-family: Montserrat, sans-serif;" type="button" class="btn btn-sm waves-effect waves-light btn-primary" 
                                        onclick="detail_total_ventas_visita('."'".$zona_view."'".','."'".$periodo."'".','."'".$prod_port."'".','."'".$region_venta."'".');">
                                        Detallado</button>
                                    </td>
                                </tr>';
                }

                $output .= '</tbody></table>';
            }else
            {
                $output ='No hay resultados';
            }
        }else
        {
            $output = errorPDO($query1);
        }
        return $output;
    }
    public function detail_total_ventas_visita($zona_view, $periodo, $prod_port, $region_venta)
    {
        $prod_port = str_replace("|", ",", str_replace("#", "'", $prod_port));
        $data = array();
        $tbl = null;
        $QueryExec = null;

        $Query_N1_N2_SC = "SELECT 
                                            drenaje_dist_cod AS dist_cod,
                                            drenaje_cliente_ruc AS cliente_ruc,
                                            drenaje_cliente_cod AS cliente_cod,
                                            drenaje_cliente_name AS cliente_name,
                                            drenaje_prod_cod AS cod_prod,
                                            drenaje_zona_desc AS desc_cliente,
                                            drenaje_fecha AS fecha,
                                            drenaje_prod_name AS desc_prod,
                                            drenaje_cantidad AS cantidad,
                                            drenaje_valor AS valor
                                        FROM
                                            tbl_drenaje_ventas
                                        WHERE
                                            drenaje_periodo = :periodo
                                                AND drenaje_zona_cod IN (SELECT 
                                                    zona_cod
                                                FROM
                                                    tbl_maestro_detalle_zonas
                                                WHERE
                                                    zona_cod_g_zona = :zona_view)
                                                AND drenaje_prod_cod IN (SELECT 
                                                    prod_vis_cod
                                                FROM
                                                    tbl_productos_visita
                                                WHERE
                                                    prod_vis_portafolio IN ($prod_port))
                                            AND drenaje_region_2 = :region_venta
                                        GROUP BY drenaje_id;";

        $QueryLima = "SELECT 
                                drenaje_dist_cod AS dist_cod,
                                drenaje_cliente_ruc AS cliente_ruc,
                                drenaje_cliente_cod AS cliente_cod,
                                drenaje_fecha AS fecha,
                                drenaje_cliente_name AS cliente_name,
                                drenaje_prod_cod AS cod_prod,
                                drenaje_zona_desc AS desc_cliente,
                                drenaje_prod_name AS desc_prod,
                                drenaje_cantidad AS cantidad,
                                drenaje_valor AS valor
                            FROM
                                tbl_drenaje_ventas
                            WHERE
                                drenaje_periodo = :periodo
                                    AND drenaje_zona_visita = :zona_view
                                    AND drenaje_prod_cod IN (SELECT 
                                        prod_vis_cod
                                    FROM
                                        tbl_productos_visita
                                    WHERE
                                        prod_vis_portafolio IN ($prod_port))
                                    AND drenaje_region_2 = :region_venta
                            GROUP BY drenaje_id;";
        if($region_venta == 1)
        {
            $QueryExec = $QueryLima;
        }else
        {
            $QueryExec = $Query_N1_N2_SC;
        }

        $query1 = $this->db->prepare($QueryExec);
        // return $vendedor. ' - '. $portafolio . ' - ' . $region .' - ' . $periodo;
        $query1->bindParam(":periodo", $periodo);
        $query1->bindParam(":zona_view", $zona_view);
        $query1->bindParam(":region_venta", $region_venta);
        if($query1->execute())
        {
            $tbl = '<table class="table table-condensed table-striped table-sm table-bordered" id="table_total_ventas" style="width:auto;color:black;font-weight: regular;font-family: Montserrat, sans-serif;">
                        <thead class="text-center" style="background-color:#476D7C;font-size:0.85em;">
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone" style="border-radius:8px 0 0 0;">Distribuidora</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone">RUC</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Codigo</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Clientes</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Localidad</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Cod-prod</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Desc prod</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone">Cant</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone">Valor</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone">Fecha</th>
                    </thead></table>';
            while ($rquery1 = $query1->fetch(PDO::FETCH_ASSOC))
            {
                $dist_cod = $rquery1['dist_cod'];
                $dist_name = _short_name_($dist_cod);
                $cliente_ruc = $rquery1['cliente_ruc'];
                $cliente_cod = $rquery1['cliente_cod'];
                $cliente_name = $rquery1['cliente_name'];
                $fecha = $rquery1['fecha'];
                $cantidad = (int)$rquery1['cantidad'];
                $valor = $rquery1['valor'];
                $cod_prod = $rquery1['cod_prod'];
                $desc_prod = $rquery1['desc_prod'];
                $desc_cliente = $rquery1['desc_cliente'];

                 $result['dist_name'] = $dist_name;
                $result['cliente_ruc'] = $cliente_ruc;
                $result['cliente_cod'] = $cliente_cod;
                $result['fecha'] = fecha_db_to_view($fecha);
                $result['cliente_name'] = $cliente_name;
                $result['cod_prod'] = $cod_prod;
                $result['desc_prod'] = $desc_prod;
                $result['cantidad'] = $cantidad;
                $result['valor'] = $valor;
                $result['localidad'] = $desc_cliente;

                $data['data'][] = $result;
            }
        }else
        {
            $data['data'][] = 'Error';
        }
        return $tbl.'|~|'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function detail_clientes_visita($zona_view, $periodo, $prod_port, $region_venta)
    {
        $prod_port = str_replace("|", ",", str_replace("#", "'", $prod_port));
        $data = array();
        $tbl = null;
        $QueryExec = null;

        $Query_N1_N2_SC = "SELECT 
                                        drenaje_dist_cod AS dist_cod,
                                        drenaje_cliente_ruc AS cliente_ruc,
                                        drenaje_cliente_cod AS cliente_cod,
                                        drenaje_cliente_name AS cliente_name,
                                        SUM(drenaje_valor) AS sumaxdistri
                                    FROM
                                        tbl_drenaje_ventas
                                    WHERE
                                        drenaje_periodo = :periodo
                                            AND drenaje_zona_cod IN (SELECT 
                                                zona_cod
                                            FROM
                                                tbl_maestro_detalle_zonas
                                            WHERE
                                                zona_cod_g_zona = :zona_view)
                                            AND drenaje_prod_cod IN (SELECT 
                                                prod_vis_cod
                                            FROM
                                                tbl_productos_visita
                                            WHERE
                                                prod_vis_portafolio IN ($prod_port))
                                            AND drenaje_region_2 = :region_venta
                                    GROUP BY drenaje_dist_cod , drenaje_cliente_cod
                                    ORDER BY 5 DESC;";

        $QueryLima = "SELECT 
                                        drenaje_dist_cod AS dist_cod,
                                        drenaje_cliente_ruc AS cliente_ruc,
                                        drenaje_cliente_cod AS cliente_cod,
                                        drenaje_cliente_name AS cliente_name,
                                        SUM(drenaje_valor) AS sumaxdistri
                            FROM
                                tbl_drenaje_ventas
                            WHERE
                                drenaje_periodo = :periodo
                                    AND drenaje_zona_visita = :zona_view
                                    AND drenaje_prod_cod IN (SELECT 
                                        prod_vis_cod
                                    FROM
                                        tbl_productos_visita
                                    WHERE
                                        prod_vis_portafolio IN ($prod_port))
                                    AND drenaje_region_2 = :region_venta
                            GROUP BY drenaje_dist_cod , drenaje_cliente_cod
                            ORDER BY 5 DESC;";

        if($region_venta == 1)
        {
            $QueryExec = $QueryLima;
        }else
        {
            $QueryExec = $Query_N1_N2_SC;
        }


        $query1 = $this->db->prepare($QueryExec);
        // return $vendedor. ' - '. $portafolio . ' - ' . $region .' - ' . $periodo;
        $query1->bindParam(":periodo", $periodo);
        $query1->bindParam(":zona_view", $zona_view);
        $query1->bindParam(":region_venta", $region_venta);
        if($query1->execute())
        {
            $tbl = '<table class="table table-condensed table-striped table-bordered table-sm" id="table_clientes" style="width:auto;color:black;font-weight: regular;font-family: Montserrat, sans-serif;">
                    <thead class="text-center" style="background-color:#476D7C;font-size:0.85em;color:black;">
                        <th class="text-center text-white export-col-x-clientes_ desktop tablet fablet phone" style="border-radius:8px 0 0 0;">Distribuidora</th>
                        <th class="text-center text-white export-col-x-clientes_ desktop tablet fablet phone">RUC</th>
                        <th class="text-center text-white export-col-x-clientes_ desktop tablet fablet">Codigo</th>
                        <th class="text-center text-white export-col-x-clientes_ desktop tablet fablet">Clientes</th>
                        <th class="text-center text-white export-col-x-clientes_ desktop tablet fablet phone this_order_reg">Ventas</th>
                        <th class="text-center text-white desktop tablet fablet">Detalle</th>
                    </thead></table>';
            while ($rquery1 = $query1->fetch(PDO::FETCH_ASSOC))
            {
                $dist_cod = $rquery1['dist_cod'];
                $dist_name = _short_name_($dist_cod);
                $cliente_ruc = $rquery1['cliente_ruc'];
                $cliente_cod = $rquery1['cliente_cod'];
                $cliente_name = $rquery1['cliente_name'];
                $sumaxdistri = $rquery1['sumaxdistri'];

                $vendedor = _zona_to_vendedor($zona_view, $periodo);


                // $button = '<div class="input-group-button">
                //                 <button class="btn btn-primary btn-sm waves-effect waves-light" onclick="return detail_productos_visita('."'".$vendedor."'".','."'".$region_venta."'".','."'".$cliente_cod."'".','."'".$dist_cod."'".','."'".$periodo."'".');">
                //                     Detalle
                //                 </button>
                //             </div>';

                $button = '-';

                $result['dist_name'] = $dist_name;
                $result['cliente_ruc'] = $cliente_ruc;
                $result['cliente_cod'] = $cliente_cod;
                $result['cliente_name'] = $cliente_name;
                $result['sumaxdistri'] = $sumaxdistri;
                $result['button'] = $button;

                $data['data'][] = $result;
            }
        }else
        {
            $data['data'][] = 'Error';
        }
        // return json_encode($data);
        return $tbl.'|~|'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function detail_productos_visita($vendedor, $region_venta, $cliente_cod, $dist_cod, $periodo)
    {
        $Name_Cliente = null;
        $total = 0;
        // $Portafolio_ = $portafolio;

        $QueryCliente = $this->db->prepare("SELECT DISTINCT
                                                drenaje_cliente_name, drenaje_cliente_ruc
                                            FROM
                                                tbl_drenaje_ventas
                                            WHERE
                                                drenaje_cliente_cod = :cliente_cod
                                                    AND drenaje_region_2 = :region_venta
                                                    AND drenaje_periodo = :periodo");
        $QueryCliente->bindParam(":cliente_cod", $cliente_cod);
        $QueryCliente->bindParam(":region_venta", $region_venta);
        $QueryCliente->bindParam(":periodo", $periodo);

        if($QueryCliente->execute() && $QueryCliente->rowCount() == 1)
        {
            $rQueryCliente = $QueryCliente->fetch(PDO::FETCH_ASSOC);
            $Name_Cliente = $rQueryCliente['drenaje_cliente_name'];
        }else
        {
            $Name_Cliente = ' { DATA_REPLACE }';
        }


        $output = '<b class="h4"> '. $Name_Cliente.', ' . only_mes_($periodo).'</b> 
                <table class="table table-condensed table-striped table-bordered table-sm" style="width:auto;color:black;">
                    <thead class="text-center" style="background-color:#476269;">
                        <th class="text-center text-white">Producto</th>
                        <th class="text-center text-white">Cantidad</th>
                        <th class="text-center text-white">Ventas</th>
                    </thead>
                    <tbody class="text-font-black">';#<th class="text-center text-white">Detalle</th>

        $QueryProducto = $this->db->prepare("SELECT 
                                                drenaje_prod_cod AS prod_cod,
                                                drenaje_prod_name AS prod_name,
                                                drenaje_cantidad AS cantidad,
                                                drenaje_valor AS valor
                                            FROM
                                                tbl_drenaje_ventas
                                            WHERE
                                                drenaje_cliente_cod = :cliente_cod
                                                    AND drenaje_dist_cod = :dist_cod
                                                    AND drenaje_region_2 = :region_venta
                                                    AND drenaje_repre_cod = :vendedor
                                                    AND drenaje_periodo = :periodo
                                                    ORDER BY 4 DESC");

        $QueryProducto->bindParam(":vendedor", $vendedor);
        $QueryProducto->bindParam(":cliente_cod", $cliente_cod);
        $QueryProducto->bindParam(":periodo", $periodo);
        $QueryProducto->bindParam(":region_venta", $region_venta);
        $QueryProducto->bindParam(":dist_cod", $dist_cod);
        // $QueryProducto->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);

        if($QueryProducto->execute())
        {
            if($QueryProducto->rowCount() > 0)
            {
                while($rQueryProducto = $QueryProducto->fetch(PDO::FETCH_ASSOC))
                {
                    $producto = $rQueryProducto['prod_name'];
                    $cantidad = $rQueryProducto['cantidad'];
                    $valor = $rQueryProducto['valor'];

                    $output .=  '<tr>
                                    <td class="text-left">'.$producto.'</td>
                                    <td>'. (int)$cantidad.'</td>
                                    <td>'. number_format($valor, 2, '.', ',').'</td>
                                </tr>';#;$vendedor_name . " - " .$cuota. " - ". $valor . "<br/>";
                }
            }else
            {
                $output = 0;#"vacio QueryVendedor";
            }
            $output .= "</tbody></table>";
        }else
        {
            $output = 0;#errorPDO($QueryVendedor_valor);
        }
        return $output;
    }
    public function reporte($periodo)
    {
        // $periodo = 201804;
        $reg_cod = 10;
        $reg_cod_vis = _zona_ventas_visita($reg_cod);
        $output = null;

        $select_zona_reg = $this->db->prepare("SELECT 
                                                    drenaje_zona_g
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_periodo = :periodo
                                                        AND drenaje_region_2 = :reg_cod
                                                    GROUP BY 1;");
            $select_zona_reg->bindParam(":periodo", $periodo);
            $select_zona_reg->bindParam(":reg_cod", $reg_cod);

            if($select_zona_reg->execute())
            {
                $output .= '<table id="reporte_test" class="table table-condensed table-striped table-bordered table-sm" style="width:auto;color:black;">
                                <thead class="text-white" style="background-color:#476269;">
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">Zona</th>
                                    <th class="text-center">Vendedor</th>
                                    <th class="text-center">Localidad</th>
                                    <th class="text-center">Ruc</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Valores</th>
                                </thead>
                                <tfoot class="text-white" style="background-color:#476269;">
                                    <td class="text-center" colspan="6">Total</td>
                                   
                                    <td class="text-center">-</td>
                                </tfoot></table>';/* <td class="text-center">Zona</td>
                                <td class="text-center">Vendedor</td>
                                <td class="text-center">Localidad</td>*/
                while($res_zona_g = $select_zona_reg->fetch(PDO::FETCH_ASSOC))
                {
                    $drenaje_zona_g = $res_zona_g['drenaje_zona_g'];

                    if($drenaje_zona_g == 33)
                    {
                        $src_porafolio = "'A'";
                    }else if($drenaje_zona_g == 31)
                    {
                        $src_porafolio = "'B','C'";
                    }elseif ($drenaje_zona_g == 30) 
                    {
                        $src_porafolio = "'E','F'";
                    }

                    $select_cuota = $this->db->prepare("SELECT 
                                                            cuota_zona AS zona,
                                                            cuota_region AS region,
                                                            cuota_codigo_vendedor AS vendedor,
                                                            cuota_portafolio AS portafolio,
                                                            cuota_monto AS monto
                                                        FROM
                                                            tbl_cuotas
                                                        WHERE
                                                            cuota_periodo = :periodo
                                                        AND cuota_region = :reg_cod_vis
                                                        AND cuota_portafolio IN($src_porafolio) ORDER BY 3;");
                    $select_cuota->bindParam(":periodo", $periodo);
                    $select_cuota->bindParam(":reg_cod_vis", $reg_cod_vis);
                    if($select_cuota->execute())
                    {
                        while ($res_cuota = $select_cuota->fetch(PDO::FETCH_ASSOC))
                        {
                            $total_ventas = 0;                      
                            $count_dis = 0;

                            $zona = $res_cuota['zona'];
                            $region = $res_cuota['region'];
                            $vendedor = $res_cuota['vendedor'];

                            $name_vendedor = search_repre_name($vendedor);

                            if($name_vendedor == 'NoName')
                            {
                                $name_vendedor = 'VACANTE';
                            }else
                            {
                                $name_vendedor = strtoupper($name_vendedor);
                            }

                            $portafolio = $res_cuota['portafolio'];
                            $monto = $res_cuota['monto'];
                            
                            if($drenaje_zona_g == 33)
                            {
                                if($portafolio == 'A')
                                {
                                    $prod_vis = "'A','B'";
                                }
                            }elseif ($drenaje_zona_g == 31)
                            {
                                if($portafolio == 'B')
                                {
                                    $prod_vis = "'A'";
                                }elseif ($portafolio == 'C')
                                {   
                                    $prod_vis = "'B'";
                                }
                            }else if($drenaje_zona_g == 30)
                            {
                                if($portafolio == 'E')
                                {
                                    $prod_vis = "'A'";
                                }elseif ($portafolio == 'F')
                                {   
                                    $prod_vis = "'B'";
                                }
                            }
                            $select_ventas = $this->db->prepare("SELECT 
                                                                        drenaje_ubigeo_desc AS ubi_dsc,
                                                                        drenaje_cliente_ruc as ruc,
                                                                        drenaje_cliente_name,
                                                                        SUM(drenaje_valor) AS total_x_dis
                                                                    FROM
                                                                        tbl_drenaje_ventas
                                                                    WHERE
                                                                        drenaje_periodo = :periodo
                                                                        AND drenaje_zona_cod IN (SELECT 
                                                                                zona_cod
                                                                            FROM
                                                                                tbl_maestro_detalle_zonas
                                                                            WHERE
                                                                                zona_cod_g_zona = :drenaje_zona_g)
                                                                        AND drenaje_prod_cod IN (SELECT 
                                                                                prod_vis_cod
                                                                            FROM
                                                                                tbl_productos_visita
                                                                            WHERE
                                                                                prod_vis_portafolio IN ($prod_vis))
                                                                    GROUP BY drenaje_ubigeo_desc, drenaje_cliente_ruc;");
                            $select_ventas->bindParam(':periodo', $periodo);
                            $select_ventas->bindParam(':drenaje_zona_g', $drenaje_zona_g);
                            if($select_ventas->execute())
                            {   
                                while($res_ventas = $select_ventas->fetch(PDO::FETCH_ASSOC))
                                {
                                    $venta_x_dis = trim($res_ventas['total_x_dis']);
                                    $ubi_dsc = $res_ventas['ubi_dsc'];
                                    
                                    $ruc_cliente = $res_ventas['ruc'];
                                    $name_cliente = $res_ventas['drenaje_cliente_name'];
                                    // $output .= '<tr><td>'.$zona.'*'.$portafolio.'</td>
                                    //             <td>'.$name_vendedor.'</td>
                                    //             <td>'.$ubi_dsc.'</td>
                                    //             <td>'.$venta_x_dis.'</td></tr>';


                                    if($portafolio == 'A')
                                    {
                                        $new_zona = 33;
                                        $new_porta = 'A';
                                    }else if($portafolio == 'B')
                                    {
                                        $new_zona = 31;
                                        $new_porta = 'A';
                                    }else if($portafolio == 'C')
                                    {
                                        $new_zona = 31;
                                        $new_porta = 'B';
                                    }else if($portafolio == 'E')
                                    {
                                        $new_zona = 30;
                                        $new_porta = 'A';
                                    }else if($portafolio == 'F')
                                    {
                                        $new_zona = 30;
                                        $new_porta = 'B';
                                    
                                    }





                                    $res_data['cod_ven'] = $vendedor;
                                    $res_data['zona'] = $new_zona.'*'.$new_porta;#$zona.'*'.$portafolio;
                                    $res_data['name_vendedor'] = $name_vendedor;
                                    $res_data['ubi_dsc'] = $ubi_dsc;
                                    $res_data['ruc_cliente'] = $ruc_cliente;
                                    $res_data['name_cliente'] = $name_cliente;
                                    $res_data['venta_x_dis'] = $venta_x_dis;

                                    $data['data'][] = $res_data;

                                    /*
                                    $output .= "<b>zona = " .$zona.
                                            " - vendedor = " .$vendedor.
                                            " - name_vendedor = " .$name_vendedor.
                                            " - portafolio = " .$portafolio.
                                            " - ubi_dsc =" . $ubi_dsc.
                                            " - venta_x_dis =" . $venta_x_dis . " - periodo = " .$periodo . '<br><br>';*/


                                }
                               
                            }else
                            {
                                $output .= " VENTAS = ".errorPDO($select_ventas).'<br>';
                            }
                        }
                    }else
                    {
                        $output .= " CUOTA = ".errorPDO($select_cuota).'<br>';
                    }
                }
            }else
            {
                $output .= " ZONA_REG = ".errorPDO($select_zona_reg).'<br>';
            }
            // $output .='</tbody>
            // </table>';
            return $output.'|~|'.json_encode($data);
    }
    public function __destruct()
    {
        $this->db = null;
    }
}