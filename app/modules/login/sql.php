<?php


function logueo()
{
    $SQL = NULL;

    $SQL = "SELECT usuario_id, usuario_usuario, usuario_password, usuario_estado, usuario_root
                FROM tbl_usuarios
                WHERE usuario_usuario = :usuario";

    return  $SQL;
}
