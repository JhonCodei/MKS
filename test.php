<?php


//  $fecha_inicial= "2018-10-02";
// // // fecha actual
//  $fecha_final= date("Y-m-d");
 //2
//ss
 $fecha_inicial=  date("Y-m-d");
// // fecha actual
 $fecha_final= "2018-10-02";

function dias_pasados($fecha_inicial, $fecha_final)
{
    $dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
    $dias = abs($dias);
    $dias = floor($dias);
    return $dias;
}
print dias_pasados($fecha_inicial, $fecha_final);


 
// function dias_pasados($fecha_inicial,$fecha_final)
// {
//     $fecha1 = new DateTime($fecha_inicial);
//     $fecha2 = new DateTime($fecha_final);
//     $resultado = $fecha1->diff($fecha2);
//     return $resultado->format('%R%');
// }

// $fecha_inicial= "2018-10-02";
// // fecha actual
// $fecha_final= date("Y-m-d");
// print dias_pasados($fecha_inicial,$fecha_final);