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
    public function _ruteo_cerrado_()
    {
        $datetime = date("Y-m-d H:i:s");
        $this->model->_ruteo_cerrado_($datetime);
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
                print "Ingrese cantidad!";
                return false;
            }
        }

        /* CONDICIONES */
        if($this->_ruteo_cerrado_() == TRUE){print "Fuera de fecha!";return false;}
               
        $cliente_repe = repetidos_array(array_filter($array_codigos));

        if($cliente_repe != 0)
        {
            $order = array_search(current($cliente_repe), $array_codigos);
            print "Programaci&oacute;n <b>".$array_clientes[$order]. "</b>, duplicado.";
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

}