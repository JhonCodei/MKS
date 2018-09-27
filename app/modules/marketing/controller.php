<?php

Class MarketingController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "marketing";

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
    public function vendedor_cliente()
    {
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];   

        $vendedor = $_POST['vendedor'];
        $region = $_POST['region'];

        $periodo = $year.$mes;

        $Class = new MarketingModel();
        print $Class->vendedor_cliente($periodo, $region , $vendedor);
    }
    public function list_representantes()
    {
        $periodo = $_POST['periodo'];
        $region = $_POST['region'];

        $Class = new MarketingModel();
        print $Class->list_representantes($periodo, $region);
    }
    public function list_distribuidoras()
    {
        $Class = new MarketingModel();
        print $Class->list_distribuidoras();
    }
    public function list_productos()
    {
        $periodo = $_POST['periodo'];

        $Class = new MarketingModel();
        print $Class->list_productos($periodo);
    }
    public function list_productos_region()
    {
        $region = $_POST['region'];
        $periodo = $_POST['periodo'];

        $Class = new MarketingModel();
        print $Class->list_productos_region($periodo, $region);
    }
    public function list_productos_x_distribuidoras()
    {
        $periodo = $_POST['periodo'];
        $distribuidora = $_POST['distribuidora'];

        $Class = new MarketingModel();
        print $Class->list_productos_x_distribuidoras($periodo, $distribuidora);
    }
    public function producto_region_periodo()
    {
        $periodo = $_POST['periodo'];
        $region = $_POST['region'];
        $producto = $_POST['producto'];

        $Class = new MarketingModel();
        print $Class->producto_region_periodo($periodo, $region, $producto);
    }
    public function producto_cliente_zona()
    {
        $periodo = $_POST['periodo'];
        $distribuidora = $_POST['distribuidora'];
        $producto = $_POST['producto'];

        $Class = new MarketingModel();
        print $Class->producto_cliente_zona($periodo, $distribuidora, $producto);
    }
    public function resumen_ventas()
    {
        $periodo = $_POST['periodo'];

        $Class = new MarketingModel();
        print $Class->resumen_ventas($periodo);
    }
    public function cuota_x_producto()
    {
        $periodo = $_POST['periodo'];
        $producto = $_POST['producto'];

        $Class = new MarketingModel();
        print $Class->cuota_x_producto($periodo, $producto);
    }
    public function prod_zona()
    {
        $periodo = $_POST['periodo'];

        $Class = new MarketingModel();
        print $Class->prod_zona($periodo);
    }
    public function prod_zona_supervisor()
    {
        $periodo = $_POST['periodo'];
        $region = $_POST['region'];

        $Class = new MarketingModel();
        print $Class->prod_zona_supervisor($periodo, $region);
    }

}