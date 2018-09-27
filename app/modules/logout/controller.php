<?php

Class LogoutController
{
    public function __construct()
    {
        # code...
        #__MODELS__();
        is_session_true(); 
    }
    public function render($vista)
    {
        __SESSION_OUT__();
    }
    #__functions__
}