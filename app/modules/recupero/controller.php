<?php

Class RecuperoController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "recupero";

        __MODELS__($__file__);
        __SQL__($__file__);
        __FUNCTIONS__($__file__);
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();

        $css_js_1[] = __css_js__('bootstrap-select-min_CSS');
        $css_js_2[] = __css_js__('bootstrap-select-min_JS');

        return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__
    public function listar_data_detalle()
    {
        $periodo = $_POST['periodo'];
        
        $Class = new RecuperoModel();
        print $Class->listar_data_detalle($periodo);
    }
    public function procesar_recupero()
    {
        $periodo = $_POST['periodo'];
        $data_p = yearmes_to_year_month($periodo);

        $year = $data_p['year'];
        $mes = $data_p['mes'];

        $Class = new RecuperoModel();
        print $Class->procesar_recupero($year, $mes);
    }
    public function procesar_escalas()
    {
        $periodo = $_POST['periodo'];

        $region = 'T';

        $Class = new RecuperoModel();
        print $Class->procesar_escalas($periodo, $region);
    }


}