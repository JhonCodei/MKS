<?php

Class CargasController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "cargas";

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
    public function load_csv()
    {       
        if ($_FILES)
        {
            $file_excel = $_FILES["excel"]["tmp_name"];
            $periodo = $_POST['periodo'];

            $Class = new CargasModel();
            print $Class->load_csv($file_excel, $periodo);
            // $region = $_POST['region'];
            // if($region != null)
            // {
            //     require_once model("ventanacional");

            //     $Class = new VentanacionalModel();
            //     // print $Class->load_csv($file_excel, $periodo);
            //     print $Class->load_closeup($file_excel, $region);
            //     #print $Class->load_cuota_producto($file_excel, $periodo);
            // }else
            // {
            //     require_once model("ventanacional");

            //     $Class = new VentanacionalModel();
            //     print $Class->load_csv($file_excel, $periodo);
            //     // print $Class->load_closeup($file_excel, $region);
            //     #print $Class->load_cuota_producto($file_excel, $periodo);
            // }

            // require_once model("ventanacional");

            // $Class = new VentanacionalModel();
            // // print $Class->load_csv($file_excel, $periodo);
            // print $Class->load_closeup($file_excel, $region);
            // #print $Class->load_cuota_producto($file_excel, $periodo);
        }else
        {
            print "error";
        }
    }
    public function list_closeup_data()
    {      
        $Class = new CargasModel();
        print $Class->list_closeup_data();
    }
    public function generar_reporte_venta()
    {
        $periodo = $_POST['periodo'];

        $Class = new CargasModel();
        print $Class->generar_reporte_venta($periodo);
    }
    public function sierra_central_proceso()
    {
        $periodo = $_POST['periodo'];

        $Class = new CargasModel();
        print $Class->sierra_central_proceso($periodo);
    }
    public function generar_reporte_visitas()
    {
        $periodo = $_POST['periodo'];
        $region = $_POST['region'];

        $Class = new CargasModel();

        switch ($region)
        {
            case '1':#LIMA
                print $Class->generar_reporte_visitas_lima($periodo);
                break;
            case '2':#AREQUIPA
                print $Class->generar_reporte_visitas_dmx_are($periodo);
                break;
            case '3':#NSC
                print $Class->generar_reporte_visitas_nsc($periodo);#######
                break;
            // case '7':#CAPON
            //     print $Class->generar_reporte_visitas_capon($periodo);
            //     break;
            case '9':#NORTE2
                print $Class->generar_reporte_visitas_norte2($periodo);#
                break;
            case '10':#NORTE1
                print $Class->generar_reporte_visitas_norte1($periodo);#
                break;
            case '11':#SIERRA CENTRAL
                print $Class->generar_reporte_visitas_sc($periodo);#
                break;
            case '22':#CASTILLO
                print $Class->generar_reporte_visitas_castillo($periodo);
                break;
            default:
                print 'error';
                break;
        }        
    }
}