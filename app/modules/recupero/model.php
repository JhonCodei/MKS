<?php 

Class RecuperoModel
{
    public function __construct()
    {
        $this->db = Database::Connection();
    }
    public function listar_data_detalle($periodo)
    {
        $output = null;

        $sql = "SELECT 
                    drenaje_repre_cod AS repre_cod,
                    drenaje_repre_name AS repre_name,
                    drenaje_dist_cod AS dist_cod,
                    drenaje_dist_name AS dist_name,
                    drenaje_fecha AS fecha,
                    drenaje_cliente_cod AS client_cod,
                    drenaje_cliente_ruc AS client_ruc,
                    drenaje_cliente_name AS cliente_name,
                    drenaje_prod_cod AS prod_cod,
                    drenaje_prod_name AS prod_name,
                    drenaje_cantidad AS cantidad,
                    drenaje_valor AS valor,
                    drenaje_region_2 AS region
                FROM
                    tbl_drenaje_ventas
                WHERE
                    drenaje_periodo = :periodo";
        $execSQL = $this->db->prepare($sql);
        $execSQL->bindParam(':periodo', $periodo);
        if($execSQL->execute())
        {
            if($execSQL->rowCount() > 0)
            {
                $output .= '<table id="table_listado_data_detalle" class="table-condensed table table-bordered table-sm"
                                style="width:auto !important;font-size:0.85em;font-weight: regular;font-family: Montserrat, sans-serif;">
                                <thead class="text-white" style="background-color:#588D9C;">
                                    <th class="text-center">repre_cod</th>
                                    <th class="text-center">repre_name</th>
                                    <th class="text-center">distcod</th>
                                    <th class="text-center">dist_name</th>
                                    <th class="text-center">fecha</th>
                                    <th class="text-center">client_cod</th>
                                    <th class="text-center">client_ruc</th>
                                    <th class="text-center">client_name</th>
                                    <th class="text-center">prod_cod</th>
                                    <th class="text-center">prod_name</th>
                                    <th class="text-center">cantidad</th>
                                    <th class="text-center">valor</th>
                                    <th class="text-center">region</th>
                                </thead></table>';
                while ($result = $execSQL->fetch(PDO::FETCH_ASSOC))
                {
                    $repre_cod = $result['repre_cod'];
                    $repre_name = $result['repre_name'];
                    $dist_cod = $result['dist_cod'];
                    $dist_name = $result['dist_name'];
                    $fecha = $result['fecha'];
                    $client_cod = $result['client_cod'];
                    $client_ruc = $result['client_ruc'];
                    $cliente_name = $result['cliente_name'];
                    $prod_cod = $result['prod_cod'];
                    $prod_name = $result['prod_name'];
                    $cantidad = $result['cantidad'];
                    $valor = $result['valor'];
                    $region = $result['region'];

                    $return['repre_cod'] = $repre_cod;
                    $return['repre_name'] = $repre_name;
                    $return['dist_cod'] = $dist_cod;
                    $return['dist_name'] = $dist_name;
                    $return['fecha'] = $fecha;
                    $return['client_cod'] = $client_cod;
                    $return['client_ruc'] = $client_ruc;
                    $return['cliente_name'] = $cliente_name;
                    $return['prod_cod'] = $prod_cod;
                    $return['prod_name'] = $prod_name;
                    $return['cantidad'] = $cantidad;
                    $return['valor'] = $valor;
                    $return['region'] = $region;

                    $data['data'][] = $return;
                }
            }else
            {
                $output = 0;
                $data['error'] = 'Sin registro';
            }
        }
        return $output.'|~|'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function procesar_recupero($year, $mes)
    {
        $output = null;
        $periodo = fecha_to_periodo($year.'-'.$mes.'-01');

        $query1 = "SELECT 
                        data_ruc AS ruc,
                        data_n_comercial AS n_comercial,
                        data_prod_cod AS prod_cod,
                        data_prod_nombre AS prod_name,
                        data_cod_dist AS dist_cod,
                        data_nombre_dist AS dist_name
                    FROM
                        tbl_ruc_prod_dist;";
                        /*WHERE
                        YEAR(data_registro) = :year
                            AND MONTH(data_registro) = :mes;";*/
        $execQuery1 = $this->db->prepare($query1);
        // $execQuery1->bindParam(':year', $year);
        // $execQuery1->bindParam(':mes', $mes);
        if($execQuery1->execute())
        {
            if($execQuery1->rowCount() > 0)
            {
                while($result1 = $execQuery1->fetch(PDO::FETCH_ASSOC))
                {
                    $ruc = $result1['ruc'];
                    $n_comercial = $result1['n_comercial'];
                    $prod_cod = $result1['prod_cod'];
                    $prod_name = $result1['prod_name'];
                    $dist_cod = $result1['dist_cod'];
                    $dist_name = $result1['dist_name'];

                    #VARIABLES_MANUALES-MOD_AUTO;
                    // $precio_lista = 14;#4
                    // $precio_sin_igv = 21.38;#5
                    // $porcentjae_tabla = 26.20;
                    // $p_100 = 100;
                    // $precio_venta_sin_igv = 18.12;
                    // $precio_venta_con_igv = 21.3;
                    // $descuento_x_distribuidora = 1.13;

                    $precio_lista = 14;#4#¨RECIO SUGERIDO
                    $precio_sin_igv = 21.38;#5
                    $porcentjae_tabla = 26.20;
                    $p_100 = 100;
                    $precio_venta_sin_igv = 18.12;
                    $precio_venta_con_igv = 21.38;
                    $descuento_x_distribuidora = 1.13;
                    $quimica = 1.13;
                    $dimex = 1.15;
                    $mks;

                    $query2 = "SELECT 
                                    drenaje_repre_cod AS repre_cod,
                                    drenaje_repre_name AS repre_name,
                                    drenaje_dist_cod AS dist_cod,
                                    drenaje_dist_name AS dist_name,
                                    drenaje_fecha AS fecha,
                                    drenaje_cliente_cod AS client_cod,
                                    drenaje_cliente_ruc AS client_ruc,
                                    drenaje_cliente_name AS cliente_name,
                                    drenaje_prod_cod AS prod_cod,
                                    drenaje_prod_name AS prod_name,
                                    drenaje_cantidad AS cantidad,
                                    drenaje_valor AS valor,
                                    drenaje_region_2 AS region
                                FROM
                                    tbl_drenaje_ventas
                                WHERE
                                    drenaje_periodo = :periodo
                                        AND drenaje_cliente_ruc = :ruc
                                        AND drenaje_prod_cod = :prod_cod
                                        AND drenaje_dist_cod = :dist_cod;";
                    $execQuery2 = $this->db->prepare($query2);
                    $execQuery2->bindParam(':periodo', $periodo);
                    $execQuery2->bindParam(':ruc', $ruc);
                    $execQuery2->bindParam(':prod_cod', $prod_cod);
                    $execQuery2->bindParam(':dist_cod', $dist_cod);
                    if($execQuery2->execute())
                    {
                        if($execQuery2->rowCount() > 0)
                        {
                            $output .= '<table id="table_procesado_recupero" class="table-condensed table table-bordered table-sm"
                                            style="width:auto !important;font-size:0.85em;font-weight: regular;font-family: Montserrat, sans-serif;">
                                            <thead class="text-white" style="background-color:#588D9C;">
                                                <th class="text-center">ruc</th>
                                                <th class="text-center">name</th>
                                                <th class="text-center">cod_prod</th>
                                                <th class="text-center">prod_name</th>
                                                <th class="text-center">cnt</th>
                                                <th class="text-center">valor</th>
                                                <th class="text-center">op1</th>
                                                <th class="text-center">op2</th>
                                                <th class="text-center">op3</th>
                                                <th class="text-center">op4</th>
                                                <th class="text-center">op5</th>
                                                <th class="text-center">op6</th>
                                                <th class="text-center">op7</th>
                                                <th class="text-center">op8</th>
                                                <th class="text-center">op9</th>
                                                <th class="text-center">op10</th>
                                            </thead></table>';

                            while ($result2 = $execQuery2->fetch(PDO::FETCH_ASSOC))
                            {   
                                $repre_cod = $result2['repre_cod'];
                                $repre_name = $result2['repre_name'];
                                $dist_cod = $result2['dist_cod'];
                                $dist_name = $result2['dist_name'];
                                $fecha = $result2['fecha'];
                                $client_cod = $result2['client_cod'];
                                $client_ruc = $result2['client_ruc'];
                                $cliente_name = $result2['cliente_name'];
                                $prod_cod = $result2['prod_cod'];
                                $prod_name = $result2['prod_name'];
                                $cantidad = $result2['cantidad'];
                                $valor = $result2['valor'];
                                $region = $result2['region'];


                                $op1 = round(($precio_sin_igv - $precio_lista), 2);#6
                                $op2 = round((($op1/$precio_sin_igv) * 100), 2);##7
                                $op3 = abs(round(($op2 - $porcentjae_tabla), 2));#
                                $op4 = round(($p_100 - $porcentjae_tabla), 2);#11
                                $op5 = round((($op3/$op4)*100), 2);#14
                                $op6_1 = round(($precio_venta_sin_igv * $descuento_x_distribuidora), 2);
                                $op6_2 = round(($op6_1 - $precio_venta_sin_igv), 2);
                                $op6 = round(($precio_venta_sin_igv - $op6_2), 2);#15
                                $op7 = round(((($op6*$porcentjae_tabla)/100)), 2);#16
                                $op8 = abs(round((($op7 - $op6)), 2));#17
                                $op9 = round((($op8 * $op5)/100), 2);
                                $op10 = round(($op9 * $cantidad), 2);

                                // $result_data['sssss'] = $sssss;

                                $result_data['client_ruc'] = $client_ruc;
                                $result_data['cliente_name'] = $cliente_name;
                                $result_data['prod_cod'] = $prod_cod;
                                $result_data['prod_name'] = $prod_name;
                                $result_data['cantidad'] = $cantidad;
                                $result_data['valor'] = $valor;

                                $result_data['op1'] = $op1;
                                $result_data['op2'] = $op2;
                                $result_data['op3'] = $op3;
                                $result_data['op4'] = $op4;
                                $result_data['op5'] = $op5;
                                $result_data['op6'] = $op6;
                                $result_data['op7'] = $op7;
                                $result_data['op8'] = $op8;
                                $result_data['op9'] = $op9;
                                $result_data['op10'] = $op10;                               

                                $data['data'][] = $result_data;
                            }
                        }else
                        {
                            $output = 0;
                            $data['error'] = 'Sin registro';
                        }
                    }
                }
            }
        }
        return $output.'|~|'.json_encode($data, JSON_UNESCAPED_UNICODE);
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
    public function tabla_descuento_base($periodo, $codigo_producto, $valor)
    {
        $output = null;

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
                                AND producto_id = :codigo_producto";

            $runQuery1 = $this->db->prepare($SQL);
            $runQuery1->bindParam(":periodo", $periodo);
            $runQuery1->bindParam(":codigo_producto", $codigo_producto);

            if($runQuery1->execute())
            {
                if($runQuery1->rowCount() > 0)
                {
                    $output = "<div><h5 class='font-weight-bold'>Escalas</h5></div>
                    <table class='table table-bordered table-sm font-weight-bold' style='width:auto !important;'>
                        <thead style='background-color:#4d7cae;'  class='text-white'>
                            <th class='text-center'>&nbsp;&nbsp;Rango&nbsp;&nbsp;</th>
                            <th class='text-center'>&nbsp;&nbsp;Descuento&nbsp;&nbsp;</th>
                        </thead>
                        <tbody>";

                    while($Query1 = $runQuery1->fetch(PDO::FETCH_ASSOC))
                    {
                        $u_inicio = $Query1['u_inicio'];
                        $u_fin = $Query1['u_fin'];
                        $desc_base = round($Query1['desc_base'],2);
                        
                        if($u_inicio  <= $valor && $u_fin >= $valor)
                        {
                            $output .= "<tr style='background-color:#63aebb;' class='text-center'>
                                        <td class='text-white'>".$u_inicio."</td>
                                        <td class='text-white'>".$desc_base."%</td>
                                    </tr>";
                        }else
                        {
                            $output .=   "<tr class='text-center'>
                                                <td>".$u_inicio."</td>
                                                <td>".$desc_base."%</td>
                                            </tr>";
                        }
                    }
                    $output .=   "</tbody></table>";
                }else
                {
                    $output = 'Sin escala';
                }
            }
            return $output;
    }
    public function precio_lista_x_prod_x_dist($periodo, $codigo_producto, $distribuidora)
    {
        $output = array('descuento' => 0, 'precio_lista' => 0);

            $SQL = "SELECT 
            producto_id, descuento, precio_lista
        FROM
            mks_unidos_db.precio_productos_distribuidoras
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

                    $output = array('descuento' => $descuento, 'precio_lista' => $precio_lista);
                }
            }
            return $output;
    }
    public function procesar_escalas($periodo, $region)
    {
        $output = null;
        $addWhere = null;
        //$region = 3;
        if($region != 'T')
        {
            $addWhere = " AND drenaje_region_2 = '$region' ";
        }

        $sql = "SELECT 
                        drenaje_region_2 AS region,
                        drenaje_zona_g AS zonag,
                        drenaje_repre_cod AS repre,
                        drenaje_dist_cod AS dist_cod,
                        drenaje_cliente_ruc AS cliente_ruc,
                        drenaje_cliente_name AS cliente_name,
                        drenaje_prod_cod AS prod_cod,
                        drenaje_prod_name AS prod_name,
                        SUM(drenaje_cantidad) AS suma_cant,
                        SUM(drenaje_valor) AS suma_valor
                    FROM
                        tbl_drenaje_ventas
                    WHERE
                        drenaje_periodo = :periodo
                        $addWhere 
                    GROUP BY drenaje_region_2 , drenaje_repre_cod, drenaje_cliente_ruc, drenaje_prod_cod";

        $execSQL = $this->db->prepare($sql);
        $execSQL->bindParam(':periodo', $periodo);
        if($execSQL->execute())
        {
            if($execSQL->rowCount() > 0)
            {/*
                
                <div class="text-left col-lg-12 form-inline">
                                        <div class="form-inline"><label>&nbsp;&nbsp;Region: &nbsp;&nbsp;</label><div id="div_option_1" style="max-width:80%;"></div></div>
                                        <div class="form-inline"><label>&nbsp;&nbsp;Representante: &nbsp;&nbsp;</label><div id="div_option_2" style="max-width:80%;"></div></div>
                                        <div class="form-inline"><label>&nbsp;&nbsp;Distribuidora: &nbsp;&nbsp;</label><div id="div_option_4" style="max-width:80%;"></div>
                                        <div class="form-inline"><label>&nbsp;&nbsp;Cliente: &nbsp;&nbsp;</label><div id="div_option_6" style="max-width:80%;"></div></div>
                                        <div class="form-inline"><label>&nbsp;&nbsp;Producto: &nbsp;&nbsp;</label><div id="div_option_8" style="max-width:80%;"></div></div>
                                        <br><br><br>
                                        <hr/>
                                    </div>

                                    <div class="text-left col-lg-12">
                                    <div class="form-inline"><label class="pull-left">&nbsp;&nbsp;Region: &nbsp;&nbsp;</label><div class="pull-right" id="div_option_1" style="max-width:100%;"></div></div>
                                    <div class="form-inline"><label class="pull-left">&nbsp;&nbsp;Representante: &nbsp;&nbsp;</label><div class="pull-right" id="div_option_2" style="max-width:100%;"></div></div>
                                    <div class="form-inline"><label class="pull-left">&nbsp;&nbsp;Distribuidora: &nbsp;&nbsp;</label><div class="pull-right" id="div_option_4" style="max-width:100%;"></div></div>
                                    <div class="form-inline"><label class="pull-left">&nbsp;&nbsp;Cliente: &nbsp;&nbsp;</label><div class="pull-right" id="div_option_6" style="max-width:100%;"></div></div>
                                    <div class="form-inline"><label class="pull-left">&nbsp;&nbsp;Producto: &nbsp;&nbsp;</label><div class="pull-right" id="div_option_8" style="max-width:100%;"></div></div>
                                    </div>
                */
                $output .= ' <table>
                                    <tbody>
                                        <tr>
                                            <td><label class="pull-right">Region:</label></td>
                                            <td><div class="pull-left" id="div_option_1"></div></td>

                                            <td><label class="pull-right">Representante:</label></td>
                                            <td><div class="pull-left" id="div_option_2"></div></td>

                                            <td><label class="pull-right">Distribuidora:</label></td>
                                            <td><div class="pull-left" id="div_option_4"></div></td>
                                        </tr>
                                        <tr>
                                            <td><label class="pull-right">Cliente:</label></td>
                                            <td><div class="pull-left" id="div_option_6"></div></td>

                                            <td><label class="pull-right">Producto:</label></td>
                                            <td><div class="pull-left" id="div_option_8"></div></td>
                                        </tr>
                                    </tbody>
                                    </table>
                                <hr/>
                                <label class="h4 pull-left" style="padding-left:15px;" id="total-recupero"></label><br><br>
                <table id="table-data-escalas-1" width="auto" class="table-condensed table table-bordered table-sm"
                                style="font-size:0.85em;font-weight: regular;font-family: Montserrat, sans-serif;">
                                <thead class="text-white" style="background-color:#4d7cae;">
                                    <tr>
                                        <th class="text-center this_events">Suprv.</th>
                                        <th class="text-center this_events">Zona</th>
                                        <th class="text-center this_events">Repre.</th>
                                        <th class="text-center">dist_cod</th>
                                        <th class="text-center this_events">Dist.</th>
                                        <th class="text-center">Ruc</th>
                                        <th class="text-center this_events">N. Comercial</th>
                                        <th class="text-center">Codigo</th>
                                        <th class="text-center this_events">Producto</th>
                                        <th class="text-center this_events">Cant.</th>
                                        <th class="text-center this_events">Val.</th>
                                        <th class="text-center " style="background-color:#008f99;">Desc B. tabla</th>
                                        <th class="text-center this_events" style="background-color:#008f99;">Desc B. tabla</th>
                                        <th class="text-center" style="background-color:#008f99;">Val. / Base</th>
                                        <th class="text-center this_events" style="background-color:#008f99;">Val. uni.</th>
                                        <th class="text-center this_events" style="background-color:#008f99;">P. List</th>
                                        <th class="text-center this_events" style="background-color:#008f99;">P.U. IGV</th>
                                        <th class="text-center this_events" style="background-color:#528078;">P. List</th>
                                        <th class="text-center this_events" style="background-color:#528078;">Desc. Dist.</th>
                                        <th class="text-center this_events" style="background-color:#528078;">Vnt. Fac.</th>
                                        <th class="text-center this_events" style="background-color:#e42449;">Rec.</th>
                                    </tr>
                                </thead>
                                </table>';
                                /*
                                
                                <tfoot style="background-color:#4d7cae;font-size:0.95em;" class="text-white">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td id="footer_11"></td>
                                    <td id="footer_12"></td>
                                    <td id="footer_13"></td>
                                    <td id="footer_14"></td>
                                    <td id="footer_15"></td>
                                    <td id="footer_16"></td>
                                    <td id="footer_17"></td>
                                    <td id="footer_18"></td>
                                    <td style="background-color:#e42449;"></td>
                                </tfoot>
                                */
                while ($result = $execSQL->fetch(PDO::FETCH_ASSOC))
                {

                    $region = $result["region"];
                    if($result["repre"] == 999)
                    {
                        $repre = "MI FARMA";
                    }elseif($result["repre"] == 989)
                    {
                        $repre = "ECKERD";
                    }elseif($result["repre"] == 51)
                    {
                        $repre = "Rubi Cisneros";
                    }else
                    {
                        $repre = nombre_corto($result["repre"]);
                    }
                    
                    
                    if($result["repre"] == 632 )
                    {
                        $zonag = 21;
                        $region = 99;
                    }else if($result["repre"] == 810)
                    {
                        $zonag = 22;
                        $region = 99;
                    }else if($result["repre"] == 218)
                    {
                        $zonag = 9;
                        $region = 99;
                    }else
                    {
                        $zonag = zerofill($result["zonag"], 2);
                        $region = $result["region"];
                    }


                    $region_sup = sup_region_name($region);

                    $dist_cod = $result["dist_cod"];#_short_name_
                    #$dist_name = $result['dist_name'];
                    $dist_name = _short_name_($result['dist_cod']);
                    $cliente_ruc = $result["cliente_ruc"];
                    #$cliente_name = max_string_values($result["cliente_name"], 20);
                    $cliente_name = $result["cliente_name"];

                    $prod_cod = $result["prod_cod"];
                    #$prod_name = max_string_values($result["prod_name"], 20);
                    $prod_name = $result["prod_name"];

                    $suma_cant = (int)$result["suma_cant"];
                    $suma_valor = $result["suma_valor"];
                    $descuento = round($this->descuento_base($periodo, $prod_cod, $suma_cant),2);
                    $data_precios_desc = $this->precio_lista_x_prod_x_dist($periodo, $prod_cod, $dist_cod);
                    $content = $this->tabla_descuento_base($periodo, $prod_cod, $suma_cant);

                    $descuento_dist = $data_precios_desc['descuento'];
                    $precio_lista = $data_precios_desc['precio_lista'];

                    $op1_a = ($precio_lista * ($descuento_dist/100));
                    $op1_b = ($precio_lista-$op1_a)*1.18;
                    $op2_a = ($precio_lista * ($descuento/100));
                    $op2_b = ($precio_lista-$op2_a)*1.18;

                    $valor_uni = 0;


                    if($suma_valor > 0)
                    {
                        @$recupero = round($op1_b-$op2_b, 2);

                        if($suma_cant > 0)
                        {
                            $valor_uni = round(($suma_valor / $suma_cant), 2);
                        }else
                        {
                            $valor_uni = 0;
                        }
                        
                        if($recupero < 0)
                        {
                            $recupero = 0;
                        }
                    }else
                    {
                        $recupero = 0;
                        $valor_uni = 0;
                    }
                    

                    if($descuento != 0)
                    {
                        $val_x_desc = ($suma_valor * ($descuento/100));
                    }else
                    {
                         $val_x_desc = 0;
                    }
                    //$btn1 = ($suma_valor * ($descuento/100))
                    $val_x_desc = round($val_x_desc,2);

                    $btn_popper = '<button type="button"  class="btn btn-default btn-sm" data-html="true" data-toggle="popover"  
                                        data-placement="bottom" data-trigger="focus" data-content="'.$content.'">
                                        <span class="fa fa-info"></span></button>';

                    $return['region'] = $region_sup;
                    $return['zonag'] = $zonag;
                    $return['repre'] = $repre;
                    $return['dist_cod'] = $dist_cod;
                    $return['dist_name'] = $dist_name;
                    $return['cliente_ruc'] = $cliente_ruc;
                    $return['cliente_name'] = $cliente_name;
                    $return['prod_cod'] = $prod_cod;
                    $return['prod_name'] = $prod_name;
                    $return['suma_cant'] = $suma_cant;
                    $return['suma_valor'] = $suma_valor;
                    #$return['descuento'] = $btn_popper.'&nbsp;'.$descuento.'%';
                    $return['descuento'] = "<div class='input-group'>".$btn_popper.'&nbsp;'.$descuento.'%</div>';
                    $return['descuento_sin_escala'] = $descuento.'%';
                    $return['val_x_desc'] = $val_x_desc;
                    $return['valor_uni'] = $valor_uni;
                    $return['op1_'] = round($op1_b, 2);
                    $return['p_list'] = $precio_lista;
                    $return['desc_dist'] = $descuento_dist;
                    $return['op2_'] = round($op2_b, 2);
                    $return['recupero'] = $recupero;

                    $data['data'][] = $return;
                }
            }else
            {
                $output = 0;
                $data['error'] = 'Sin registro';
            }
        }
        return $output.'|~|'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    


    public function __destruct()
    {
        $this->db = null;
    }
}