<?php 
#Code

function  sql_select_box_region_x_sup()
{
    $SQL = NULL;

    $SQL = "SELECT 
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
                    view_usuario = :user;";

    return  $SQL;
}