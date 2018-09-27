<?php

Class LoginController 
{
    public function __construct()
    {       
        $__file__ = "login";

        __MODELS__($__file__);
        __SQL__($__file__);
         __FUNCTIONS__($__file__);
    }
    public function render()
    {
        return only_views();
    }
    public function logeo()
    {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        if(!empty($usuario))
        {
            $usuario = $usuario;
        }else
        {
            print "0~~El Usuario es necesario.";
            return false;
        }
        if(!empty($password))
        {
            $password = $password;
        }else
        {
            print "0~~La contraseña es necesario.";
            return false;
        }

        $Class = new LoginModel();
        print $Class->logeo($usuario, $password);                    
    }
}


?>