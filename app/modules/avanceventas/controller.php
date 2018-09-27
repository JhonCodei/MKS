<?php
Class AvanceventasController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "avanceventas";
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
    public function listar_avance()
    {   
        $periodo = $_POST['periodo'];
        $tipo = info_usuario('tipo_user');
        $region = $_POST['region'];
        $vendedor = info_usuario('codigo');

        if($vendedor == 632 || $vendedor == 810 || $vendedor == 218)
        {
            $region = 99;
        }

        if($vendedor == 40)
        {
            $vendedor = 771;
        }else if($vendedor == 758)
        {
            $vendedor = 771;
        }else if($vendedor == 771)
        {
            $vendedor = 771;
        }

        $Class = new AvanceventasModel();
        print $Class->listar_avance($periodo, $tipo, $region, $vendedor);
    }
    public function detail_clientes()
    {
        $Vendedor = clear_($_POST['vendedor']);
        $Periodo = clear_($_POST['periodo']);
        $Region = clear_($_POST['region']);

        if($Vendedor == 632 || $Vendedor == 810 )
        {
            $Region = 1;
        }else if($Vendedor == 218)
        {
            $Region = 7;
        }
        // print $region;
        $Class = new AvanceventasModel();
        print $Class->detail_clientes($Vendedor, $Periodo, $Region);
    }
    public function detail_total_ventas()
    {
        $Vendedor = clear_($_POST['vendedor']);
        $Periodo = clear_($_POST['periodo']);
        $Region = clear_($_POST['region']);

        if($Vendedor == 632 || $Vendedor == 810)
        {
            $Region = 1;
        }else if($Vendedor == 218)
        {
            $Region = 7;
        }

        $Class = new AvanceventasModel();
        print $Class->detail_total_ventas($Vendedor, $Periodo, $Region);
    }
    public function detail_productos()
    {
        $vendedor = clear_($_POST['vendedor']);
        $periodo = clear_($_POST['periodo']);
        $region = clear_($_POST['region']);
        $cliente_cod = clear_($_POST['cliente_cod']);
        $dist_cod = clear_($_POST['dist_cod']);

        $Class = new AvanceventasModel();
        print $Class->detail_productos($vendedor, $region, $cliente_cod, $dist_cod, $periodo);
    }
    public function reporte()
    {
        $periodo = $_POST['periodo'];
        // $dist_cod = $_POST['dist_cod'];
        // $cliente_cod = $_POST['cliente_cod'];
        // $region_venta = $_POST['region_venta'];
        // $vendedor = $_POST['vendedor'];

        $Class = new AvanceventasModel();
        print $Class->reporte($periodo);
    }
}