<?php

Class MedmarkosController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "medmarkos";

        __MODELS__($__file__);
        __SQL__($__file__);
         __FUNCTIONS__($__file__);
    }
    public function render($vista)
    {
        $css_js_1 = array();
        $css_js_2 = array();
        
        #CSS_JS_1
        $css_js_1[] = __css_js__('highchart_JS');
        $css_js_1[] = __css_js__('highchart_MORE_JS');
        $css_js_1[] = __css_js__('bootstrap-select-min_CSS');
        

        #CSS_JS_2
        $css_js_2[] = __css_js__('bootstrap-filestyle_JS');
        $css_js_2[] = __css_js__('bootstrap-select-min_JS');

        return render_view($vista, $css_js_1, $css_js_2);
    }
    #__functions__
    public function load_movimiento()
    {
        if ($_FILES)
        {
            $file = $_FILES["excel"]["tmp_name"];
            $periodo = $_POST['periodo'];
            $tipo = $_POST['tipo'];

            if($tipo == 'movimiento')
            {
                $Class = new MedmarkosModel();
                print $Class->load_movimiento($periodo, $file);
            }else if($tipo == 'medicos')
            {
                $Class = new MedmarkosModel();
                print $Class->load_medicos($file);
            }
            elseif ($tipo == 'medicos_x_repre')
            {
                $Class = new MedmarkosModel();
                print $Class->load_medicos_x_repre($file);
            }
        }else
        {
            print "error";
        }
    }
    public function listar_representantes()
    {
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];
        $region = $_POST['region_src'];

        $Class = new MedmarkosModel();
        print $Class->listar_representantes($year, $mes, $region);
    }
    public function ficha_medica()
    {
        $representantes = $_POST['representantes'];
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];


        $Class = new MedmarkosModel();
        
        ?>
        <div class="row" id ="div-ficha-medica">
            <div class="col-lg-2"></div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label  style="color:#404969;" for="field-1" class="control-label">Medicos</label>
                    <input readonly="true" class="form-control text-center" type="text" id="numero-medicos" style="border-color:#78BBE6;">
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label  style="color:#404969;" for="field-1" class="control-label">Contactos</label>
                    <input readonly="true" class="form-control text-center" type="text" id="numero-contactos" style="border-color:#F38181;">
                </div>
            </div>
            <div class="col-lg-4" style="background-color:;">
                <div class="form-group">
                    <label  style="color:#404969;" for="field-1" class="control-label">Representante</label>
                    <input readonly="true" class="form-control text-center" type="text" id="representante_name" style="border-color:#78B7BB;">
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label  style="color:#404969;" for="field-1" class="control-label">D&iacute;as</label>
                    <input readonly="true" class="form-control text-center" type="text" id="dias" value="20" style="border-color:#38817A;">
                </div>
            </div>
            <div class="col-lg-1">
                <div class="form-group">
                    <label  style="color:#404969;" for="field-1" class="control-label">Cont/d&iacute;as</label>
                    <input readonly="true" class="form-control text-center" type="text" id="cont-dias" value="15" style="border-color:#5C636E;">
                </div>
            </div>
            </div>
            <br>
            <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-4">
                <table class="table table-striped table-condensed table-bordered">
                    <thead>
                        <th class="text-white text-center" style="background-color:#2D6E7E;">Categoria</th>
                        <th class="text-white text-center" style="background-color:#588D9C;">N° Medicos</th>
                    </thead>
                    <tbody>
                        <tr>
                        <td class="text-center font-weight-bold">AA</td>
                        <td class="text-center" id="cant-aa"></td>
                        </tr>
                        <tr>
                        <td class="text-center font-weight-bold">A</td>
                        <td class="text-center" id="cant-a"></td>
                        </tr>
                        <tr>
                        <td class="text-center font-weight-bold">B</td>
                        <td class="text-center" id="cant-b"></td>
                        </tr>
                        <tr>
                        <td class="text-center font-weight-bold">C</td>
                        <td class="text-center" id="cant-c"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row">
            <div class="table-responsive col-lg-6">
                <?php print $Class->table_x_especialidad($representantes);?>
            </div>
            <div class="table-responsive col-lg-6">
                <?php print $Class->table_x_localidad($representantes);?>
            </div>
            <div class="table-responsive col-lg-12" id="div-container-3"></div>
        </div>
        
        <div class="row">
            <div class="table-responsive col-lg-6">
                <div id="grafico_especialidad" style="height: 400px; max-width: 600px; margin: 0 auto"></div>
            </div>
            <div class="table-responsive col-lg-6">
                <div id="grafico_localidad" style="height: 400px; max-width: 600px; margin: 0 auto"></div>
            </div>
        </div>
        <?php
    }
    public function cobertura()
    {
        $representantes = $_POST['representantes'];
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];

        $Class = new MedmarkosModel();
        print $Class->table_visitas_realizada($representantes, $mes, $year);
    }
    public function listar_padron_medicos()
    {
        $representantes = $_POST['representantes'];
        $categorias = $_POST['categorias'];
        $var_data = $_POST['var_data'];
        $tipo = $_POST['tipo'];

        $Class = new MedmarkosModel();
        print $Class->listar_padron_medicos($representantes, $categorias, $var_data, $tipo);
    }
    public function cobertura_visitados()
    {
        $representantes = $_POST['representantes'];
        $especialidades = $_POST['especialidades'];
        $categorias = $_POST['categorias'];

        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];

        $Class = new MedmarkosModel();
        print $Class->cobertura_visitados($especialidades, $categorias, $representantes, $mes, $year);
    }
    //ACA
    public function cobertura_fecha_productos()
    {
        $codigo_medico = $_POST['codigo_medico'];
        $dia = $_POST['dia'];
        $mes = $_POST['mes'];
        $year = $_POST['year'];
        $representantes = $_POST['representantes'];

        $Class = new MedmarkosModel();
        print $Class->cobertura_fecha_productos($codigo_medico, $dia, $mes, $year, $representantes);
    }
    public function cobertura_no_visitados()
    {
        $representantes = $_POST['representantes'];
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];
        $especialidades = $_POST['especialidades'];
        $categorias = $_POST['categorias'];

        $Class = new MedmarkosModel();
        print $Class->cobertura_no_visitados($especialidades, $categorias, $representantes, $mes, $year);
    }
    public function table_realizadas_detalle()
    {
        $representantes = $_POST['representantes'];
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];

        $Class = new MedmarkosModel();
        print $Class->table_realizadas_detalle($representantes, $mes, $year);
    }
    public function table_medicos_x_dia()
    {
        $region = $_POST['region'];
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];

        $Class = new MedmarkosModel();
        print $Class->table_medicos_x_dia_2($region, $mes, $year);
        // print $Class->table_medicos_x_dia($representantes, $mes, $year);
    }
    public function cobertura_visitados_detalle()
    {
        $representantes = $_POST['representantes'];
        $fecha = $_POST['fecha'];

        $Class = new MedmarkosModel();
        print $Class->cobertura_visitados_detalle($representantes, $fecha);
    }
    public function cobertura_productos_modal_v2()
    {
        $representantes = $_POST['representantes'];
        $med_cod = $_POST['med_cod'];
        $fecha = $_POST['fecha'];

        $Class = new MedmarkosModel();
        print $Class->cobertura_productos_modal_v2($representantes, $med_cod, $fecha);
    }
    public function table_stock_count()
    {
        $representantes = $_POST['representantes'];
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];

        $Class = new MedmarkosModel();
        print $Class->table_stock_count($representantes, $mes, $year);
    }
    public function table_stock_count_all()
    {
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];

        $Class = new MedmarkosModel();
        print $Class->table_stock_count_all($mes, $year);
    }
    public function table_medicos_x_dia_total()
    {
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];

        $Class = new MedmarkosModel();
        print $Class->table_medicos_x_dia_total($mes, $year);
    }
    public function view_medicos_x_producto()
    {
        $representantes = $_POST['repre_cod'];
        $fecha = $_POST['fecha'];
        $prod_cod = $_POST['prod_cod'];

        $Class = new MedmarkosModel();
        print $Class->view_medicos_x_producto($representantes, $fecha, $prod_cod);
    }
    public function propagandistas_muestras()
    {
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];
        $region = $_POST['region_src'];

        $Class = new MedmarkosModel();
        print $Class->propagandistas_muestras($mes, $year, $region);
    }
    public function medicos_productos_cantidad_periodo()
    {
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];
        $representantes = $_POST['representantes'];

        $Class = new MedmarkosModel();
        print $Class->medicos_productos_cantidad_periodo($representantes, $mes, $year);
    }
    public function stock_entregado_resumen()
    {
        $periodo = yearmes_to_year_month($_POST['periodo']);
        
        $mes = $periodo['mes'];
        $year = $periodo['year'];
        $representantes = $_POST['representantes'];

        $Class = new MedmarkosModel();       
        print $Class->stock_entregado_resumen($representantes, $mes, $year);
    }
    public function padron_medico_x_represesntante()
    {
        $representantes = $_POST['repre'];

        $Class = new MedmarkosModel();        
        print $Class->padron_medico_x_represesntante($representantes);
    }
    public function medicos_visitados_x_dia()
    {
        $representantes = $_POST['repre'];
        $year = $_POST['year'];
        $mes = $_POST['mes'];
        $zonag = $_POST['zonag'];

        $Class = new MedmarkosModel();       
        print $Class-> medicos_visitados_x_dia($zonag, $representantes, $year, $mes);
    }

}