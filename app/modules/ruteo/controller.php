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

        $this->model = new RuteoModel();
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
    public function _ruteo_cerrado_($fecha = NULL, $user_session = NULL)
    {
        $data = $this->model;
        $data->datetime = date("Y-m-d");
        $data->fecha = $fecha;
        $data->vendedor = $user_session;
        $data->module = $__file__;

        $today = date("Y-m-d");

        $add_month = 3;#MESES DISPONIBLES

        $_split_today = explode('-', $today);
        $t_day = $_split_today[2];
        $t_month = $_split_today[1];
        $t_year = $_split_today[0];

        $_split_date = explode("-", $fecha);
        $year = $_split_date[0];
        $month = $_split_date[1];
        $day = $_split_date[2];

        $days_in_month = __days_in_month($month, $year);

        $build_pool_1 = NULL;
        $build_pool_2 = NULL;
        $_pool_dates = array();

        $ruteo_estado = $this->model->_ruteo_cerrado_($data);
        $_split_status = explode("~", $ruteo_estado);

        if($_split_status[0] == "TRUE")#SI HAY EXCEPCIONES SQL
        {
            $build_pool_1 = $_split_status[1];
            $build_pool_2 = $_split_status[2];
        }else
        {
            if($t_day <= 13)
            {
                $build_pool_1 = $t_year.'-'.$t_month.'-16';
                $build_pool_2 = date("Y-m-d", strtotime("+".$add_month." month", strtotime($build_pool_1)));

            }else if ($t_day <= 25)
            {
                $build_pool_1 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-01')));
                $build_pool_2 = date("Y-m-d", strtotime("+".$add_month." month", strtotime($build_pool_1))); 

            }else if ($t_day > 25 && $t_day <= $days_in_month)
            {
                $build_pool_1 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-16')));
                $build_pool_2 = date("Y-m-d", strtotime("+".$add_month." month", strtotime($build_pool_1)));
            }           
        }
        #####BUILD POOL FECHAS###
        for($i = $build_pool_1; $i <= $build_pool_2; $i = date("Y-m-d", strtotime($i ."+ 1 days")))
        {
            $_pool_dates[] = $i;
        }

        if(in_array($fecha, $_pool_dates))
        {
            return FALSE;
        }else
        {
            return TRUE;
        }
    }
    public function _check_ruteo_($fecha)#validando si hay ruteo ingresado ese dia
    {
        $fecha = formatfecha($fecha);
        $user_session = info_usuario('codigo');

        $this->model->_check_ruteo_($user_session, $fecha);
    }
    public function _buscar_modal()
    {
        #VISITA = 5 & VENTAS = 4#
        $btn = null;
        $element_id = $_POST['element_id'];
        $user_session = info_usuario('codigo');
        $user_tipo = info_usuario('tipo_user');

        $btn_medicos = '<button class="btn btn-sm waves-effect waves-light btn-primary" onclick="_buscar_medico('.$element_id.');"><span class="fa fa-user-md"></span> Medicos</button>&nbsp;';
        $btn_clientes = '<button class="btn btn-sm waves-effect waves-light btn-inverse" onclick="_buscar_cliente('.$element_id.');"><span class="fa fa-users"></span> Clientes</button>&nbsp;';
        $btn_clientes_k = '<button class="btn btn-sm waves-effect waves-light btn-default" onclick="alert(2);"><span class="fa fa-plus-square"></span> Clientes K.</button>&nbsp;';

        switch ($user_tipo)
        {
            case '4':
                $btn = $btn_clientes;
                break;
            case '5':
                $btn = $btn_medicos.$btn_clientes;
                break;            
            default:
                $btn = $btn_medicos.$btn_clientes;
                break;
        }
        if($user_session == 355)
        {
            $btn = $btn_clientes_k;
        }

        $output = '
                <div class="text-center" style="padding-bottom:10px;">
                <label class="h3">Busqueda</label>
                <button type="button" class=" waves-effect waves-light btn btn-sm btn-danger pull-right" data-dismiss="modal" aria-hidden="true" onclick="return close_modal('."'modal-events-mm'".');">
                    <span class="fa fa-close"></span></button>
                </div>
                <div class="modal-body text-center">                
                    <div>
                        '.$btn.'
                    </div>
                    <hr>
                    <div id="div_modal_body" class="table-responsive" style="width:100%;"></div>
                </div>';
                    
        print $output;
    }
    public function _buscar_medico()
    {
        $periodo = fecha_to_periodo(formatfecha($_POST['fecha']));
        $codigo_ = $_POST['codigo'];
        $user_session = info_usuario('codigo');

        $medico = new RuteoModel();

        $medico->codigo = $codigo_;
        $medico->vendedor = $user_session;
        $medico->_in = $_POST['_in'];
        $medico->periodo = $periodo;

        print $this->model->_buscar_medico($medico);
    }
    public function _buscar_cliente()
    {
        $periodo = fecha_to_periodo(formatfecha($_POST['fecha']));
        $user_tipo = info_usuario('tipo_user');

        $cliente = new RuteoModel();

        $cliente->codigo = $_POST['codigo'];
        $cliente->vendedor = info_usuario('codigo');
        $cliente->_in = $_POST['_in'];
        $cliente->periodo = $periodo;

        print $this->model->_buscar_cliente($cliente);
    }
    public function _insertar_ruteo()
    {       
        $estado_1_vacio = false;

        $array_horas = array();
        $array_codigos = array();
        $array_clientes = array();
        $array_objetivos = array();
        $array_importes = array();
        $array_observaciones = array();
        $array_tipos = array();
        
        $user_session = info_usuario('codigo');
        $fecha = formatfecha($_POST['fecha']);

        $horas_get = $_POST['horas'];
        $codigos_get = $_POST['codigos'];
        $clientes_get = $_POST['clientes'];
        $objetivos_get = $_POST['objetivos'];
        $importes_get = $_POST['importes'];
        $observaciones_get = $_POST['observaciones'];
        $tipos_get = $_POST['tipos'];

        #$estado_ = $_POST['estado'];

        if(strpos($horas_get, '||') !== FALSE)
        {
            $horas = explode("||", $horas_get);
            $codigos = explode("||", $codigos_get);
            $clientes = explode("||", $clientes_get);
            $objetivos = explode("||", $objetivos_get);
            $importes = explode("||", $importes_get);
            $observaciones = explode("||", $observaciones_get);
            $tipos = explode("||", $tipos_get);
        }else
        {
            $horas[] = $horas_get;
            $codigos[] = $codigos_get;
            $clientes[] = $clientes_get;
            $objetivos[] = $objetivos_get;
            $importes[] = $importes_get;
            $observaciones[] = $observaciones_get;
            $tipos[] = $tipos_get;
        }
        
        $count  = count($horas);
        # print_r($count); return false;
        for ($c = 0; $c < $count; $c++)
        { 
            if($horas[$c] != 0 && $horas[$c] != null)
            {
                $array_horas[] = $horas[$c];
                $array_codigos[] = $codigos[$c];
                $array_clientes[] = $clientes[$c];
                $array_objetivos[] = $objetivos[$c];
                $array_importes[] = $importes[$c];
                $array_observaciones[] = $observaciones[$c];
                $array_tipos[] = $tipos[$c]; 
            }else
            {
                print "Ingrese el codigo2";
                return false;
            }
        }

        /* CONDICIONES */
        #if($this->_ruteo_cerrado_() == TRUE){print "Fuera de fecha!";return FALSE;}
               
        $cliente_repe = repetidos_array(array_filter($array_codigos));
        $horas_repe = repetidos_array(array_filter($array_horas));

        if($cliente_repe != 0)
        {
            $order = array_search(current($cliente_repe), $array_codigos);
            print "Programaci&oacute;n <b>".$array_codigos[$order]. "</b>, duplicado.";
            return FALSE;
        }
        if($horas_repe != 0)
        {
            $order = array_search(current($horas_repe), $array_horas);
            print "Programaci&oacute;n <b>".$array_horas[$order]. "</b>, duplicado.";
            return FALSE;
        }

        if(strlen($_POST['fecha']) == 0){print "Ingrese una fecha";return FALSE;}
        // if(strlen($cliente) != 11){print "Verifique la cantidad de digitos del RUC";return false;}
        // if(!intval(trim($cliente))){print 'RUC invalido (debe ser númerico)';return false;}
        /* CONDICIONES */

        if($this->_ruteo_cerrado_($fecha, $user_session) == FALSE)
        {
            $ruteo = $this->model;

            $ruteo->user_session = $user_session;
            $ruteo->fecha = $fecha;
            $ruteo->array_horas = $array_horas;
            $ruteo->array_codigos = $array_codigos;
            $ruteo->array_clientes = $array_clientes;
            $ruteo->array_objetivos = $array_objetivos;
            $ruteo->array_importes = $array_importes;
            $ruteo->array_observaciones = $array_observaciones;
            $ruteo->array_tipos = $array_tipos;
            
            print $this->model->_insertar_ruteo($ruteo);
        }else
        {
            print "Ruteo cerrado!";
            return FALSE;
        }
    }
    public function _buscar_ruteo()
    {
        $fecha = formatfecha($_POST['fecha']);
        $user_session = info_usuario('codigo');

        print $this->model->_buscar_ruteo($user_session, $fecha);
    }
    public function _actualizar_ruteo()
    {       
        $estado_1_vacio = false;

        $array_horas = array();
        $array_codigos = array();
        $array_clientes = array();
        $array_objetivos = array();
        $array_importes = array();
        $array_observaciones = array();
        $array_tipos = array();
        
        $user_session = info_usuario('codigo');
        $fecha = formatfecha($_POST['fecha']);

        $horas_get = $_POST['horas'];
        $codigos_get = $_POST['codigos'];
        $clientes_get = $_POST['clientes'];
        $objetivos_get = $_POST['objetivos'];
        $importes_get = $_POST['importes'];
        $observaciones_get = $_POST['observaciones'];
        $tipos_get = $_POST['tipos'];

        if(strpos($horas_get, '||') !== FALSE)
        {
            $horas = explode("||", $horas_get);
            $codigos = explode("||", $codigos_get);
            $clientes = explode("||", $clientes_get);
            $objetivos = explode("||", $objetivos_get);
            $importes = explode("||", $importes_get);
            $observaciones = explode("||", $observaciones_get);
            $tipos = explode("||", $tipos_get);
        }else
        {
            $horas[] = $horas_get;
            $codigos[] = $codigos_get;
            $clientes[] = $clientes_get;
            $objetivos[] = $objetivos_get;
            $importes[] = $importes_get;
            $observaciones[] = $observaciones_get;
            $tipos[] = $tipos_get;
        }

        $count  = count($horas);

        for ($c = 0; $c < $count; $c++)
        { 
            if($codigos[$c] != 0 && $codigos[$c] != null)
            {
                $array_horas[] = $horas[$c];
                $array_codigos[] = $codigos[$c];
                $array_clientes[] = $clientes[$c];
                $array_objetivos[] = $objetivos[$c];
                $array_importes[] = $importes[$c];
                $array_observaciones[] = $observaciones[$c];
                $array_tipos[] = $tipos[$c]; 
            }else
            {
                print "Ingrese el codigo up";
                return false;
            }
        }

        /* CONDICIONES */
        if($this->_ruteo_cerrado_() == TRUE){print "Fuera de fecha!";return false;}
               
        $cliente_repe = repetidos_array(array_filter($array_codigos));
        $horas_repe = repetidos_array(array_filter($array_horas));

        if($cliente_repe != 0)
        {
            $order = array_search(current($cliente_repe), $array_codigos);
            print "Programaci&oacute;n <b>".$array_clientes[$order]. "</b>, duplicado.";
            return false;
        }
        if($horas_repe != 0)
        {
            $order = array_search(current($horas_repe), $array_horas);
            print "Programaci&oacute;n <b>".$array_horas[$order]. "</b>, duplicado.";
            return false;
        }

        if(strlen($_POST['fecha']) == 0){print "Ingrese una fecha";return false;}
        // if(strlen($cliente) != 11){print "Verifique la cantidad de digitos del RUC";return false;}
        // if(!intval(trim($cliente))){print 'RUC invalido (debe ser númerico)';return false;}

        /* CONDICIONES */

        if($this->_ruteo_cerrado_() == FALSE)
        {
            $ruteo = new RuteoModel();
            $ruteo->user_session = $user_session;
            $ruteo->fecha = $fecha;
            $ruteo->array_horas = $array_horas;
            $ruteo->array_codigos = $array_codigos;
            $ruteo->array_clientes = $array_clientes;
            $ruteo->array_objetivos = $array_objetivos;
            $ruteo->array_importes = $array_importes;
            $ruteo->array_observaciones = $array_observaciones;
            $ruteo->array_tipos = $array_tipos;
            
            print $this->model->_insertar_ruteo($ruteo);
        }else
        {
            print "Ruteo cerrado!";
            return false;
        }
    }
    public function _eliminar_ruteo()
    {
        $fecha = formatfecha($_POST['fecha']);
        $user_session = info_usuario("codigo");

        if($this->model->_eliminar_ruteo($user_session, $fecha))
        {
            print 1;
        }else
        {
            print "Error al eliminar";
        }
    }
    public function _listar_ruteo()
    {
        $fecha = formatfecha($_POST['fecha']);
        $user_session = info_usuario('codigo');

        print $this->model->_listar_ruteo($user_session, $fecha);
    }
    public function _listar_ruteo_pagos()
    {
        #$user_session = info_usuario('codigo');
        $periodo = $_POST['periodo'];
        $quincena = $_POST['quincena'];
       
        if(strlen($periodo) == 0){print "Error ingrese un periodo";return FALSE;}       

        $_split = yearmes_to_year_month($periodo);
        $year = $_split['year'];
        $month = $_split['mes'];

        $listado = $this->model;

        $listado->year = $year;
        $listado->month = $month;
        $listado->quincena = $quincena;
        
        print($this->model->_listar_ruteo_pagos($listado));
    }
    public function _print_ruteos()
    {
        $codigos = $_POST['codigos'];
        $correlativos = $_POST['correlativos'];

        $data = $_POST['data'];
        $_split = explode("-", $data);

        $year = $_split[0];
        $month = $_split[1];
        $quincena = $_split[2];
        $_min_day_ = $_split[3];
        $_max_day_ = $_split[4];

        $print = $this->model;
        
        $print->codigos = $codigos;
        $print->correlativos = $correlativos;
        $print->year = $year;
        $print->month = $month;
        $print->quincena = $quincena;
        $print->_min_day_ = $_min_day_;
        $print->_max_day_ = $_max_day_;

        $this->model->_print_ruteos($print);
    }
}