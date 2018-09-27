<?php

Class PerfilController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "perfil";

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
    public function cambio_password()
    {
        $password = clear_($_POST['password']);
        $repassword = clear_($_POST['repassword']);


        if(!empty($password))
        {
            if($password == $repassword)
            {
                $user = $_SESSION['user_user'];
                $password = encrypt($password);
                   
                $Class = new PerfilModel();
                print $Class->cambio_password($user, $password);
            }else
            {
                print "Las contrase&ntilde;as no coinciden.";
            } 
        }else
        {
            print "Debe completar los campos.";
        }
    }

}