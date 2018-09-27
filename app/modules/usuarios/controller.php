<?php

Class UsuariosController
{
    public function __construct()
    {
        is_session_true(); 
        is_menu_permission();

        $__file__ = "usuarios";

        __MODELS__($__file__);
        __SQL__($__file__);
         __FUNCTIONS__($__file__);
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();

        #$css_js_1[] = __css_js__('highchart_JS');
        #$css_js_2[] = __css_js__('highchart_JS');

        return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__
    public function list_menu()
    {
        $Class = new UsuariosModel();
        print  $Class->list_menu();
    }
    public function list_usuarios()
    {
        $Class = new UsuariosModel();
        print $Class->list_usuarios();
    }
    public function search_user()
    {
        $usuario = $_POST['usuario'];

        $Class = new UsuariosModel();
        print $Class->search_user($usuario);
    }
    public function insert_usuarios()
    {
        $nombres = clear_($_POST['nombres']);
        $root = clear_($_POST['root']);
        $usuario = clear_($_POST['usuario']);
        $password = encrypt(clear_($_POST['password']));
        $codigo = clear_($_POST['codigo']);
        $portafolio = clear_($_POST['portafolio']);
        $region = clear_($_POST['region']);
        $menu_array = clear_($_POST['menu_array']);
        $tipo_view = clear_($_POST['tipo_view']);
        $estado = 1;

        $Class = new UsuariosModel();
        print $Class->insert_usuarios($nombres,$root, $usuario, $password, $codigo,$portafolio, $region, $menu_array, $estado, $tipo_view);
    }
    public function update_usuarios()
    {
        $nombres = clear_($_POST['nombres']);
        $root = clear_($_POST['root']);
        $usuario = clear_($_POST['usuario']);
        $password = encrypt(clear_($_POST['password']));
        $codigo = clear_($_POST['codigo']);
        $portafolio = clear_($_POST['portafolio']);
        $region = clear_($_POST['region']);
        $menu_array = clear_($_POST['menu_array']);
        $tipo_view = clear_($_POST['tipo_view']);

        $Class = new UsuariosModel();
        print $Class->update_usuarios($nombres, $root, $usuario, $password, $codigo, $portafolio, $region, $menu_array, $tipo_view);
    }

}