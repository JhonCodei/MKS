<?php

function select_box_region_x_sup($v)
{
    $output = null;
    $session_user = $_SESSION['user_user'];

    $query = Database::Connection()->prepare(sql_select_box_region_x_sup());
    $query->bindParam(":user", $session_user, PDO::PARAM_STR);    
    
    if($query->execute())
    {
        if($query->rowCount() > 0)
        {
            $rQuery0 = $query->fetch(PDO::FETCH_ASSOC);

            $array = array(0 => $rQuery0['codigo'], 
                                    1 => $rQuery0['portafolio'],  
                                    2 => $rQuery0['region'], 
                                    3 => $rQuery0['region_visita'], 
                                    4 => $rQuery0['tipo']);

            $output = $array[$v];
        }else
        {
            $output = 0;
        }
    }

    return $output;
}