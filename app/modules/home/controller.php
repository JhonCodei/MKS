<?php

Class HomeController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "home";

        __MODELS__($__file__);
        __SQL__($__file__);
        __FUNCTIONS__($__file__);

        $this->model = new HomeModel();
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


}