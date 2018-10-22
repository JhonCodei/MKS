<?php 

// user_id
// hash_session
// access

function timeoutsession()
{
    if (isset($_SESSION['timeout'])) 
    {
        $LastTime   = $_SESSION['timeout'];
        $Now        = date('Y-m-d H:i:s');
        $RunTime    = (strtotime($Now)  -  strtotime($LastTime));
        
        if($RunTime >= 9000) 
        {
            session_destroy();
            redirect('Index');
        }else
        {
            $_SESSION['timeout'] = $Now;
        }
    }
}
function is_session_true()
{
    if(isset($_SESSION['user_id']))
    {
        return true;
    }else
    {
        redirect('Login');
    }
}
function is_session_()
{
    if(isset($_SESSION['user_id']))
    {
        return true;
    }
}
function __SESSION_OUT__()
{
    if(is_session_true())
    {
        session_destroy();
        redirect('Login');
    }
}
function is_menu_permission()
{
    // $_session_user = info_usuario('usuario');

    // $consulta = Database::Connection()->prepare("SELECT 
    //                                                     menu_items
    //                                                 FROM
    //                                                     tbl_maestro_menus
    //                                                 WHERE
    //                                                     menu_cod = (SELECT 
    //                                                             u_detalle_menu
    //                                                         FROM
    //                                                             tbl_usuario_detalle
    //                                                         WHERE
    //                                                             u_detalle_usuario = :_session_user);");
    // $consulta->bindParam(":_session_user", $_session_user);
    // if($consulta->execute() && $consulta->rowCount() > 0)
    // {

    //     $rconsulta = $consulta->fetch(PDO::FETCH_ASSOC);

    //     (string)$list_menu = $rconsulta['menu_items'];
    //     (string)$url = strtolower($_GET['viewController']);

    //     (array)$arraylist = array();
            
    //     $explode0 = explode('=', $list_menu);
    //     $explode1 = explode(',', $explode0[1]);

    //     for($i = 0; $i < count($explode1); $i++)
    //     {
    //             $explode2 = explode('~', $explode1[$i]);

    //             $arraylist[] = strtolower($explode2[1]);
    //     }

    //     if(in_array($url, $arraylist))
    //     {
    //             return true;
    //             print "xd<br>";
    //     }else
    //     {
    //         $menu_load = _get_menu_list(info_usuario('usuario'));
    //         $exp = explode("=", $menu_load);
    //         $exp2 = explode("~", $exp[1]);
    //         redirect($exp2[1]);  
    //         print "WAAAAA<br>";
    //     }
    // }else
    // {
    //     $menu_load = _get_menu_list(info_usuario('usuario'));
    //     $exp = explode("=", $menu_load);
    //     $exp2 = explode("~", $exp[1]);
    //     redirect($exp2[1]);
    //     // print "WAAAAA2222<br>";
    // }
}
function is_root()
{
    if(campo("root") == 0)
    {
        return true;
    }else
    {
        redirect("Index");
    }
}
function is_admin_session()
{
    if(info_usuario('usuario') == 'admin')
    {
        return true;
    }else
    {
        redirect("Login");
    }
}