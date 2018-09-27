<?php

Class ErrorController
{
    public function __construct()
    {
        # code...
        #__MODELS__();
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();

        return only_views();
        //return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__


}