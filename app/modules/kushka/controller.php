<?php

Class KushkaController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "kushka";

        __MODELS__($__file__);
        __SQL__($__file__);
         __FUNCTIONS__($__file__);
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();

        #$css_js_1[] = __css_js__('highchart_JS');
        $css_js_2[] = __css_js__('bootstrap-filestyle_JS');

        return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__
    public function load_tipo_carga()
    {       
        if ($_FILES)
        {
            $file_excel = $_FILES["excel"]["tmp_name"];
            $periodo = $_POST['periodo'];
            $tipocarga = $_POST['tipocarga'];
            $empresa = $_POST['empresa'];

            $Class = new KushkaModel();

            if($tipocarga == 'locales')
            {
                print $Class->load_locales($file_excel);
            }else if($tipocarga == 'drenaje')
            {
                print $Class->load_drenaje($file_excel, $empresa);
            }
        }else
        {
            print "error";
        }

    }
    public function generar_data()
    {
        $periodo = $_POST['periodo'];

        $data_fecha = yearmes_to_year_month($periodo);

        $year = $data_fecha["year"];
        $mes = $data_fecha["mes"];
        
        $Class = new KushkaModel();
        $Class->generar_data($year, $mes);
    }
    public function listar_reporte()
    {
        $periodo = $_POST['periodo'];

        $data_fecha = yearmes_to_year_month($periodo);

        $year = (int)$data_fecha["year"];
        $mes = (int)$data_fecha["mes"];
        
        $Class = new KushkaModel();
        print $Class->listar_reporte($year, $mes);
    }

}