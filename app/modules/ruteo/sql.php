<?php 
#Code
function _sql_insertar_ruteo_()
{
  $sql = "INSERT INTO ruteo(vendedor, cliente, cliente_desc, cod_int, direccion, distrito, objetivo, importe, fecha, hora, observaciones, tipo)
          VALUES(:user_session, :codigos, :clientes, :cod_int, :direccion, :distrito, :objetivos, :importes, :fecha, :horas, :observaciones, :tipos)";
  return $sql;
}
function _sql_buscar_ruteo_()
{
    $sql = "SELECT 
                cliente AS codigo,
                cliente_desc AS cliente,
                direccion,
                distrito,
                objetivo,
                hora,
                observaciones,
                importe,
                tipo
            FROM
                ruteo
            WHERE
                vendedor = :vendedor AND fecha = :fecha
            ORDER BY hora ASC";
    return $sql;
}
function _sql_eliminar_ruteo_()
{
    $sql = "DELETE FROM ruteo WHERE vendedor = :vendedor AND fecha = :fecha";
    return $sql;
}
function _sql_buscar_medicos($user_session, $in = 0)
{
    $cmp = NULL;
    $goSQL = NULL;
    $user_existe_medico = user_existe_medico($user_session);
    $sup = false;
    $WHERE = NULL;
    $WHERE1 = NULL;

    if(strlen($in) != 0)
    {
        $goSQL = "SELECT 
                            medico_cmp AS cmp,
                            medico_correlativo AS correlativo,
                            medico_nombre AS nombre,
                            medico_categoria AS categoria
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_cmp = :cmp LIMIT 0,1";
    }else
    {
        switch ($user_session)
        {
            case '520':
                $sup = true;
                break;
            case '417':
                $sup = true;
                break;
            case '391':
                $sup = true;
                break;
            case '629':
                $sup = true;
                break;
            case '757':
                $sup = true;
                break;
            case '650':
                $sup = true;
                break;
            default:
                $sup = false;
                break;
        }
    
        if($sup == true)
        {
            $goSQL = "SELECT 
                            medico_cmp AS cmp,
                            medico_correlativo AS correlativo,
                            medico_nombre AS nombre,
                            medico_categoria AS categoria
                        FROM
                            tbl_maestro_medicos
                        WHERE
                            medico_especialidad != ''
                                AND medico_categoria != ''
                                AND medico_supervisor = :user_session
                                AND medico_representante IS NOT NULL;";
    
        }else
        {        
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
        }  
    }
    
    return $goSQL;
}
function _sql_zonas_supervisores()
{
    $sql = "SELECT 
                GROUP_CONCAT(DISTINCT zona
                    SEPARATOR ',') AS zonas
            FROM
                maestro_clientes
            WHERE
                zona IN (SELECT 
                        zona_cod AS cod_zonita
                    FROM
                        tbl_maestro_detalle_zonas
                            INNER JOIN
                        tbl_maestro_zonas ON zonag_codigo = zona_cod_g_zona
                            INNER JOIN
                        tbl_maestro_regiones ON region_codigo2 = zonag_region
                    WHERE
                        zonag_region = :region)";
    return $sql;    
}
function _sql_buscar_zonas()
{
    $sql = "SELECT codigo, portafolio, region, zona, zona_vista, cargo FROM zona_venta_visita WHERE codigo = :codigo;";
    return $sql;
}
function _sql_buscar_clientes($in = 0)
{
    $condiciones = NULL;

    if(strlen($in) != 0)
    {
        if(is_numeric($in))
        {
            $condiciones = " ruc = :in LIMIT 0,1 ";
        }else
        {
            $condiciones = " codigo = :in LIMIT 0,1 ";
        }
    }else
    {
        $condiciones = " zona IN (:zonas) ";
    }


    $sql = "SELECT 
                codigo,
                ruc,
                nombre_comercial,
                razon_social,
                direccion,
                departamento,
                provincia,
                distrito,
                zona,
                zona_desc
            FROM
                maestro_clientes
            WHERE
                $condiciones";

    return $sql;
}
function _sql_datos_complementos($codigo, $user_session)
{
    $sql = null;
    
    if(is_numeric($codigo))
    {
        if($codigo > 10000000001)
        {
            $sql = "SELECT distrito, direccion FROM maestro_clientes WHERE ruc = :codigo LIMIT 1";
        }else
        {
            $sql = "SELECT medico_direccion AS direccion, medico_localidad AS distrito FROM tbl_maestro_medicos WHERE medico_cmp = :codigo LIMIT 1";
        }
    }else
    {
        $sql = "SELECT distrito, direccion FROM maestro_clientes WHERE codigo = :codigo LIMIT 1";
    }

    return $sql;
}
function _sql_count_ruteo_fecha()
{
    $sql = "SELECT COUNT(fecha) AS cantidad FROM ruteo WHERE fecha = :fecha AND vendedor = :vendedor";
    return $sql;
}
#-----------PAGOS---------------
function _sql_representates_ruteo()
{
    $sql = "SELECT DISTINCT
                vendedor, nombre_corto
            FROM
                ruteo
                    LEFT JOIN
                tbl_representantes ON representante_codigo = vendedor
            WHERE
                YEAR(fecha) = :year AND MONTH(fecha) = :month
                ORDER BY nombre_corto";

    return $sql;
}
function _sql_contar_dias_ruteo()
{
    $sql = "SELECT 
                COUNT(cliente) AS 'cant'
            FROM
                ruteo
            WHERE
                YEAR(fecha) = :year AND MONTH(fecha) = :month
                    AND DAY(fecha) = :day
                    AND vendedor = :vendedor;";

    return $sql;
}
function _sql_listado_ruteo_pagos()
{
    $sql = "SELECT 
                vendedor,
                fecha,
                cliente,
                cliente_desc,
                objetivo,
                direccion AS destino,
                distrito AS distrito,
                'Viaje' AS viaje,
                cod_int AS cliente_int
            FROM
                ruteo
            WHERE
                YEAR(fecha) = :year AND MONTH(fecha) = :month
                    AND vendedor IN (:codigos)
                    AND DAY(fecha) BETWEEN :_min_day_ AND :_max_day_
            GROUP BY vendedor , fecha , cliente , hora
            ORDER BY vendedor , fecha , hora;";
    
    return $sql;
}
function _sql_validate_permitions()
{
    $sql = "SELECT 
                modulo, usuarios, inicio, fin, estado
            FROM
                permisos
            WHERE
                modulo = :module ORDER BY registro DESC LIMIT 0,1";
    
    return $sql;
}