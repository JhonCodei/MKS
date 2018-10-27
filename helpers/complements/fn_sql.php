<?php
function nombre_corto($var)
{
        $output = 'vacante';

        $SQL = "SELECT 
                            representante_codigo AS codigo,
                            representante_nombre AS nombre,
                            nombre_corto
                        FROM
                            tbl_representantes
                        WHERE
                            representante_codigo = :var;";

        $runQuery1 = Database::Connection()->prepare($SQL);
        $runQuery1->bindParam(":var", $var);
        if($runQuery1->execute())
        {
            if($runQuery1->rowCount() > 0)
            {
                $Query1 = $runQuery1->fetch(PDO::FETCH_ASSOC);
                $nombre_corto = $Query1['nombre_corto'];
                $output = $nombre_corto;
            }
        }
        return $output;
}
function kushka_codprod_markos($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                                            *
                                                                        FROM
                                                                            tbl_productos_kushka
                                                                        WHERE
                                                                            prod_kusha_cod = :var
                                                                        LIMIT 0 , 1;");
    $query->bindParam(":var", $var);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $markos = $rQuery0['prod_markos_cod'];


            $output = $markos;

        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function kushka_data_names($var)
{
    $output = null;


    $var = trim($var);
    $query = Database::Connection()->prepare("SELECT f_local_desc
                                        FROM tbl_kushka_maestro_locales WHERE f_local_codigo = :var");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $nombre = $rQuery0['f_local_desc'];

            $output = $nombre;
        }else
        {
            $output = '-';
        }
    }

    return $output;
}
function kushka_data($var)
{
    $output = null;


    $var = trim($var);
    $query = Database::Connection()->prepare("SELECT f_local_desc, f_local_departamento, 
                                        f_local_provincia, f_local_zona 
                                        FROM tbl_kushka_maestro_locales WHERE f_local_codigo = :var");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $nombre = $rQuery0['f_local_desc'];
            // $departamento = $rQuery0['f_local_departamento'];
            // $provincia = $rQuery0['f_local_provincia'];
            $zona = $rQuery0['f_local_zona'];

            $output = $nombre.'~~'.$zona;
        }else
        {
            $output ='noname~~0';
        }
    }

    return $output;
}
function kushka_zonag($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT zona_cod_g_zona FROM tbl_maestro_detalle_zonas WHERE zona_cod = :var");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['zona_cod_g_zona'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function kushka_region_($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT zonag_region FROM tbl_maestro_zonas WHERE zonag_codigo = :var");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['zonag_region'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function cantidad_sctock($repre_cod, $periodo)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    COALESCE(SUM(stock_cantidad_tmp)) AS stock
                                                FROM
                                                    tbl_stock
                                                WHERE
                                                    stock_periodo = :periodo
                                                        AND stock_codigo_vendedor = :repre_cod");
    $query->bindParam(":repre_cod", $repre_cod);
    $query->bindParam(":periodo", $periodo);
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $res_query = $query->fetch(PDO::FETCH_ASSOC);
            
            $output = $res_query['stock'];
        }
    }                                           
    return $output;
}
function realizado_categoria($repre_cod, $mes, $year, $categoria)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    COUNT(DISTINCT medpro_medico_cmp, medpro_fecha) AS realizado
                                                FROM
                                                    tbl_medpro_detalle
                                                WHERE
                                                    medpro_vendedor = :repre_cod
                                                        AND YEAR(medpro_fecha) = :year
                                                        AND MONTH(medpro_fecha) = :mes
                                                        AND medpro_medico_categoria = :categoria
                                                        AND medpro_estado IN(1,2);");
    $query->bindParam(":repre_cod", $repre_cod);
    $query->bindParam(":year", $year);
    $query->bindParam(":mes", $mes);
    $query->bindParam(":categoria", $categoria);
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $res_query = $query->fetch(PDO::FETCH_ASSOC);
            
            $output = $res_query['realizado'];
        }
    }                                           
    return $output;
}
function pronostico_categoria($repre_cod, $categoria)
{
    $output = null;

    $Exec_Pronostico = null;
    $user_existe_medico = user_existe_medico($repre_cod);


        $Query_PRONOSTICO1 = "SELECT 
                                    COUNT(medico_cmp) AS pronostico
                                FROM
                                    tbl_maestro_medicos
                                WHERE
                                    medico_categoria = :categoria
                                        AND medico_representante = :repre_cod;";
        $Query_PRONOSTICO2 = "SELECT 
                                    COUNT(medico_cmp) AS pronostico
                                FROM
                                    tbl_maestro_medicos
                                WHERE
                                    medico_categoria = :categoria
                                        AND medico_zona = (SELECT 
                                                representante_zonag
                                            FROM
                                                tbl_representantes
                                            WHERE
                                                representante_codigo = :repre_cod
                                                AND medico_alta_baja = 'A')
                                        AND medico_representante IS NULL;";

        if($user_existe_medico == 1)
        {
            $Exec_Pronostico = $Query_PRONOSTICO1;
        }else
        {
            $Exec_Pronostico = $Query_PRONOSTICO2;
        }


    $query = Database::Connection()->prepare($Exec_Pronostico);
    $query->bindParam(":repre_cod", $repre_cod);
    $query->bindParam(":categoria", $categoria);
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $res_query = $query->fetch(PDO::FETCH_ASSOC);
            

            $pronostico = $res_query['pronostico'];

            if($categoria == 'AA')
            {
                $output = $pronostico * 4;
            }elseif ($categoria == 'A') {
                $output = $pronostico * 3;
            }elseif ($categoria == 'B') {
                $output = $pronostico * 2;
            }elseif ($categoria == 'C') {
                $output = $pronostico * 1;
            }

        }else
        {
            $output = 0;
        }
    }                                           
    return $output;
}
function pronostico_medicos_new($repre_cod)
{
    $output = null;

    $Exec_Pronostico = null;
    $user_existe_medico = user_existe_medico($repre_cod);


        $Query_PRONOSTICO1 = "SELECT 
                                    COUNT(medico_cmp) AS pronostico
                                FROM
                                    tbl_maestro_medicos
                                WHERE
                                        AND medico_representante = :repre_cod;";
        $Query_PRONOSTICO2 = "SELECT 
                                    COUNT(medico_cmp) AS pronostico
                                FROM
                                    tbl_maestro_medicos
                                WHERE
                                        medico_zona = (SELECT 
                                                representante_zonag
                                            FROM
                                                tbl_representantes
                                            WHERE
                                                representante_codigo = :repre_cod
                                                AND medico_alta_baja = 'A')
                                        AND medico_representante IS NULL;";

        if($user_existe_medico == 1)
        {
            $Exec_Pronostico = $Query_PRONOSTICO1;
        }else
        {
            $Exec_Pronostico = $Query_PRONOSTICO2;
        }


    $query = Database::Connection()->prepare($Exec_Pronostico);
    $query->bindParam(":repre_cod", $repre_cod);
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $res_query = $query->fetch(PDO::FETCH_ASSOC);

            $pronostico = $res_query['pronostico'];

            $output = $pronostico;

        }else
        {
            $output = 0;
        }
    }                                           
    return $output;
}
function search_localidad_x_ubigeo($ubg_departamento, $ubg_provincia, $ubg_distrito)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                distrito_nombre
                                            FROM
                                                ubigeo_distritos
                                            WHERE
                                                departamento_id = :ubg_departamento
                                                    AND provincia_id = :ubg_provincia
                                                    AND distrito_id = :ubg_distrito;");
    $query->bindParam(":ubg_departamento", $ubg_departamento);
    $query->bindParam(":ubg_provincia", $ubg_provincia);
    $query->bindParam(":ubg_distrito", $ubg_distrito);
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $res_query = $query->fetch(PDO::FETCH_ASSOC);
            
            $output = $res_query['distrito_nombre'];
            
        }else
        {
            $output = "empty";
        }
    }                                           
    return $output;
}
function _array_dist_x_reg($periodo, $reg_cod)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT DISTINCT
                                                    GROUP_CONCAT(DISTINCT drenaje_dist_cod
                                                        ORDER BY drenaje_dist_cod) AS distribuidora_codigo
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_periodo = :periodo
                                                        AND drenaje_zona_cod IN (SELECT 
                                                            zona_cod
                                                        FROM
                                                            tbl_maestro_detalle_zonas
                                                        WHERE
                                                            zona_cod_g_zona IN (SELECT 
                                                                    drenaje_zona_g
                                                                FROM
                                                                    tbl_drenaje_ventas
                                                                WHERE
                                                                    drenaje_periodo = :periodo
                                                                        AND drenaje_region = :reg_cod));");
    $query->bindParam(":periodo", $periodo);
    $query->bindParam(":reg_cod", $reg_cod);
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            while ($res_query = $query->fetch(PDO::FETCH_ASSOC))
            {
                $output = $res_query['distribuidora_codigo'];
            }
        }else
        {
            $output = "0";
        }
    }else
    {
        $output = errorPDO($query);
    }                                           
    return $output;
}
function medico_array($cod_med)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                medico_cmp AS med_cod,
                                                medico_nombre AS med_name,
                                                medico_especialidad AS med_esp,
                                                medico_categoria AS med_cat,
                                                medico_institucion AS med_inst,
                                                medico_localidad AS med_dist
                                            FROM
                                                tbl_maestro_medicos
                                            WHERE
                                                medico_cmp = :cod_med;");
    $query->bindParam(":cod_med", $cod_med, PDO::PARAM_INT);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $med_cod = $rQuery0['med_cod'];
            $med_name = $rQuery0['med_name'];
            $med_esp = $rQuery0['med_esp'];
            $med_cat = $rQuery0['med_cat'];
            $med_inst = $rQuery0['med_inst'];
            $med_dist = $rQuery0['med_dist'];

            $data = array(0 => $cod_med, 1 => $med_name, 2 => $med_esp, 3 => $med_cat, 4 => $med_inst, 5 => $med_dist);

            $output = $data;
        }else
        {
            $output = $data = array(0 => $cod_med, 1 => '-', 2 => '-', 3 => '-', 4 => '-', 5 => '-');
        }
    }

    return $output;
}
function _validate_medico_visitado($med_cod, $representantes, $mes, $year)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    COALESCE(COUNT(*)) AS cantidad
                                                FROM
                                                    tbl_medpro_detalle
                                                WHERE
                                                    medpro_vendedor = :representantes
                                                        AND medpro_medico_cmp = :med_cod
                                                        AND MONTH(medpro_fecha) = :mes
                                                        AND YEAR(medpro_fecha) = :year;");
    $query->bindParam(":year", $year);
    $query->bindParam(":mes", $mes);
    $query->bindParam(":med_cod", $med_cod);
    $query->bindParam(":representantes", $representantes);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $cantidad = $rQuery0['cantidad'];

            $output = $cantidad;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function distrib_name($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT distribuidora_nombre FROM distribuidora WHERE distribuidora_cod = :var LIMIT 1");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['distribuidora_nombre'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function _search_cuota_data($producto, $region_sup, $repre_zona, $periodo)
{
    $output = null;
    $WHERE = null;

    // if($producto == null)
    // {
    //     $producto = $producto;
    // }else
    // {
    //     $producto = " prod_cod = '$producto' AND ";
    // }
    if($producto == 'T')
    {
        $producto = null;
    }else
    {
        $producto = " prod_cod = '$producto' AND ";
    }
    
    $WHERE = $producto;

    $query = Database::Connection()->prepare("SELECT 
                                                COALESCE(SUM(prod_cuota_uni), 0) AS total_unidad,
                                                COALESCE(SUM(prod_cuota_val), 0) AS total_valor
                                            FROM
                                                tbl_cuota_producto
                                            WHERE
                                                $WHERE
                                                prod_region = :region_sup                                              
                                                AND prod_zona = :repre_zona
                                                AND prod_periodo = :periodo");
    $query->bindParam(":region_sup", $region_sup);
    $query->bindParam(":repre_zona", $repre_zona);
    $query->bindParam(":periodo", $periodo);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $total_unidad = $rQuery0['total_unidad'];
            $total_valor = $rQuery0['total_valor'];

            $output = $total_unidad.'||'.$total_valor;
        }else
        {
            $output = "N||N";
        }
    }else
    {
        $output = "error||".errorPDO($query);
    }
    return $output;
}
function _search_cuota_reg($producto, $region_sup, $periodo)
{
    $output = null;
    $WHERE = null;

    if($producto == 'T')
    {
        $producto = null;
    }else
    {
        $producto = " prod_cod = '$producto' AND ";
    }
    
    $WHERE = $producto;

    $query = Database::Connection()->prepare("SELECT 
                                                COALESCE(SUM(prod_cuota_uni), 0) AS total_unidad,
                                                COALESCE(SUM(prod_cuota_val), 0) AS total_valor
                                            FROM
                                                tbl_cuota_producto
                                            WHERE
                                                $WHERE
                                                prod_region = :region_sup                                              
                                                -- AND prod_zona = :repre_zona
                                                AND prod_periodo = :periodo");
    $query->bindParam(":region_sup", $region_sup);
    // $query->bindParam(":repre_zona", $repre_zona);
    $query->bindParam(":periodo", $periodo);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $total_unidad = $rQuery0['total_unidad'];
            $total_valor = $rQuery0['total_valor'];

            $output = $total_unidad;#.'||'.$total_valor;
        }else
        {
            $output = "N";
        }
    }else
    {
        $output = errorPDO($query);
    }
    return $output;
}
function _search_zona_locales($departamento, $provincia, $distrito)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT f_local_zona 
                                                FROM tbl_kushka_maestro_locales 
                                                    WHERE f_local_departamento = :departamento
                                                    AND f_local_provincia =:provincia
                                                    AND f_local_distrito = :distrito LIMIT 1");
    $query->bindParam(":departamento", $departamento, PDO::PARAM_STR);
    $query->bindParam(":provincia", $provincia, PDO::PARAM_STR);
    $query->bindParam(":distrito", $distrito, PDO::PARAM_STR);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['f_local_zona'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function _search_ubigeo($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    MAX(medico_ubigeo) AS max_ubigeo
                                                FROM
                                                    tbl_maestro_medicos
                                                WHERE
                                                    medico_localidad = :var
                                                LIMIT 1;");
    $query->bindParam(":var", $var);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['max_ubigeo'];

            $output = $data;
        }else
        {
            $output = 99;
        }
    }

    return $output;
}
function _search_ubigeo_locales($departamento, $provincia, $distrito)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT f_local_ubigeo 
                                                FROM tbl_kushka_maestro_locales 
                                                    WHERE f_local_departamento = :departamento
                                                    AND f_local_provincia =:provincia
                                                    AND f_local_distrito = :distrito LIMIT 1");
    $query->bindParam(":departamento", $departamento, PDO::PARAM_STR);
    $query->bindParam(":provincia", $provincia, PDO::PARAM_STR);
    $query->bindParam(":distrito", $distrito, PDO::PARAM_STR);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['f_local_ubigeo'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function _data_array_medico($cmp, $vendedor)
{
    $output = null;
    $user_existe_medico = user_existe_medico($vendedor);

    $SQL_GO = null;

    if($user_existe_medico == 1)
    {
        $SQL_GO = "SELECT 
                        medico_nombre,
                        medico_correlativo,
                        medico_localidad,
                        medico_especialidad,
                        medico_institucion,
                        medico_direccion,
                        medico_categoria,
                        medico_alta_baja,
                        medico_ubigeo
                    FROM
                        tbl_maestro_medicos
                    WHERE
                        medico_cmp = :cmp
                        AND medico_representante = :vendedor LIMIT 1;";
    }else
    {
        $SQL_GO = "SELECT 
                        medico_nombre,
                        medico_correlativo,
                        medico_localidad,
                        medico_especialidad,
                        medico_institucion,
                        medico_direccion,
                        medico_categoria,
                        medico_alta_baja,
                        medico_ubigeo
                    FROM
                        tbl_maestro_medicos
                    WHERE
                        medico_cmp = :cmp
                        AND medico_zona = (SELECT 
                                representante_zonag
                            FROM
                                tbl_representantes
                            WHERE
                                representante_codigo = :vendedor)
                        AND medico_representante IS NULL LIMIT 1;";
    }

    $query = Database::Connection()->prepare($SQL_GO);
    $query->bindparam(':cmp', $cmp);
    $query->bindparam(':vendedor', $vendedor);
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $query_r = $query->fetch(PDO::FETCH_ASSOC);

            $nombre = $query_r['medico_nombre'];
            $correlativo = $query_r['medico_correlativo'];
            $localidad = $query_r['medico_localidad'];
            $especialidad = $query_r['medico_especialidad'];
            $institucion = $query_r['medico_institucion'];
            $direccion = $query_r['medico_direccion'];
            $categoria = $query_r['medico_categoria'];
            $alta_baja = $query_r['medico_alta_baja'];
            $ubigeo = $query_r['medico_ubigeo'];

            $output = array('nombre' => $nombre,
                            'correlativo' => $correlativo,
                            'localidad' => $localidad,
                            'especialidad' => $especialidad,
                            'institucion' => $institucion,
                            'direccion' => $direccion,
                            'categoria' => $categoria,
                            'alta_baja' => $alta_baja,
                            'ubigeo' => $ubigeo);
        }else
        {
            $nombre = 'No Registrado';
            $correlativo = 1;
            $localidad = "-";
            $especialidad = "-";
            $institucion = "-";
            $direccion = "-";
            $categoria = "-";
            $alta_baja = "A";
            $ubigeo = 0;

            $output = array('nombre' => $nombre,
                            'correlativo' => $correlativo,
                            'localidad' => $localidad,
                            'especialidad' => $especialidad,
                            'institucion' => $institucion,
                            'direccion' => $direccion,
                            'categoria' => $categoria,
                            'alta_baja' => $alta_baja,
                            'ubigeo' => $ubigeo);
        }
    }else
    {
        $nombre = 'No Registrado';
        $correlativo = 1;
        $localidad = "-";
        $especialidad = "-";
        $institucion = "-";
        $direccion = "-";
        $categoria = "-";
        $alta_baja = "A";
        $ubigeo = 0;

        $output = array('nombre' => $nombre,
                            'correlativo' => $correlativo,
                            'localidad' => $localidad,
                            'especialidad' => $especialidad,
                            'institucion' => $institucion,
                            'direccion' => $direccion,
                            'categoria' => $categoria,
                            'alta_baja' => $alta_baja,
                            'ubigeo' => $ubigeo);
    }
    return $output;
}
function validate_cmp_x_cat($cmp, $fecha_mm, $user_session)
{
    $output = null;

    $ingreso = true;

    $msj = null;

    $fecha_ex = explode("-", $fecha_mm);
    $mes = (int)$fecha_ex[1];
    $year = (int)$fecha_ex[0];

    $query = Database::Connection()->prepare("SELECT 
                                                    GROUP_CONCAT(DISTINCT DAY(medpro_fecha)
                                                    ORDER BY medpro_fecha ASC
                                                    SEPARATOR ' - ') AS fechas,
                                                    medpro_medico_categoria AS categoria,
                                                    COUNT(DISTINCT medpro_fecha) AS cantidad
                                                FROM
                                                    tbl_medpro_detalle
                                                WHERE
                                                    medpro_vendedor = :user_session
                                                        AND YEAR(medpro_fecha) = :year
                                                        AND MONTH(medpro_fecha) = :mes
                                                        AND medpro_medico_cmp = :cmp
                                                        AND medpro_estado = 1;");
    $query->bindParam(":year", $year);    
    $query->bindParam(":mes", $mes); 
    $query->bindParam(":cmp", $cmp); 
    $query->bindParam(":user_session", $user_session); 
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $fechas = $rQuery0['fechas'];
            $categoria = trim($rQuery0['categoria']);
            $cantidad = $rQuery0['cantidad'];

            if(strpos($fechas, '-') !== FALSE)
            {
                $sql_fecha = ' Los dias '. $fechas;
            }else
            {
                $sql_fecha = ' El dia '. $fechas;
            }
            
            if($categoria == 'C' && $cantidad >= 1)
            {
                $ingreso = false;
                $msj = "Los medicos de categoria <b>$categoria</b>, solo pueden ser visitados 1 vez por mes, <br> <b>Registrado: $sql_fecha </b>";
            }else if($categoria == 'B' && $cantidad >= 2)
            {
                $ingreso = false;
                $msj = "Los medicos de categoria <b>$categoria</b>, solo pueden ser visitados 2 vez por mes, <br> <b>Registrado: $sql_fecha </b>";
            }else if($categoria == 'A' && $cantidad >= 3)
            {
                $ingreso = false;
                $msj = "Los medicos de categoria <b>$categoria</b>, solo pueden ser visitados 3 vez por mes, <br> <b>Registrado: $sql_fecha </b>";
            }else if($categoria == 'AA' && $cantidad >= 4)
            {
                $ingreso = false;
                $msj = "Los medicos de categoria <b>$categoria</b>, solo pueden ser visitados 4 vez por mes, <br> <b>Registrado: $sql_fecha </b>";
            }else
            {
                $ingreso = true;
                $msj = 'Se ingresa';
            }
        }else
        {
            $ingreso = true;
            $msj = 'Se ingresa';
        }
    }
    return $output = array('ingreso' => $ingreso,
                            'msj' => $msj);
    // return $output = array('cat'=> $categoria , 'cantidad' => $cantidad , 'ingreso' => $ingreso,
    //                         'msj' => $msj);

}
function validate_mm_cmp_today($cmp, $fecha, $vendedor)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    COALESCE(COUNT(*), 0) AS cantidad
                                                FROM
                                                    tbl_medpro_detalle
                                                WHERE
                                                    medpro_fecha = :fecha
                                                        AND medpro_vendedor = :vendedor
                                                        AND medpro_medico_cmp = :cmp
                                                        AND medpro_estado NOT IN (4,5)");
    $query->bindParam(":cmp", $cmp);
    $query->bindParam(":fecha", $fecha);
    $query->bindParam(":vendedor", $vendedor);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $cantidad = $rQuery0['cantidad'];

            $output = $cantidad;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function validate_mm_cmp_today_punto($med_cod, $fecha_mm, $vendedor, $estado)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    COALESCE(COUNT(*), 0) AS cantidad
                                                FROM
                                                    tbl_medpro_detalle
                                                WHERE
                                                    medpro_fecha = :fecha
                                                        AND medpro_vendedor = :vendedor
                                                        AND medpro_medico_cmp = :cmp
                                                        AND medpro_estado IN (:estado)");
    $query->bindParam(":cmp", $med_cod);
    $query->bindParam(":fecha", $fecha_mm);
    $query->bindParam(":vendedor", $vendedor);
    $query->bindParam(":estado", $estado);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $cantidad = $rQuery0['cantidad'];

            $output = $cantidad;
        }else
        {
            $output = 0;
        }
    }
    return $output;
}
function sum_total_regiones($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT SUM(rv_valor) AS suma FROM reporte_drenaje_ventas WHERE rv_periodo = :var AND rv_tipo = 1;");
    $query->bindParam(":var", $var, PDO::PARAM_INT);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['suma'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function sum_total_cuotas_regiones($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT SUM(rv_cuota) AS suma FROM reporte_drenaje_ventas WHERE rv_periodo = :var AND rv_tipo = 1;");
    $query->bindParam(":var", $var, PDO::PARAM_INT);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['suma'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function sum_cuotas_x_regiones($region, $periodo)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT SUM(rv_cuota) AS suma 
                                                FROM reporte_drenaje_ventas 
                                                WHERE rv_periodo = :periodo 
                                                AND rv_region_2 = :region; ");#AND rv_tipo = 1;
    $query->bindParam(":periodo", $periodo, PDO::PARAM_INT);    
    $query->bindParam(":region", $region, PDO::PARAM_INT);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['suma'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function _sum_prod_entegados($representantes, $cod_prod, $mes, $year)
{
    $output = null;

    $consulta = Database::Connection()->prepare("SELECT 
                                                    COALESCE(SUM(medpro_cantidad), 0) AS entregados
                                                FROM
                                                    tbl_medpro_detalle
                                                WHERE
                                                    YEAR(medpro_fecha) = :year
                                                        AND MONTH(medpro_fecha) = :mes
                                                        AND medpro_vendedor = :representantes
                                                        AND medpro_producto = :cod_prod");
    $consulta->bindParam(':year', $year, PDO::PARAM_INT);
    $consulta->bindParam(':mes', $mes, PDO::PARAM_INT);
    $consulta->bindParam(':representantes', $representantes, PDO::PARAM_INT);
    $consulta->bindParam(':cod_prod', $cod_prod, PDO::PARAM_INT);
    if($consulta->execute())
    {
        if($consulta->rowCount() > 0)
        {
            $rQuery = $consulta->fetch(PDO::FETCH_ASSOC);
            $output = (int)$rQuery['entregados'];
        }else
        {
            $output = 0;
        }
    }else
    {
        $output = 0;
    }

    $consulta = null;

    return $output;
}
function user_existe_medico($var)
{
    $output = null;
            $consulta = Database::Connection()->prepare("SELECT 
                                                            medico_id
                                                        FROM
                                                            tbl_maestro_medicos
                                                        WHERE
                                                            medico_representante = :var
                                                        LIMIT 1;");
            $consulta->bindParam(":var", $var);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $output = 1;
                }else
                {
                    $output = 0;
                }
            }else
            {
                $output = 0;
            }
        return $output;
}
function search_repre_exist($var)
{
    $output = false;
            $consulta = Database::Connection()->prepare("SELECT 
                                                            representante_nombre
                                                        FROM
                                                            tbl_representantes
                                                        WHERE
                                                            representante_codigo = :var LIMIT 1");
            $consulta->bindParam(":var", $var, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $output = true;
                }
            }
        return $output;
}
function search_repre_name($var)
{
    $output = null;
            $consulta = Database::Connection()->prepare("SELECT 
                                                            representante_nombre
                                                        FROM
                                                            tbl_representantes
                                                        WHERE
                                                            representante_codigo = :var LIMIT 1");
            $consulta->bindParam(":var", $var, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $rconsulta = $consulta->fetch(PDO::FETCH_ASSOC);
                    $output = $rconsulta['representante_nombre'];
                }else
                {
                    $output = "NoName";
                }
            }else
            {
                $output = 'NoName';
            }
        return $output;
}
function search_reg_x_zona_g($var)
{
    $consulta = Database::Connection()->prepare("SELECT 
                                                    zonag_region
                                                FROM
                                                    tbl_maestro_zonas
                                                WHERE
                                                    zonag_codigo = :var
                                                LIMIT 0 , 1");
    $consulta->bindParam(":var", $var);
    if($consulta->execute())
    {
        if($consulta->rowCount() > 0)
        {
            $rconsulta = $consulta->fetch(PDO::FETCH_ASSOC);
            $output = $rconsulta['zonag_region'];
        }else
        {
            $output = "9999";
        }
    }
    return $output;                                            
}
function search_name_med($var1, $var2)
{
        $output = null;
            $consulta = Database::Connection()->prepare("SELECT 
                                                        medico_cmp, medico_correlativo, medico_nombre
                                                    FROM
                                                        tbl_maestro_medicos
                                                    WHERE
                                                        medico_cmp = :var1
                                                            AND medico_zona = (SELECT 
                                                                representante_zonag
                                                            FROM
                                                                tbl_representantes
                                                            WHERE
                                                                representante_codigo = :var2) LIMIT 1;");
            $consulta->bindParam(":var1", $var1, PDO::PARAM_INT);
            $consulta->bindParam(":var2", $var2, PDO::PARAM_INT);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $rconsulta = $consulta->fetch(PDO::FETCH_ASSOC);
                    $output = $rconsulta['medico_nombre'];
                }else
                {
                    $output = "Sin registrar";
                }
            }
        return $output;
}
function search_correlativo($var1)
{
        $output = null;
            $consulta = Database::Connection()->prepare("SELECT 
                                                            MAX(medico_correlativo) as maximo
                                                            FROM
                                                                tbl_maestro_medicos
                                                            WHERE
                                                                medico_cmp = :var1 LIMIT 1;");
            $consulta->bindParam(":var1", $var1);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $rconsulta = $consulta->fetch(PDO::FETCH_ASSOC);
                    $output = $rconsulta['maximo'];
                }else
                {
                    $output = 1;
                }
            }else
            {
                $output = 0;
            }
        return $output;
}

function search_data_med($var1, $var2)
{
        $output = null;
            $consulta = Database::Connection()->prepare("SELECT 
                                                            MAX(medico_id) AS medico_id
                                                        FROM
                                                            tbl_maestro_medicos
                                                        WHERE
                                                            medico_cmp = :var1
                                                            AND medico_representante = :var2;");
            $consulta->bindParam(":var1", $var1);
            $consulta->bindParam(":var2", $var2);
            if($consulta->execute())
            {
                if($consulta->rowCount() > 0)
                {
                    $rconsulta = $consulta->fetch(PDO::FETCH_ASSOC);

                    $medico_id = $rconsulta['medico_id'];

                    $consulta2 = Database::Connection()->prepare("SELECT 
                                                            medico_correlativo,
                                                            medico_zona,
                                                            medico_categoria,
                                                            medico_localidad,
                                                            medico_alta_baja,
                                                            medico_turno,
                                                            medico_ubigeo,
                                                            COALESCE(medico_representante, 'vacio') AS medico_representante
                                                        FROM
                                                            tbl_maestro_medicos
                                                        WHERE
                                                            medico_id = :medico_id");
                    $consulta2->bindParam(":medico_id", $medico_id);
                    if($consulta2->execute())
                    {
                        if($consulta2->rowCount() > 0)
                        {
                            $rconsulta2 = $consulta2->fetch(PDO::FETCH_ASSOC);

                            $medico_correlativo = $rconsulta2['medico_correlativo'];
                            $medico_zona = $rconsulta2['medico_zona'];
                            $medico_categoria = $rconsulta2['medico_categoria'];
                            $medico_localidad = $rconsulta2['medico_localidad'];
                            $medico_alta_baja = $rconsulta2['medico_alta_baja'];
                            $medico_turno = $rconsulta2['medico_turno'];
                            $medico_ubigeo = $rconsulta2['medico_ubigeo'];
                            $medico_representante = $rconsulta2['medico_representante'];

                            $output = array('id' => $medico_id,
                                            'correlativo' => $medico_correlativo,
                                            'zona' => $medico_zona,
                                            'categoria' => $medico_categoria,
                                            'localidad' => $medico_localidad,
                                            'alta_baja' => $medico_alta_baja,
                                            'turno' => $medico_turno,
                                            'ubigeo' => $medico_ubigeo,
                                            'representante' => $medico_representante,
                                            'existe' => 1);
                        }else
                        {
                            $output = array('id' => '0',
                                            'correlativo' => '0',
                                            'zona' => '0',
                                            'categoria' => 'n',
                                            'localidad' => 'no registrado',
                                            'alta_baja' => 'B',
                                            'turno' => 'PM',
                                            'ubigeo' => '0',
                                            'representante' => '0',
                                            'existe' => 0);
                        }
                    }else
                    {
                        $output = array('id' => '0',
                                            'correlativo' => '0',
                                            'zona' => '0',
                                            'categoria' => 'n',
                                            'localidad' => 'no registrado',
                                            'alta_baja' => 'B',
                                            'turno' => 'PM',
                                            'ubigeo' => '0',
                                            'representante' => '0',
                                            'existe' => 0);
                    }
                }else
                {
                    $output = array('id' => '0',
                                    'correlativo' => '0',
                                    'zona' => '0',
                                    'categoria' => 'n',
                                    'localidad' => 'no registrado',
                                    'alta_baja' => 'B',
                                    'turno' => 'PM',
                                    'ubigeo' => '0',
                                    'representante' => '0',
                                    'existe' => 0);
                }
            }else
            {
                    $output = array('id' => '0',
                                    'correlativo' => '0',
                                    'zona' => '0',
                                    'categoria' => 'n',
                                    'localidad' => 'no registrado',
                                    'alta_baja' => 'B',
                                    'turno' => 'PM',
                                    'ubigeo' => '0',
                                    'representante' => '0',
                                    'existe' => 0);
            }
        return $output;
}
function procentaje_calcular($total, $alcance)
{
    $output = 0;
    if($alcance != 0 && $total != 0)
    {
        $output = ($alcance * 100)/$total;
    }else 
    {
        $output = 0;
    }
    // $output = ($alcance * 100)/$total;
    
    return $output;
}
function cliente_ruc_name($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT cliente_ruc, cliente_nombre 
                                                FROM distribuidora 
                                                WHERE cliente_cod = :var LIMIT 1");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['cliente_ruc']. '~~' . $rQuery0['cliente_nombre'];

            $output = $data;
        }else
        {
            $output = "0~~noname";
        }
    }

    return $output;
}
function _stock_actual_x_prod($user, $prod_cod ,$periodo)
{
        $output = null;

        $query = Database::Connection()->prepare("SELECT 
                                                        stock_cantidad_tmp
                                                    FROM
                                                        tbl_stock
                                                    WHERE
                                                        stock_periodo = :periodo
                                                            AND stock_codigo_producto = :prod_cod
                                                            AND stock_codigo_vendedor = :user LIMIT 1");
        $query->bindParam(":periodo", $periodo);
        $query->bindParam(":prod_cod", $prod_cod);
        $query->bindParam(":user", $user);
        
        if($query->execute())
        {
            if($query->rowCount() > 0)
            {
                $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
                
                $data = $rQuery0['stock_cantidad_tmp'];
    
                $output = $data;
            }else
            {
                $output = "0";
            }
        }
    
        return $output;
}
function producto_name($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT producto_cod, producto_nombre 
                                                FROM producto 
                                                WHERE producto_cod = :var LIMIT 1");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['producto_nombre'];

            $output = $data;
        }else
        {
            $output = "noname";
        }
    }

    return $output;
}
function sup_region_name($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT region_supervisor 
                                                FROM tbl_maestro_regiones 
                                                WHERE region_codigo2 = :var LIMIT 1");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['region_supervisor'];

            $output = $data;
        }else
        {
            $output = "noname";
        }
    }

    return $output;
}
function sup_region_name_2($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT region_descripcion1, region_supervisor 
                                                FROM tbl_maestro_regiones 
                                                WHERE region_codigo2 = :var LIMIT 1");
    $query->bindParam(":var", $var, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            $data0 = $rQuery0['region_descripcion1'];
            $data = $rQuery0['region_supervisor'];

            $output = $data. ' - ' . $data0;
        }else
        {
            $output = "noname";
        }
    }

    return $output;
}
function sum_x_regiones($region, $periodo)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT SUM(rv_valor) AS suma 
                                                FROM reporte_drenaje_ventas 
                                                WHERE rv_periodo = :periodo 
                                                AND rv_region_2 = :region");
    $query->bindParam(":periodo", $periodo, PDO::PARAM_INT);    
    $query->bindParam(":region", $region, PDO::PARAM_INT);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            
            $data = $rQuery0['suma'];

            $output = $data;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function array_colores($i)
{
    $color = array(1 => '#07617D', 2 => '#76B39D', 3 => '#FDB44B', 4 => '#2796CB', 5 => '#5FCC9C', 
    6 => '#76A665', 7 => '#7BCECC', 8 => '#588D9C', 9 => '#F6490D', 10 => '#404969', 11 => 'Noviembre', 12 => 'Diciembre');

    return  $color[$i];
}
function _get_menu_list($user)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    menu_items
                                                FROM
                                                    tbl_maestro_menus
                                                WHERE
                                                    menu_cod = (SELECT 
                                                            u_detalle_menu
                                                        FROM
                                                            tbl_usuario_detalle
                                                        WHERE
                                                        u_detalle_usuario = :user);");
    $query->bindParam(":user", $user, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            $menu_items = $rQuery0['menu_items'];

            $output = $menu_items;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function usuario_nombre()
{
    $output = null;
    $session_user = $_SESSION['user_user'];

    $query = Database::Connection()->prepare("SELECT 
                                                    u_detalle_nombre
                                                FROM
                                                    tbl_usuario_detalle
                                                WHERE
                                                    u_detalle_usuario = :user;");
    $query->bindParam(":user", $session_user, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);
            $menu_items = $rQuery0['u_detalle_nombre'];

            $output = $menu_items;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function tipe_view_modules($v)
{
    $output = null;
    $session_user = $_SESSION['user_user'];

    $query = Database::Connection()->prepare("SELECT 
                                                    u_detalle_codigo AS codigo,
                                                    view_portafolio AS portafolio,
                                                    view_region AS region,
                                                    view_region_visita AS region_visita,
                                                    view_tipo AS tipo
                                                FROM
                                                    tbl_view_data
                                                        INNER JOIN
                                                    tbl_usuario_detalle ON u_detalle_usuario = view_usuario
                                                WHERE
                                                    view_usuario = :user;");
    $query->bindParam(":user", $session_user, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $array = array(0 => $rQuery0['codigo'], 1 => $rQuery0['portafolio'],  2 => $rQuery0['region'], 3 => $rQuery0['region_visita'], 4 => $rQuery0['tipo']);

            $output = $array[$v];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function info_usuario($i)
{
    $output = null;
    $session_user = $_SESSION['user_user'];

    $query = Database::Connection()->prepare("SELECT 
                                                    u_detalle_codigo AS codigo,
                                                    usuario_usuario AS usuario,
                                                    u_detalle_nombre AS nombre,
                                                    usuario_root AS tipo_user,
                                                    view_region AS region,
                                                    view_region_visita AS region_visita,
                                                    view_portafolio AS portafolio,
                                                    u_detalle_menu AS menu,
                                                    usuario_estado AS estado,
                                                    view_tipo AS tipo
                                                FROM
                                                    tbl_usuarios
                                                        INNER JOIN
                                                    tbl_view_data ON view_usuario = usuario_usuario
                                                        INNER JOIN
                                                    tbl_usuario_detalle ON u_detalle_usuario = usuario_usuario
                                                    WHERE usuario_usuario = :user
                                                GROUP BY 2;");
    $query->bindParam(":user", $session_user, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $array = array('codigo' => $rQuery0['codigo'], 
                            'usuario' => $rQuery0['usuario'],  
                            'nombre' => $rQuery0['nombre'], 
                            'tipo_user' => $rQuery0['tipo_user'], 
                            'region_visita' => $rQuery0['region_visita'], 
                            'portafolio' => $rQuery0['portafolio'], 
                            'menu' => $rQuery0['menu'], 
                            'estado' => $rQuery0['estado'],
                            '' => 'empty',
                            'region' => $rQuery0['region']);

            $output = $array[$i];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function info_usuario2()
{
    $output = null;
    $session_user = $_SESSION['user_user'];

    $query = Database::Connection()->prepare("SELECT 
                                                    u_detalle_codigo AS codigo,
                                                    usuario_usuario AS usuario,
                                                    u_detalle_nombre AS nombre,
                                                    usuario_root AS tipo_user,
                                                    view_region AS region,
                                                    view_region_visita AS region_visita,
                                                    view_portafolio AS portafolio,
                                                    u_detalle_menu AS menu,
                                                    usuario_estado AS estado,
                                                    view_tipo AS tipo
                                                FROM
                                                    tbl_usuarios
                                                        INNER JOIN
                                                    tbl_view_data ON view_usuario = usuario_usuario
                                                        INNER JOIN
                                                    tbl_usuario_detalle ON u_detalle_usuario = usuario_usuario
                                                    WHERE usuario_usuario = :user
                                                GROUP BY 2;");
    $query->bindParam(":user", $session_user, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $array = array('codigo' => $rQuery0['codigo'], 
                            'usuario' => $rQuery0['usuario'],  
                            'nombre' => $rQuery0['nombre'], 
                            'tipo_user' => $rQuery0['tipo_user'], 
                            'region_visita' => $rQuery0['region_visita'], 
                            'portafolio' => $rQuery0['portafolio'], 
                            'menu' => $rQuery0['menu'], 
                            'estado' => $rQuery0['estado'],
                            '' => 'empty',
                            'region' => $rQuery0['region']);

            $output = $array;
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function name_user_()
{
    $name_user = null;
    $nombre = explode(" ", usuario_nombre());
    
    if(count($nombre) == 4)
    {
        $name_user = ucfirst(strtolower($nombre[0])) ." " . ucfirst(substr($nombre[2], 0, 1)).".";
    }else if(count($nombre) == 3)
    {
        $name_user = ucfirst(strtolower($nombre[0])) ." " . ucfirst(substr($nombre[1], 0, 1)).".";
    }else if(count($nombre) == 2)
    {
        $name_user = ucfirst(strtolower($nombre[0])) ." " . ucfirst(substr($nombre[1], 0, 1)).".";
    }else
    {
        $name_user = ucfirst(info_usuario('nombre'));
    }
    return $name_user;
}

// function _perfil($campo)
// {
//     $campo = 'perfil_'  .  $campo;

//     $Consulta = Database::Connection()->prepare("SELECT $campo FROM perfiles WHERE perfil_codigo = :perfil_codigo");
//     $Consulta->bindParam(':perfil_codigo', $_SESSION['perfil_codigo'], PDO::PARAM_INT);

//     if ($Consulta->execute() == TRUE)
//     {
//         if ($Consulta->rowCount() > 0)
//         {
//             $QueryResult = $Consulta->fetch(PDO::FETCH_ASSOC);
//             return $QueryResult["$campo"];
//         }
//     }else
//     {
//         return errorPDO($Consulta);
//     }
// }
function session_portafolio()
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    view_portafolio
                                                FROM
                                                    tbl_view_data
                                                WHERE
                                                    view_usuario = :user;");
    $query->bindParam(":user", $_SESSION['user_user'], PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $output = $rQuery0['view_portafolio'];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function session_region()
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    view_region
                                                FROM
                                                    tbl_view_data
                                                WHERE
                                                    view_usuario = :user;");
    $query->bindParam(":user", $_SESSION['user_user'], PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $output = $rQuery0['view_region'];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function session_codigo_vendedor()
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    u_detalle_codigo
                                                FROM
                                                    tbl_usuario_detalle
                                                WHERE
                                                    u_detalle_usuario = :user;");
    $query->bindParam(":user", $_SESSION['user_user'], PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $output = $rQuery0['u_detalle_codigo'];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function session_usuario_cargo()
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    usuario_root
                                                FROM
                                                    tbl_usuarios
                                                WHERE
                                                    usuario_usuario = :user;");
    $query->bindParam(":user", $_SESSION['user_user'], PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $output = $rQuery0['usuario_root'];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function cuota_representante_zona_region($periodo, $region, $vendedor)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    cuota_monto
                                                FROM
                                                    tbl_cuotas
                                                WHERE
                                                    cuota_periodo = :periodo
                                                        -- AND cuota_portafolio = :portafolio
                                                        AND cuota_codigo_vendedor = :vendedor
                                                        AND cuota_region = :region;");
    $query->bindParam(":periodo", $periodo, PDO::PARAM_INT);
    // $query->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);
    $query->bindParam(":vendedor", $vendedor, PDO::PARAM_INT);
    $query->bindParam(":region", $region, PDO::PARAM_INT);
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $output = $rQuery0['cuota_monto'];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function _menu($campo)
{
    $campo = 'menu_'  .  $campo;

    $Consulta = Database::Connection()->prepare("SELECT $campo FROM user_menu WHERE menu_user = :menu_user");
    $Consulta->bindParam(':menu_user', $_SESSION['user_user'], PDO::PARAM_STR);

    if ($Consulta->execute() == TRUE)
    {
        if ($Consulta->rowCount() > 0)
        {
            $QueryResult = $Consulta->fetch(PDO::FETCH_ASSOC);
            return $QueryResult["$campo"];
        }
    }else
    {
        return errorPDO($Consulta);
    }
}
function _short_name_($var)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                    distribuidora_small_name
                                                FROM
                                                    tbl_distribuidoras
                                                WHERE
                                                distribuidora_codigo = :var;");
    $query->bindParam(":var", $var, PDO::PARAM_INT);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $output = $rQuery0['distribuidora_small_name'];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}
function data_cliente_medico($cod)
{
    $SQL = null;
    $name = null;
    $local = null;
    $direccion = null;
    $localidad = null;

    $output = null;

    if(is_numeric($cod))
    {
        $SQL = "SELECT medico_nombre, medico_institucion, medico_direccion, medico_localidad FROM tbl_maestro_medicos WHERE medico_cmp = '$cod'";
        
        $name = "medico_nombre";
        $local = "medico_institucion";
        $direccion = "medico_direccion";
        $localidad = "medico_localidad";
    }else
    {
        #$SQL = "SELECT clientes_nombre, clientes_nombre_comercial, clientes_direccion, clientes_distrito FROM fichero_clientes WHERE clientes_codigo = '$cod'";
        $SQL = "SELECT medico_nombre, medico_institucion, medico_direccion, medico_localidad FROM tbl_maestro_medicos WHERE medico_cmp = '$cod'";
        $name = "clientes_nombre";
        $local = "clientes_nombre_comercial";
        $direccion = "clientes_direccion";
        $localidad = "clientes_distrito";        
    }

    $Exc = Database::Connection()->prepare($SQL);
    if($Exc->execute())
    {
        if($Exc->rowCount() > 0)
        {
            $rExc = $Exc->fetch(PDO::FETCH_ASSOC);

            $output = $rExc[$name]. ' [ ' . $rExc[$local] . ' ]'.'~~'.$rExc[$direccion]. ' - '. $rExc[$localidad];
        }else
        {
            $output = '';
        }
    }else
    {
        $output = errorPDO($Exc).'~~'.errorPDO($Exc);#'Error~~Error';
    }

    return $output;
}
function _last_generado_ventas()
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT 
                                                MAX(rv_registro) AS ahora
                                            FROM
                                                reporte_drenaje_ventas;");  
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $ahora = $rQuery0['ahora'];

            $explo0 = explode(" ", $ahora);
            
            $hora_ = explode(':', $explo0[1]);

            $output = fecha_db_to_view($explo0[0]) . ' &nbsp;-&nbsp; '.$hora_[0].':'.$hora_[1];

        }else
        {
            $output = '-';
        }
    }

    return $output;
}
function medico_visitado_x_categoria($user_session, $cmp, $categoria, $year, $month)
{
    $mensaje = null;
    $mostrar = 0;
    $total_visita = 0;

    $Query1 = "SELECT 
                COALESCE(COUNT(DISTINCT medpro_fecha), 0) AS cantidad,
                medpro_medico_nombre AS nombre,
                GROUP_CONCAT(DISTINCT DAY(medpro_fecha)
                    ORDER BY medpro_fecha
                    SEPARATOR '-') AS visitados
                FROM
                    tbl_medpro_detalle
                WHERE
                    YEAR(medpro_fecha) = :year
                        AND MONTH(medpro_fecha) = :month
                        AND medpro_vendedor = :user_session
                        -- AND medpro_medico_categoria = :categoria
                        AND medpro_medico_cmp = :cmp;";

    $execQuery = Database::Connection()->prepare($Query1);
    $execQuery->bindParam(':user_session', $user_session);
    $execQuery->bindParam(':cmp', $cmp);
    $execQuery->bindParam(':categoria', $categoria);
    $execQuery->bindParam(':year', $year);
    $execQuery->bindParam(':month', $month);
    if($execQuery->execute())
    {
        if($execQuery->rowCount() > 0)
        {
            $rQuery0 = $execQuery->fetch(PDO::FETCH_ASSOC);

            $cantidad = $rQuery0['cantidad'];
            $dias_visitado = $rQuery0['visitados'];
            $nombre_ = $rQuery0['nombre'];
            $cant_visitas = null;

            if(strpos($dias_visitado, '-') !== FALSE)
            {
                $explode_dias_v = explode('-', $dias_visitado);
                for ($i = 0; $i <= count($explode_dias_v) - 1; $i++)
                { 
                    $cant_visitas .= '&nbsp;<a class="btn btn-sm btn-primary waves-effect waves-light" 
                    style="line-height:10px;font-size:1em;height:8px !important;text-align:left !important;max-width:6px !important;cursor:pointer;color:#005585;" 
                    onclick="return cobertura_fecha_productos('."'".$cmp."'".','."'".$explode_dias_v[$i]."'".','."'".$month."'".','."'".$year."'".','."'".$user_session."'".','."'".$nombre_."'".');"><b>'.$explode_dias_v[$i].'</b></a>  -';                     
                }
            }else
            {
                $cant_visitas = '<a class="btn btn-sm btn-primary waves-effect waves-light" 
                style="line-height:10px;font-size:1em;height:8px !important;text-align:left !important;max-width:6px !important;cursor:pointer;color:#005585;" 
                onclick="return cobertura_fecha_productos('."'".$cmp."'".','."'".$dias_visitado."'".','."'".$month."'".','."'".$year."'".','."'".$user_session."'".','."'".$nombre_."'".');"><b>'.$dias_visitado.'</b></a>';
                
            }

            $cant_visitas = trim($cant_visitas,'-');



            if($categoria == 'C' && $cantidad < 1)
            {   
                $output = '1|~|<b>No visitado.</b>';
            }elseif ($categoria == 'B' && $cantidad < 2)
            { 
                $total_visita = 2;
                if($cantidad == 0)
                {
                    $output = '1|~|<b>No visitado.</b>';
                }else
                {
                    $diff = @$total_visita - $cantidad;
                    $output = '1|~|<b>Visitado/Día: ' .$cant_visitas.', Por visitar: </b><b style="color:red;">'.$diff.'</b>';
                }
            }elseif ($categoria == 'A' && $cantidad < 3)
            {
                $total_visita = 3;
                if($cantidad == 0)
                {
                    $output = '1|~|<b>No visitado.</b>';
                }else
                {
                    $diff = @$total_visita - $cantidad;
                    $output = '1|~|<b>Visitado/Día: ' .$cant_visitas.', Por visitar:</b> <b style="color:red;">'.$diff.'</b>';
                }
            }elseif ($categoria == 'AA' && $cantidad < 4)
            {
                $total_visita = 4;
                if($cantidad == 0)
                {
                    $output = '1|~|<b>No visitado.</b>';
                }else
                {
                    $diff = @$total_visita - $cantidad;
                    $output = '1|~|<b>Visitado/Día: ' .$cant_visitas.', Por visitar: </b><b style="color:red;">'.$diff.'</b>';
                }
            }else
            {
                $output = '0|~|0';
            }
        }else
        {
            $output = '0|~|0';
        }
    }

    return $output;
}
function _zona_to_vendedor($var, $periodo)
{
    $output = null;

    $query = Database::Connection()->prepare("SELECT DISTINCT
                                                    drenaje_repre_cod AS repre_cod
                                                FROM
                                                    tbl_drenaje_ventas
                                                WHERE
                                                    drenaje_zona_g = :var
                                                        AND drenaje_periodo = :periodo;");
    $query->bindParam(":var", $var);    
    $query->bindParam(":periodo", $periodo);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $output = $rQuery0['repre_cod'];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}

function __modules_permissions__($module)
{
    $user_session = info_usuario('codigo');
    $today = date('y-m-d');

    $output = "FALSE~NULL~NULL";

    $query = Database::Connection()->prepare("SELECT 
                                                    modulo, usuarios, inicio, fin, estado
                                                FROM
                                                    permisos
                                                WHERE
                                                    modulo = :module ORDER BY registro DESC LIMIT 0,1");
    $query->bindParam(":module", $module);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $r_sql1 = $query->fetch(PDO::FETCH_ASSOC);
            #$modulo = $r_sql1['modulo'];
            $estado = $r_sql1['estado'];
            $usuarios = $r_sql1['usuarios'];
            $inicio = $r_sql1['inicio'];
            $fin = $r_sql1['fin'];
            
            if($estado == 1)
            {
                if($usuarios == 'all')
                {
                    if(check_in_range_date($inicio, $fin, $today))
                    {
                        $output = "TRUE~".$inicio."~".$fin;
                    }
                }else
                {
                    $_split_usuarios = explode(",", $usuarios);

                    if(in_array($user_session, $_split_usuarios) && check_in_range_date($inicio, $fin, $today))
                    {
                        $output = "TRUE~".$inicio."~".$fin;
                    }
                }
            }
        }
    }

    return $output;
}