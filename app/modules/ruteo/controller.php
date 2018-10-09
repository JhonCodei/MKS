<?php

Class RuteoController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "ruteo";

        __MODELS__($__file__);
        __SQL__($__file__);
         __FUNCTIONS__($__file__);
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();

        $css_js_1[] = __css_js__('bootstrap-timepicker_CSS');
        $css_js_2[] = __css_js__('bootstrap-timepicker_JS');

        return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__


}