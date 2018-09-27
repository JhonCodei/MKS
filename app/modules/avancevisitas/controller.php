<?php

Class AvancevisitasController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "avancevisitas";

        __MODELS__($__file__);
        __SQL__($__file__);
         __FUNCTIONS__($__file__);
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();

        return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__
    public function listar_avance_visitas()
    {   
        $periodo = $_POST['periodo'];
        $tipo = info_usuario('tipo_user');
        $region = $_POST['region'];
        $visitador = info_usuario('codigo');

        $Class = new AvancevisitasModel();
        print $Class->listar_avance_visitas($periodo, $tipo, $region, $visitador);
    }
    public function detail_total_ventas_visita()
    {
        $zona_view = $_POST['zona_view'];
        $periodo = $_POST['periodo'];
        $prod_port = $_POST['prod_port'];
        $region_venta = $_POST['region_venta'];

        $Class = new AvancevisitasModel();
        print $Class->detail_total_ventas_visita($zona_view, $periodo, $prod_port, $region_venta);
    }
    public function detail_clientes_visita()
    {
        $zona_view = $_POST['zona_view'];
        $periodo = $_POST['periodo'];
        $prod_port = $_POST['prod_port'];
        $region_venta = $_POST['region_venta'];

        $Class = new AvancevisitasModel();
        print $Class->detail_clientes_visita($zona_view, $periodo, $prod_port, $region_venta);
    }
    public function detail_productos_visita()
    {
        $periodo = $_POST['periodo'];
        $dist_cod = $_POST['dist_cod'];
        $cliente_cod = $_POST['cliente_cod'];
        $region_venta = $_POST['region_venta'];
        $vendedor = $_POST['vendedor'];

        $Class = new AvancevisitasModel();
        print $Class->detail_productos_visita($vendedor, $region_venta, $cliente_cod, $dist_cod, $periodo);
    }
    public function reporte()
    {
        $periodo = $_POST['periodo'];
        // $dist_cod = $_POST['dist_cod'];
        // $cliente_cod = $_POST['cliente_cod'];
        // $region_venta = $_POST['region_venta'];
        // $vendedor = $_POST['vendedor'];

        $Class = new AvancevisitasModel();
        print $Class->reporte($periodo);
    }


}