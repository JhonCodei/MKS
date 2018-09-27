<?php
#con que producto estoy haciendo
Class AvanceventasModel
{
    public function __construct()
    {
        $this->db = Database::Connection();
    }
    public function listar_avance($periodo, $tipo, $region, $vendedor)
    {
        $WHERE = null;
        $output = null;
        $region_venta = _reg_visita_ventas_($region);


        
        /*CONDICIONAL NORTE2 -GPERz*/
        // if($vendedor == 46)
        // {
        //     $region = 9;
        // }



        if( $tipo == 4 )
        {
            $WHERE = " AND rv_vendedor_codigo = $vendedor AND rv_region_2 = $region ";
        }
        if($region != 'T' && $tipo != 4)
        {
            $WHERE = " AND rv_region_2 = $region ";
            $output .='<h7 class="m-0 pull-left" style="padding-left:2em;" >
                                <a  class="collapsed text-left">
                                    <p class="">'.sup_region_name_2($region).' </p>
                                    <i>
                                        <b class="pull-left h6"> Cuotas: S/ '.number_format(sum_cuotas_x_regiones($region, $periodo), 2, '.', ',').' 
                                        <br> Ventas: S/ '.number_format(sum_x_regiones($region, $periodo), 2, '.', ',').' 
                                        <br> Avance: '.round(procentaje_calcular(sum_cuotas_x_regiones($region, $periodo), sum_x_regiones($region, $periodo)), 2).'%</b>
                                    </i>
                                </a>
                                </h7><br><br><br><br><br>';
                                if($region == 10)
                                {
                                    $output .= '<button class="btn btn-inverse waves-effect waves-light" onclick="return reporte();">
                                                <span class="fa fa-search"></span>
                                            &nbsp;Venta por zona</button><br><br>';
                                }
        }
        if($vendedor == 756 || $vendedor == 46 && $tipo == 5)
        {
            $WHERE = " AND rv_vendedor_codigo = $vendedor AND rv_region_2 = $region ";
        }

        $query1 = $this->db->prepare("SELECT 
                                            rv_zona AS zona,
                                            rv_vendedor_codigo AS repre_cod,
                                            rv_vendedor_nombre AS repre_name,
                                            rv_distribuidoras_valores AS valor_dist,
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
                                    <th class="text-center export-col-x-regional_ desktop tablet fablet phone" >Representante</th>
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
                            <th class="text-center export-col-x-regional_ desktop tablet fablet phone order_rg">Porc.%</th>
                            <th class="text-center export-col-x-regional_ desktop tablet">80%</th>
                            <th class="text-center export-col-x-regional_ desktop tablet">90%</th>
                            <th class="text-center export-col-x-regional_ desktop tablet">100%</th>
                            <th class="text-center desktop tablet" style="border-radius:0 8px 0 0;">Acciones</th>
                        </thead>
                        <tbody class="text-font-black" style="color:black;font-family">';
            
                while($rquery1 = $query1->fetch(PDO::FETCH_ASSOC))
                {
                    $total = 0;
                    $zona = $rquery1['zona'];
                    $repre_cod = $rquery1['repre_cod'];

                    
                    $repre_name = nombre_corto($rquery1['repre_cod']);
                    #$repre_name = $rquery1['repre_name'];
                    $valor_dist = explode(",", $rquery1['valor_dist']);
                    $cuota = $rquery1['cuota'];
                    $vendido = $rquery1['vendido'];

                    if($repre_cod == 810)
                    {
                        $zona = 22;
                    }elseif($repre_cod == 632)
                    {
                        $zona = 21;
                    }

                    $output .= '<tr>
                                    <td class=" col-md-offset-5 text-center">'.$zona.'</td>
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

                    $output .='<td>'.$valortotal.'</td>
                                    <td class="text-center">'.color_x_monto($porcentaje).'</td>
                                    <td class="text-center">'.$_80.'</td>
                                    <td class="text-center">'.$_90.'</td>
                                    <td class="text-center">'.$_100.'</td>
                                    <td class="text-center" >
                                        <button style="font-size:1em;font-weight: bold;font-family: Montserrat, sans-serif;" type="button" class="btn btn-sm waves-effect waves-light btn-primary" 
                                        onclick="detail_clientes('."'".$repre_cod."'".','."'".$periodo."'".','."'".$region."'".');">
                                        Clientes</button>
                                        <button style="font-size:1em;font-weight: bold;font-family: Montserrat, sans-serif;" type="button" class="btn btn-sm waves-effect waves-light btn-default" 
                                        onclick="detail_total_ventas('."'".$repre_cod."'".','."'".$periodo."'".','."'".$region."'".');">
                                        Detalle</button>
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
    public function detail_total_ventas($vendedor, $periodo, $region)
    {
        $data = array();
        $tbl = null;

        $query1 = $this->db->prepare("SELECT 
                                            drenaje_dist_cod AS dist_cod,
                                            drenaje_cliente_ruc AS cliente_ruc,
                                            drenaje_cliente_cod AS cliente_cod,
                                            drenaje_fecha AS fecha,
                                            drenaje_cliente_name AS cliente_name,
                                            drenaje_prod_cod AS cod_prod,
	                                        drenaje_prod_name AS desc_prod,
                                            drenaje_cantidad AS cantidad,
                                            drenaje_valor AS valor
                                        FROM
                                            tbl_drenaje_ventas
                                        WHERE
                                            drenaje_periodo = :periodo
                                                AND drenaje_repre_cod = :vendedor
                                                AND drenaje_region_2 = :region
                                        ORDER BY drenaje_valor DESC;");
        // return $vendedor. ' - '. $portafolio . ' - ' . $region .' - ' . $periodo;
        $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        $query1->bindParam(":vendedor", $vendedor, PDO::PARAM_INT);
        $query1->bindParam(":region", $region, PDO::PARAM_INT);
        if($query1->execute())
        {
            $tbl = '<table class="table table-condensed table-striped table-sm table-bordered" id="table_total_ventas" style="width:auto;color:black;font-weight: regular;font-family: Montserrat, sans-serif;">
                        <thead class="text-center" style="background-color:#476D7C;font-size:0.85em;">
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone" style="border-radius:8px 0 0 0;">Distribuidora</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone">RUC</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Clientes</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Cod-prod</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Desc prod</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone">Cant</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet phone">Valor</th>
                    <th class="text-center text-white export-col-x-clientes_2 desktop tablet fablet">Fecha</th>
                    </thead></table>';
            while ($rquery1 = $query1->fetch(PDO::FETCH_ASSOC))
            {
                $dist_cod = $rquery1['dist_cod'];
                $dist_name = _short_name_($dist_cod);
                $cliente_ruc = $rquery1['cliente_ruc'];
                $cliente_cod = $rquery1['cliente_cod'];
                $cliente_name = $rquery1['cliente_name'];
                $cantidad = (int)$rquery1['cantidad'];
                $valor = $rquery1['valor'];
                $fecha = $rquery1['fecha'];
                $cod_prod = $rquery1['cod_prod'];
                $desc_prod = $rquery1['desc_prod'];
                

                $result['dist_name'] = $dist_name;
                $result['cliente_ruc'] = $cliente_ruc;
                // $result['cliente_cod'] = $cliente_cod;
                $result['fecha'] = fecha_db_to_view($fecha);

                $result['cliente_name'] = $cliente_name;
                $result['cod_prod'] = $cod_prod;
                $result['desc_prod'] = $desc_prod;
                $result['cantidad'] = $cantidad;
                $result['valor'] = $valor;

                $data['data'][] = $result;
            }
        }else
        {
            $data['data'][] = 'Error';
        }
        return $tbl.'|~|'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function detail_clientes($vendedor, $periodo, $region)
    {
        $data = array();
        $tbl = null;

        $query1 = $this->db->prepare("SELECT 
                                            drenaje_dist_cod AS dist_cod,
                                            drenaje_cliente_ruc AS cliente_ruc,
                                            drenaje_cliente_cod AS cliente_cod,
                                            drenaje_cliente_name AS cliente_name,
                                            SUM(drenaje_valor) AS sumaxdistri
                                        FROM
                                            tbl_drenaje_ventas
                                        WHERE
                                            drenaje_periodo = :periodo
                                                AND drenaje_repre_cod = :vendedor
                                                AND drenaje_region_2 = :region
                                        GROUP BY drenaje_dist_cod , drenaje_cliente_ruc
                                        ORDER BY 5 DESC;");
        // return $vendedor. ' - '. $portafolio . ' - ' . $region .' - ' . $periodo;
        $query1->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        $query1->bindParam(":vendedor", $vendedor, PDO::PARAM_INT);
        $query1->bindParam(":region", $region, PDO::PARAM_INT);
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

                $button = '<div class="input-group-button">
                                <button class="btn btn-primary btn-sm waves-effect waves-light" onclick="return detail_productos('."'".$vendedor."'".','."'".$region."'".','."'".$cliente_cod."'".','."'".$dist_cod."'".','."'".$periodo."'".');">
                                    Detalle
                                </button>
                            </div>';

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
    public function detail_productos($vendedor, $region, $cliente_cod, $dist_cod, $periodo)
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
                                                    AND drenaje_region_2 = :region
                                                    AND drenaje_periodo = :periodo");
        $QueryCliente->bindParam(":cliente_cod", $cliente_cod, PDO::PARAM_STR);
        $QueryCliente->bindParam(":region", $region, PDO::PARAM_INT);
        $QueryCliente->bindParam(":periodo", $periodo, PDO::PARAM_INT);

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
                                                    AND drenaje_region_2 = :region
                                                    AND drenaje_repre_cod = :vendedor
                                                    -- AND drenaje_portafolio = :portafolio
                                                    AND drenaje_periodo = :periodo
                                                    ORDER BY 4 DESC");

        $QueryProducto->bindParam(":vendedor", $vendedor, PDO::PARAM_INT);
        $QueryProducto->bindParam(":cliente_cod", $cliente_cod, PDO::PARAM_STR);
        $QueryProducto->bindParam(":periodo", $periodo, PDO::PARAM_INT);
        $QueryProducto->bindParam(":region", $region, PDO::PARAM_INT);
        $QueryProducto->bindParam(":dist_cod", $dist_cod, PDO::PARAM_INT);
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

        $select_zona_reg = $this->db->prepare("SELECT DISTINCT
                                                    drenaje_repre_cod, drenaje_zona_g
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_periodo = :periodo
                                                        AND drenaje_region_2 = :reg_cod ORDER BY 2 DESC;");
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
                    $drenaje_repre_cod = $res_zona_g['drenaje_repre_cod'];
                    $name_vendedor = search_repre_name($drenaje_repre_cod);

                    if($name_vendedor == 'NoName')
                    {
                        $name_vendedor = 'VACANTE - TUMBES';
                    }

                    $select_ventas = $this->db->prepare("SELECT 
                                                            drenaje_ubigeo_desc AS ubi_dsc,
                                                            drenaje_cliente_ruc AS ruc,
                                                            drenaje_cliente_name,
                                                            SUM(drenaje_valor) AS total_x_dis
                                                        FROM
                                                            tbl_drenaje_ventas
                                                        WHERE
                                                            drenaje_periodo = :periodo
                                                                AND drenaje_repre_cod = :drenaje_repre_cod
                                                                AND drenaje_region_2 = :reg_cod
                                                        GROUP BY drenaje_ubigeo_desc , drenaje_cliente_ruc;");
                            $select_ventas->bindParam(':periodo', $periodo);
                            $select_ventas->bindParam(':drenaje_repre_cod', $drenaje_repre_cod);
                            $select_ventas->bindParam(':reg_cod', $reg_cod);
                            if($select_ventas->execute())
                            {   
                                if($select_ventas->rowCount() > 0)
                                {
                                    while($res_ventas = $select_ventas->fetch(PDO::FETCH_ASSOC))
                                    {
                                        $venta_x_dis = trim($res_ventas['total_x_dis']);
                                        $ubi_dsc = $res_ventas['ubi_dsc'];
                                        
                                        $ruc_cliente = $res_ventas['ruc'];
                                        $name_cliente = $res_ventas['drenaje_cliente_name'];
    
                                        $res_data['cod_ven'] = $drenaje_repre_cod;
                                        $res_data['zona'] = $drenaje_zona_g;#$zona.'*'.$portafolio;
                                        $res_data['name_vendedor'] = $name_vendedor;
                                        $res_data['ubi_dsc'] = $ubi_dsc;
                                        $res_data['ruc_cliente'] = $ruc_cliente;
                                        $res_data['name_cliente'] = $name_cliente;
                                        $res_data['venta_x_dis'] = $venta_x_dis;
    
                                        $data['data'][] = $res_data;
    
                                    }    
                                }else
                                {
                                    $output .= "VENTAS = 0";
                                    $data['error'] = 'Error';
                                }
                            }else
                            {
                                $output .= " VENTAS = ".errorPDO($select_ventas).'<br>';
                                $data['error'] = 'Error';
                            }
                }
            }else
            {
                $output .= " ZONA_REG = ".errorPDO($select_zona_reg).'<br>';
                $data['error'] = 'Error';
            }
            // $output .='</tbody>
            // </table>';
            return $output.'|~|'.json_encode($data);
    }

    public function __destruct()
    {
        $this->db = NULL;
    }
}