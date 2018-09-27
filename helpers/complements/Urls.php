<?php

function web_path()
{
    $var = @explode('/', $_SERVER['REQUEST_URI']);
    
    $url_ = '/'.$var[1].'/';

    return $url_;
}
function icon_web()
{
    return '<link rel="icon" type="image/png" href="'.web_path().'public/assets/images/Website/icon.png"/>';
}
function web_index()
{
    return '/'.explode('/', $_SERVER['PHP_SELF'])[1].'/';
}
function server_url()
{
    return $_SERVER['REQUEST_URI'];
}
function __tabs_names__($var = 'not defined')
{
    return $var;
}
function title_($tab_name = 'Not defined')
{
    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("&", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = ucfirst(strtolower($module));

    $view =  @end((explode("=", $parse1[1])));
    $view = ucfirst(strtolower($view));

    if(strlen($view) == 0)
    {
        $tab_name = $module;
    }else
    {
        $tab_name = $module.'-'.$view;
    }
    
    return $tab_name;

    // $var = @ucfirst(end((explode("/", $_SERVER['REQUEST_URI']))));
    // if($var == '')
    // {
    //     $var = "Home";
    // }else
    // {
    //     $var = $var;
    // }
    // $var = $_SERVER;
    // return $var;
}
function __validate_url__($view = 'default')
{
    $GET_ = $_SERVER['QUERY_STRING'];
    $parse1 = @explode("&", $GET_);
    $module = @end((explode("=", $parse1[0])));
    $module = strtolower($module);

    $view_ =  @end((explode("=", $parse1[1])));
    $view_ = strtolower($view_);

    if(strlen($view_) != 0)
    {
        $view = $view_;
    }

    $_path = 'app/modules/'.$module.'/views/'.$view.'.php';
    
    if(file_exists($_path))
    {
        return true;
    }
    return false;
}