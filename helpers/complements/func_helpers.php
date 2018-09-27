<?php 
ob_start();
#------- Funciones generales en la aplicacion ---------#
#############BKS######################
function __FUNCTIONS__BK__()
{
    $GET_ = $_SERVER['QUERY_STRING'];
    #viewController=Pedidos&data=Nuevo
    if(strpos($GET_, "&") !== FALSE)
    {#si
        $parse1 = explode("&", $GET_);
        $parse2 = explode("=", $parse1[0]);
        $module = strtolower($parse2[1]);
    }else
    {#no
        $parse2 = explode("=", $GET_);
        $module = strtolower($parse2[1]);
    }  
    $_path_ = 'app/modules/'.$module.'/model.php';
    //return $_path_;

    if(file_exists($_path_))
    {
        require_once $_path_;
    }else
    {
        header('Location: ' . web_path().$module);
    }
}
function __FUNCTIONS__bk()
{
    $GET_ = $_SERVER['QUERY_STRING'];
    #viewController=Pedidos&data=Nuevo
    $parse1 = @explode("-", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    $_path_ = 'app/modules/'.$module.'/functions.php';

    if(file_exists($_path_))
    {
        require_once $_path_;
    }else
    {
        header('Location: ' . web_path().$module);
    }
}
function __SQL__bk()
{
    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("-", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    $_path_ = 'app/modules/'.$module.'/sql.php';
    #return strtolower($_SERVER);
    if(file_exists($_path_))
    {
        require_once $_path_;
    }else
    {
        header('Location: ' . web_path().$module);
    }
}
function __MODELS__bk()
{
    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("-", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    $_path_ = 'app/modules/'.$module.'/model.php';
    #require_once 'app/modules/'.$module.'/model.php';

    if(file_exists($_path_))
    {
        require_once $_path_;
    }
}
function __CONTROLLERS__bk()
{
    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("-", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    $_path_ =  'app/modules/'.$module.'/controller.php';

    if(file_exists($_path_))
    {
        require_once $_path_;
    }else
    {
        header('Location: ' . web_path().$module);
    }
}
#################BKS################
#################NEW################
function __MODELS__($__file__)
{
    $__file__ = strtolower($__file__);

    $_path_ = 'app/modules/'.$__file__.'/model.php';
    
    if(file_exists($_path_))
    {
        require_once $_path_;
    }
}
function __SQL__($__file__)
{
    $__file__ = strtolower($__file__);

    $_path_ = 'app/modules/'.$__file__.'/sql.php';
    
    if(file_exists($_path_))
    {
        require_once $_path_;
    }else
    {
        header('Location: ' . web_path().$__file__);
    }
}
function __FUNCTIONS__($__file__)
{
    $__file__ = strtolower($__file__);

    $_path_ = 'app/modules/'.$__file__.'/functions.php';
    
    if(file_exists($_path_))
    {
        require_once $_path_;
    }else
    {
        header('Location: ' . web_path().$__file__);
    }
}
function __CONTROLLERS__($__file__)
{
    $__file__ = strtolower($__file__);

    $_path_ = 'app/modules/'.$__file__.'/controllers.php';
    
    if(file_exists($_path_))
    {
        require_once $_path_;
    }else
    {
        header('Location: ' . web_path().$__file__);
    }
}
#################NEW################

function model($model)//retorno ruta modelo
{
    return 'app/models/' . strtolower($model) . 'Model.php';
}
function controller($controller)//retorno ruta controladores
{
    return 'app/controllers/' . strtolower($controller) . 'Controller.php';
}
function __css_js__($element)
{
    #$array = array();
    $e['highchart_JS'] = "plugins/code/highcharts.js";
    $e['highchart_MORE_JS'] = "plugins/code/highcharts-more.js";
    $e['bootstrap-select-min_CSS'] = "plugins/bootstrap-select/dist/css/bootstrap-select.min.css";
    $e['bootstrap-select-min_JS'] = "plugins/bootstrap-select/dist/js/bootstrap-select.min.js";
    $e['bootstrap-filestyle_JS'] = "plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js";

    return $e[$element];
}

function only_views($view = 'default')//retorno ruta vistas
{
    /*if($view == null)
    {
        $view = 'default';
    }*/
    //return 'app/views/' . strtolower($views) . 'View.php';
    #viewController=Login&data=sss

    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("&", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    require 'app/modules/'.$module.'/views/'.$view.'.php';
    #return $_SERVER['QUERY_STRING'];
}
function views__BK($view = 'default')//retorno ruta vistas
{
    /*if($view == null)
    {
        $view = 'default';
    }*/
    //return 'app/views/' . strtolower($views) . 'View.php';
    #viewController=Login&data=sss

    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("&", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    require 'app/modules/'.$module.'/views/'.$view.'.php';
    #return $_SERVER['QUERY_STRING'];
}
function path_modal($modal = 'modal')//retorno ruta vistas
{
    /*if($view == null)
    {
        $view = 'default';
    }*/
    //return 'app/views/' . strtolower($views) . 'View.php';
    #viewController=Login&data=sss

    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("&", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    return 'app/modules/'.$module.'/modal.php';
    #return $_SERVER['QUERY_STRING'];
}
function views($view)//retorno ruta vistas
{
    //return 'app/views/' . strtolower($views) . 'View.php';
    #viewController=Login&data=sss

    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("&", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    return 'app/modules/'.$module.'/'.'views/'.$view.'.php';
    #return $_SERVER['QUERY_STRING'];
}
function _complement_($var)
{
    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("&", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);
    
    $path = 'app/modules/'.$module.'/'.$var;
    if(file_exists($path))
    {
        return web_path().$path;
    }
}
function render_view($view = 'default', $css_js_1 = null, $css_js_2 = null, $modals = null)
{
    $_path_css_js_1 = $css_js_1;
    $_path_css_js_2 = $css_js_2;

    require_once 'app/UI/doctype.php';
    require_once 'app/UI/default_header.php';
    #PERZONALIZADO CSS O JS
    required_content('<link href="'._complement_('style.css').'" rel="stylesheet" type="text/css">');

    for ($i = 0; $i <= count($_path_css_js_1) - 1; $i++)
    { 
        $_this_extension = @end((explode(".",$_path_css_js_1[$i])));

        if($_this_extension == 'css')
        {
            required_content('<link href="'.APP_PUBLIC.'public/assets/'.$_path_css_js_1[$i].'" rel="stylesheet" type="text/css">');
        }else
        {
            required_content('<script src="'.APP_PUBLIC.'public/assets/'.$_path_css_js_1[$i].'"></script>;');
        }
    }
    
    
    #PERZONALIZADO CSS O JS
    require_once 'app/UI/end_header_init_body.php';
    require_once 'app/UI/left_menu.php';
    
    require_once views($view);

    require_once 'app/UI/end_content.php';
    #MODALS
    if(file_exists(path_modal()))
    {   
        require_once path_modal();
    }
    #require_once 'app/modals/avanceventas.php';
    #MODALS
    require_once 'app/UI/default_script.php';
    #PERZONALIZADO JS
    for ($b = 0; $b <= count($_path_css_js_2) - 1; $b++)
    { 
        $_this_extension = @end((explode(".",$_path_css_js_2[$b])));

        if($_this_extension == 'css')
        {
            required_content('<link href="'.APP_PUBLIC.'public/assets/'.$_path_css_js_2[$b].'" rel="stylesheet" type="text/css">');
        }else
        {
            required_content('<script src="'.APP_PUBLIC.'public/assets/'.$_path_css_js_2[$b].'"></script>;');
        }
    }
    required_content('<script src="'._complement_('script.js').'"></script>');
    #PERZONALIZADO JS
    require_once 'app/UI/end_html.php';
}
function validate_event_view($controller, $view)//retorno ruta vistas viewController/data
{
    $DIR1 = 'app/event_views/'   .   strtolower($controller) .   '/' .   strtolower($view)   .   '.php';
    $DIR2 = 'app/event_views/error.php';

    if(file_exists($DIR1))
    {
        return true;
    }else
    {
        return false;
    }
}
function event_view($controller, $view)
{
    //retorno ruta vistas viewController/data
    $DIR1 = 'app/event_views/'   .   strtolower($controller) .   '/' .   strtolower($view)   .   '.php';
    //print $DIR1;
    if(file_exists($DIR1))
    {
        return $DIR1;
    }
}
function view_error()
{
    $DIR2 = 'app/event_views/error.php';
    require_once  $DIR2;
}
function clean_input($value)//retorno de string limpio de caracteres.
{
    return trim(strip_tags(trim($value)));
}
function clear_input($string)
{
    return trim(strip_tags(str_replace(" ", "", $string)));
}
function all_text_low_first_mayus_($string)//formatstring  -- Formateo de string a la primera letra mayuscula.
{
    return ucfirst(strtolower($string));
}
function StringOut($Contenido, $Inicio, $Fin)
{
    $String = explode($Inicio, $Contenido);

    if (isset($String[1]))
    {
        $String = explode($Fin, $String[1]);

        return $String[0];
    }
        return '';
}
#------- redireccionamiento URL ---------#
function redirect($URL)
{
    //$URL = strtolower($URL);

    if( $URL == ""  or $URL == NULL or $URL == "index"  )
    {
        header('location: ' . APP_INDEX);
    }else
    {
        header('location:'  .  ucfirst($URL));
    }
}
function leftmenu()
{
    $menu = _get_menu_list($_SESSION['user_user']); #_menu("permisos");
    $out = null;

    $out .= '<div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">
                <div id="sidebar-menu">
                <ul>';
    
    if(strpos($menu, '||') == TRUE)
    {
        $parse1 = explode("||", $menu);
        $delimeter = count($parse1);
    
        for($d = 0; $d < $delimeter; $d++)
        {
            $parse2 = explode('=', $parse1[$d]);
            $modulo_parse = explode("~",$parse2[0]);
    
            $modulo_titulo = $modulo_parse[0];
            $modulo_icon = $modulo_parse[1];
    
            $out .= '<li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect">
                            <i class="'.$modulo_icon.'"></i>
                            <span>'.$modulo_titulo.'</span> 
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="list-unstyled">';
            
            $smenu_explode = explode(",", $parse2[1]);
            $sb1_count = count($smenu_explode);
    
            for($sb1 = 0; $sb1 < $sb1_count; $sb1 ++)
            {
                $sb1_explode = explode('~', $smenu_explode[$sb1]);
    
                $sb1_titulo = $sb1_explode[0];
                $sb1_url = $sb1_explode[1];
                $sb1_icon = $sb1_explode[2];
    
                $out .='<li>
                            <a href="'.web_path().$sb1_url.'">
                                <i class="'.$sb1_icon.'"></i>
                                <span>'.$sb1_titulo.'</span>
                            </a>
                        </li>';
            }
            $out .='</ul></li>';
        }
    }else
    {
        $parse2 = explode('=', $menu);
        $modulo_parse = explode("~",$parse2[0]);
    
        $modulo_titulo = $modulo_parse[0];
        $modulo_icon = $modulo_parse[1];
    
        $out .= '<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect">
                        <i class="'.$modulo_icon.'"></i>
                        <span>'.$modulo_titulo.'</span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">';
            
        $smenu_explode = explode(",", $parse2[1]);
        $sb1_count = count($smenu_explode);
    
        for($sb1 = 0; $sb1 < $sb1_count; $sb1 ++)
        {
            $sb1_explode = explode('~', $smenu_explode[$sb1]);
    
            $sb1_titulo = $sb1_explode[0];
            $sb1_url = $sb1_explode[1];
            $sb1_icon = $sb1_explode[2];
    
            $out .='<li>
                        <a href="'.web_path().$sb1_url.'">
                            <i class="'.$sb1_icon.'"></i>
                            <span>'.$sb1_titulo.'</span>
                        </a>
                    </li>';
        }
        $out .='</ul></li>';
    }
    
    $out .= '</ul>
            <div class="clearfix"></div>
            </div></div></div>';

    return $out;
}
function formatfecha($fecha)
{
    $FechaArray = explode('/', $fecha);
    return $FechaArray[2].'-'.$FechaArray[1].'-'.$FechaArray[0];
}
function onlymes($fecha)
{
    $rpta = date("m", strtotime($fecha));
    
    return $rpta;
}
function nombremes($Fecha)
{
    $FechaArray = explode('-', $Fecha);
    $Meses = array("01" => 'Enero', "02" => 'Febrero', "03" => 'Marzo', "04" => 'Abril', "05" => 'Mayo', 
    "06" => 'Junio', "07" => 'Julio', "08" => 'Agosto', "09" => 'Setiembre', "10" => 'Octubre', "11" => 'Noviembre', "12" => 'Diciembre');

    return  $FechaArray[2].' de '. $Meses[$FechaArray[1]];
}
function yearmes_to_fecha_db($data)
{
    return nl2br(htmlentities(wordwrap($data, 4, "-", true)))."-01";
}
function yearmes_to_year_month($data)
{
    $separe = nl2br(htmlentities(wordwrap($data, 4, "-", true)));
    $go = explode("-", $separe);
    $out = array('mes' => $go[1], 'year' => $go[0]);
    return $out;
}
function only_mes_($var)
{
    $FechaArray = explode('-', yearmes_to_fecha_db($var));
    $Meses = array("01" => 'Enero', "02" => 'Febrero', "03" => 'Marzo', "04" => 'Abril', "05" => 'Mayo', 
    "06" => 'Junio', "07" => 'Julio', "08" => 'Agosto', "09" => 'Setiembre', "10" => 'Octubre', "11" => 'Noviembre', "12" => 'Diciembre');

    return $Meses[$FechaArray[1]];
}
function yearmonth_to_only_mes($var)
{
    $FechaArray = explode('-', $var);
    $Meses = array("01" => 'Enero', "02" => 'Febrero', "03" => 'Marzo', "04" => 'Abril', "05" => 'Mayo', 
    "06" => 'Junio', "07" => 'Julio', "08" => 'Agosto', "09" => 'Setiembre', "10" => 'Octubre', "11" => 'Noviembre', "12" => 'Diciembre');

    return $Meses[$FechaArray[1]];
}
// function _search_mes_to_num($mes)
// {
//     $FechaArray = explode('-', $Fecha);
//     $Meses = array('Ene' => "01", 'Feb' => "02", 'Mar' => "03", 'Abr' => "04", 'May' => "05", 
//     "Jun" => '06', "Jul" => '07', "Agosto" => '08', "Set" => '09', "Oct" => '10', "Nov" => '11', "Dic" => '12');

//     return $Meses[$mes];
// }
function clear_($var)
{
    return strip_tags(trim($var));
}
function required_content($content)
{
    $ContentData = explode(";", $content);

    for ($i = 0; $i <= count($ContentData) - 1; $i++)
    { 
        print $ContentData[$i];
    }
}
function validate_periodo_gastos()
{
    $dia = date('d');

    if($dia <= 15)
    {
        $option = '<option value="1" selected>Primera</option>
                    <option value="2">Segunda</option>';
    }else
    {
        $option = '<option value="1">Primera</option>
                    <option value="2" selected>Segunda</option>';
    }
    print $option;
}
function zerofill($valor, $longitud)
{
    $res = str_pad($valor, $longitud, '0', STR_PAD_LEFT);
    return $res;
}
function _num_mes_completo($mes)
{
    $mes = null;

    if(strlen($mes) == 1)
    {
        $mes = "0".$mes;
    }

    return $mes;
}
function _name_mes_x_only_mes($mes)
{
    $Meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 
    6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Setiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');

    return $Meses[$mes];
}
function fecha_db_to_view($fecha)
{
    if(strpos($fecha,"-") !== FALSE)
    {
        $fecha = explode('-', $fecha);
        $fecha = $fecha[2].'/'.$fecha[1].'/'.str_replace('20','', $fecha[0]);
    }else
    {
        $fecha = '0/0/0';
    }
    return $fecha;
}
function fecha_db_to_view_2($fecha)
{
    $fecha = explode('-', $fecha);
    $fecha = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];

    return $fecha;
}
function _count_month($fecha1, $fecha2)
{
    $parsef1 = explode('-', $fecha1); 
    $parsef2 = explode('-', $fecha2);

    $dia1 = (int)$parsef1[2];
    $mes1 = (int)$parsef1[1];
    $year1 = (int)$parsef1[0];

    $dia2 = (int)$parsef2[2];
    $mes2 = (int)$parsef2[1];
    $year2 = (int)$parsef2[0];

    $multi = 0;
    $diff_month = 0;

    $diff_year = $year2-$year1;

    if($diff_year == 0)
    {
        $diff_month = ($mes2 - $mes1);
    }else if($diff_year == 1)
    {
        $diff_month = ($mes2 - 1) + (12 - $mes1) + 1;
    }else
    {
        $multi = $diff_year - 1;

        $diff_month = ($mes2 - 1) + (12 - $mes1) + (12 * $multi) + 1;
    }
    if($diff_month == 0)
    {
        $diff_month = 1;
    }
    return $diff_month;
}
function fecha_to_periodo($var)
{
    $fecha_ex = explode("-", $var);
    $mes = (int)$fecha_ex[1];
    $year = (int)$fecha_ex[0];

    if($mes < 10)
    {
        $mes = '0'.$mes;
    }    

    return $periodo = (int)$year.$mes;
}
function fecha_to_periodo_del($var, $delimeter)
{
    $fecha_ex = explode($delimeter, $var);
    $mes = (int)$fecha_ex[1];
    $year = (int)$fecha_ex[0];

    if($mes < 10)
    {
        $mes = '0'.$mes;
    }    

    return $periodo = (int)$year.$mes;
}
function fecha_to_periodo_del_($var, $delimeter)
{
    $fecha_ex = explode($delimeter, $var);
    #17/09/2018

    $mes = (int)$fecha_ex[1];

    if(strlen($fecha_ex[0]) == 4)
    {
        $year = $fecha_ex[0];
    }else if(strlen($fecha_ex[2]) == 4)
    {
        $year = $fecha_ex[2];
    }

    if($mes < 10)
    {
        $mes = '0'.$mes;
    }    

    return $periodo = (int)$year.$mes;
}
function diferenciadias($inicio, $fin)
{
    $inicio = strtotime($inicio);
    $fin = strtotime($fin);
    $dif = $fin - $inicio;
    $diasFalt = (( ( $dif / 60 ) / 60 ) / 24);
    return ceil($diasFalt);
}
function num_semana($fecha)
{
	$parse = explode("-", $fecha);

	$dia = $parse[2];
	$mes = $parse[1];
	$anio = $parse[0];

	$semana = date('W', mktime(0, 0, 0, $mes, $dia, $anio));  

	return (int)$semana;
}
function add_day_date($fecha, $days)
{
	return date('Y-m-d', strtotime('+'.$days.' day', strtotime($fecha)));
}
function comillas($var)
{
    return str_replace('"', '', str_replace("├æ", "N", $var));
}
function fecha_csv_to_db($fecha)
{
    $fecha = explode('/', $fecha);
    $fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];

    return date("Y-m-d", strtotime($fecha));
}
function _search_mes_to_num($var)
{
    $var = (string)$var;
    switch ($var) {
        case 'Ene':
            return "01-01";
            break;
        case 'Feb':
            return "02-01";
            break;
        case 'Mar':
            return "03-01";
            break;
        case 'Abr':
            return "04-01";
            break;
        case 'May':
            return "05-01";
            break;
        case 'Jun':
            return "06-01";
            break;
        case 'Jul':
            return "07-01";
            break;
        case 'Ago':
            return "08-01";
            break;
        case 'Sep':
            return "09-01";
            break;
        case 'Oct':
            return "10-01";
            break;
        case 'Nov':
            return "11-01";
            break;
        case 'Dic':
            return "12-01";
            break;
        default:
            return "c";
            break;
            
    } 
}
function _search_mes_to_num2($var)
{
    $var = (string)$var;
    switch ($var) {
        case 'Ene':
            return "01";
            break;
        case 'Feb':
            return "02";
            break;
        case 'Mar':
            return "03";
            break;
        case 'Abr':
            return "04";
            break;
        case 'May':
            return "05";
            break;
        case 'Jun':
            return "06";
            break;
        case 'Jul':
            return "07";
            break;
        case 'Ago':
            return "08";
            break;
        case 'Sep':
            return "09";
            break;
        case 'Oct':
            return "10";
            break;
        case 'Nov':
            return "11";
            break;
        case 'Dic':
            return "12";
            break;
        default:
            return "99";
            break;
            
    } 
}
function cod_reg_to_name($reg)
{
    $reg1 = strtolower($reg);
    $reg2 = ltrim($reg1);
    $reg3 = rtrim($reg2);

    switch ($reg3) {
        case 'castillo arequipa':
            return 22;
            break;
        case 'dimexa arequipa':
            return 2;
            break;
        case 'norte 1':
            return 10;
            break;
        case 'norte 2':
            return 9;
            break;
        case 'sierra central':
            return 11;
            break;
        case 'norte sur chico':
            return 3;
            break;
        case 'capon':
            return 7;
            break;
        case 'lima':
            return 1;
            break;
        case 'cadenas':
            return 13;
            break;
        default:
            return "c";
            break;
            
    }    
}
function search_region_name($reg)
{

    switch ($reg) {
        case 22:
            return 'castillo arequipa';
            break;
        case 2:
            return 'dimexa arequipa';
            break;
        case 10:
            return 'norte 1';
            break;
        case 9:
            return 'norte 2';
            break;
        case 11:
            return 'sierra central';
            break;
        case 3:
            return 'norte sur chico';
            break;
        case 7:
            return 'capon';
            break;
        case 1:
            return 'lima';
            break;
        case 13:
            return 'cadenas';
            break;
        case 99:
            return 'Especiales';
            break;
        default:
            return "c";
            break;
            
    }    
}
function _zona_ventas_visita($var)
{
    // return 0;

    switch ($var) {
        case '1':#LIMA
            return 8;
            break;
        case '2':#AREQUIPA
            return 23;
            break;
        case '3':#NSC
            return 28;
            break;
        case '7':#CAPON
            return 999;
            break;
        case '9':#NORTE2
            return 25;
            break;
        case '10':#NORTE1
            return 26;
            break;
        case '11':#SIERRA CENTRAL
            return 27;
            break;
        case '22':#CASTILLO
            return 24;
            break;
        default:
            return 111;
            break;
    }

    // return $reg_cod_vis;
}
function _reg_visita_ventas_($var)
{
    // return 0;

    switch ($var) {
        case '8':#LIMA
            return 1;
            break;
        case '23':#AREQUIPA
            return 2;
            break;
        case '28':#NSC
            return 3;
            break;
        case '999':#CAPON
            return 7;
            break;
        case '25':#NORTE2
            return 9;
            break;
        case '26':#NORTE1
            return 10;
            break;
        case '27':#SIERRA CENTRAL
            return 11;
            break;
        case '24':#CASTILLO
            return 22;
            break;
        default:
            return 111;
            break;
    }

    // return $reg_cod_vis;
}
function sanear_string($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    
 
 
    return $string;
}
function repetidos_array($array)
{
    $repetidos = 0;

    $uniqueArray = array_unique($array);

    $valorComun1 = array_diff_assoc($array, $uniqueArray);
    $valorComun2 = array_unique($valorComun1);
    
    if(count($valorComun2) > 0)
    { 
        $repetidos = $valorComun2;
    }else
    {
        $repetidos = 0;
    }
    return $repetidos;
}
function max_string_values($str, $max)
{
    if (strlen($str) >= $max)
    {
        $max = $max - 3;
        $str = substr($str, 0, $max) . '...';
    }else
    {
        $str = $str;
    }   
    return $str;
}
function max_days($today, $post_date)
{
    $fecha_mm = $post_date;

    $diff = abs(strtotime($today) - strtotime($fecha_mm));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

    return $days;
}
function color_x_monto($var)
{
    $out = null;
    $color = null;

    if($var < 80)
    {
        $color = '#ff5f5f';
    }elseif ($var >= 80 && $var < 90)
    {
        $color = '#fdb44b';
    }elseif ($var >= 90 && $var < 100)
    {
        $color = '#5ba19b';
    }elseif ($var >= 100)
    {
        $color = '#3d6cb9';
    }
    $out = '<b style="color:'.$color.';">'.$var.'</b>';

    return $out;
}
function color_x_circulo($var)
{
    $out = null;
    $color = null;

    if($var < 80)
    {
        $color = '#ce2525';
    }elseif ($var >= 80 && $var < 90)
    {
        $color = '#fdb44b';
    }elseif ($var >= 90 && $var < 100)
    {
        $color = '#5ba19b';
    }elseif ($var >= 100)
    {
        $color = '#3d6cb9';
    }
    $out = '<span style="color:'.$color.';" class="fa fa-circle"></span> '.$var.'%';

    return $out;
}
function NAN_to_cero($var)
{
    $out = 0;
    if(is_nan($var))
    {
        $out = 0;
    }else
    {
        $out = $var;
    }
    return $out;
}
function dias_pasados($fecha_inicial, $fecha_final)
{
    $dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
    $dias = abs($dias);
    $dias = floor($dias);
    return $dias;
}


ob_end_flush();
?>

