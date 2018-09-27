<?php
require_once 'app/core.php'; // requiriendo el core de funcionalidad

if ($_POST)
{
    switch (isset(  $_GET['request']) ? $_GET['request'] : NULL)
    {       
        case $_GET['request']:
            
            $__GET__ = explode('-', $_GET['request']);  

            $__MODULE__ = strtolower($__GET__[0]);

            $__CLASSNAME__ = ucfirst($__MODULE__).'Controller'; // Parseo del request enviado por ajax [para el Controller].
            
            $__FUNCTION__ = strtolower($__GET__[1]); //Parseo del request para la funcion.

            require_once "app/modules/".$__MODULE__."/controller.php";

            $__CLASS__ = new $__CLASSNAME__(); //Nombre de clase del Controller
            $__CLASS__->$__FUNCTION__(); //Nombre de la funcion de la clase del controller
            
        break;

        default:
            redirect();
        break;
    }
}else
{
    redirect();
}
?>
