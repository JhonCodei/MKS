<?php

Class PedidosController
{
    private $registrar;

    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "pedidos";

        __MODELS__($__file__);
        __SQL__($__file__);
        __FUNCTIONS__($__file__);

        $this->model = new PedidosModel();
        $this->max_days = 7;
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
    public function validar_cliente_ruc($ruc)
    {
        $class = new PedidosModel();
        return $class->validar_cliente_ruc($ruc);
    }
    public function _buscar_cliente()
    {
        $cliente_ruc = $_POST['cliente_ruc'];

        if(empty($cliente_ruc))
        {
            //$cliente_ruc = 'S';
            print "8";
            return false;
        }
        $Class = new PedidosModel();
        print $Class->_buscar_cliente($cliente_ruc);
    }
    public function _buscar_vendedor()
    {
        $cod_vend = $_POST['cod_vend'];

        if(empty($cod_vend))
        {
            $cod_vend = 'S';
        }
        $Class = new PedidosModel();
        print $Class->_buscar_vendedor($cod_vend);
    }
    public function _buscar_producto()
    {
        $target = $_POST['target'];
        $prod_cod = $_POST['prod_cod'];

        if(empty($prod_cod))
        {
            $prod_cod = 'S';
        }

        $Class = new PedidosModel();
        print $Class->_buscar_producto($prod_cod, $target);
    }
    public function precio_lista_x_prod_x_dist()
    {
        $periodo = fecha_to_periodo_del_($_POST['fecha'], "/");
        $producto = $_POST['producto'];
        $distribuidora = $_POST['distribuidora'];
        //$cantidad = $_POST['cantidad'];

        $Class = new PedidosModel();
        print $Class->precio_lista_x_prod_x_dist($periodo, $producto, $distribuidora);
    }
    public function __get_propietario($id)
    {
        $tipo_usuario = info_usuario('tipo_user');
        $usuario = info_usuario('codigo');

        if($tipo_usuario == 4 || $tipo_usuario == 5)
        {
            $Class = new PedidosModel();
            return $Class->__get_propietario($id, $usuario);
        }else
        {
            return TRUE;
        }
    }
    public function search_repre_exist_lima($vendedor)
    {
        $Class = new PedidosModel();
        return $Class->search_repre_exist_lima($vendedor);
    }
    public function __get_status($id)
    {
        $Class = new PedidosModel();
        return $Class->__get_status($id);
    }
    public function _listar_pedidos()
    {
        $vendedor = info_usuario('codigo');
        $fecha = formatfecha($_POST['fecha']);
        
        $Class = new PedidosModel();
        print $Class->_listar_pedidos($vendedor, $fecha);
    }
    public function _insertar_pedido()
    {
        $representantes_libres = array(756);
        
        $user_session = info_usuario('codigo');
        $estado_1_vacio = false;

        $array_prod_cod = array();
        $array_prod_name = array();
        $array_prod_cant = array();
        $array_prod_cod = array();
        $array_prod_desc = array();
        $array_prod_cant = array();
        $array_prec_list = array();
        $array_porc_dscu = array();
        $array_prec_uni = array();
        $array_prec_uni_igv = array();
        $array_monto_desc = array();
        $array_valor_neto = array();
        $array_observaciones = array();
        $array_monto_line_total = array();
        
        $fecha = formatfecha($_POST['fecha']);
        $condicion_pago = $_POST['cond_pago'];
        $distribuidora = $_POST['distribuidora'];
        $cliente = $_POST['ruc'];
        $cli_name = $_POST['cli_name'];
        $vendedor = $_POST['cod_vend'];
        $notas = $_POST['notas'];
        $especial = $_POST['especial'];

        $vendedor_registro = $user_session;

        $prod_cod_get = $_POST['prod_cod'];
        $prod_name_get = $_POST['prod_desc'];
        $prod_cant_get = $_POST['prod_cant'];
        $prec_list_get = $_POST['prec_list'];
        $porc_dscu_get = $_POST['porc_dscu'];
        $prec_uni_get = $_POST['prec_uni'];
        $prec_uni_igv_get = $_POST['prec_uni_igv'];
        $monto_desc_get = $_POST['monto_desc'];
        $valor_neto_get = $_POST['valor_neto'];
        $monto_line_total_get = $_POST['monto_line_total'];
        $observaciones_get = $_POST['observaciones'];

        #$estado_ = $_POST['estado'];

        if(strpos($prod_cod_get, '||') !== FALSE)
        {
            $prod_cod = explode("||", $prod_cod_get);
            $prod_cant = explode("||", $prod_cant_get);
            $prod_name = explode("||", $prod_name_get);
            $prec_list = explode("||", $prec_list_get);
            $porc_dscu = explode("||", $porc_dscu_get);
            $prec_uni = explode("||", $prec_uni_get);
            $prec_uni_igv = explode("||", $prec_uni_igv_get);
            $monto_desc = explode("||", $monto_desc_get);
            $valor_neto = explode("||", $valor_neto_get);
            $monto_line_total = explode("||", $monto_line_total_get);
            $observaciones = explode("||", $observaciones_get);
        }else
        {
            $prod_cod[] = $prod_cod_get;
            $prod_cant[] = $prod_cant_get;
            $prod_name[] = $prod_name_get;
            $prec_list[] = $prec_list_get;
            $porc_dscu[] = $porc_dscu_get;
            $prec_uni[] = $prec_uni_get;
            $prec_uni_igv[] = $prec_uni_igv_get;
            $monto_desc[] = $monto_desc_get;
            $valor_neto[] = $valor_neto_get;
            $monto_line_total[] = $monto_line_total_get;
            $observaciones[] = $observaciones_get;
        }

        $count  = count($prod_cod);

        for ($c = 0; $c <= $count - 1; $c++)
        { 
            if($prod_cod[$c] != 0 && $prod_cod[$c] != null)
            {
                if($prod_cant[$c] != 0 && $prod_cant[$c] != null)
                {
                    if($prec_list[$c] != 0 && $prec_list[$c] != null)
                    {
                        $array_prod_cod[] = $prod_cod[$c];
                        $array_prod_cant[] = $prod_cant[$c];
                        $array_prod_name[] = $prod_name[$c];
                        $array_prec_list[] = $prec_list[$c];
                        $array_porc_dscu[] = $porc_dscu[$c];
                        $array_prec_uni[] = $prec_uni[$c];
                        $array_prec_uni_igv[] = $prec_uni_igv[$c];
                        $array_monto_desc[] = $monto_desc[$c];
                        $array_valor_neto[] = $valor_neto[$c];
                        $array_monto_line_total[] = $monto_line_total[$c];
                        $array_observaciones[] = $observaciones[$c];
                    }else
                    {
                        print 'El precio de lista, no puede ser <b>"0".</b>';
                        return false;
                    }
                }else
                {
                    print "Ingrese cantidad!";
                    return false;
                }
            }
        }

        /* CONDICIONES */
        
        $cod_repre_exceptions = array(756);
        $usuario_tipo = info_usuario('tipo_user');

        if($usuario_tipo == 5  && !in_array($user_session, $cod_repre_exceptions))
        {
            if($vendedor == null || strlen($vendedor) == 0)
            {
                print "Ingrese codigo de su vendedor";
                return false;
            }else
            {
                if($this->search_repre_exist_lima($vendedor) == false)
                {
                    print "El vendedor no existe."; 
                    return false;
                }
            }
        }else
        {
            $vendedor = info_usuario('codigo');
        }
                
        $prod_cod_repe = repetidos_array(array_filter($array_prod_cod));

        if($prod_cod_repe != 0)
        {
            $order = array_search(current($prod_cod_repe), $array_prod_cod);
            print "El producto <b>".$array_prod_name[$order]. "</b>, esta duplicado.";
            return false;
        }

        if(strlen($_POST['fecha']) == 0){print "Ingrese una fecha";return false;}
        if(strlen($cliente) != 11){print "Verifique la cantidad de digitos del RUC";return false;}
        if(!intval(trim($cliente))){print 'RUC invalido (debe ser númerico)';return false;}
        if(!intval(trim($vendedor))){print 'Vendedor invalido (debe ser númerico) '.$vendedor;return false;}
        if(empty($array_prod_cod) || empty($array_prod_cant)){$estado_1_vacio = true;} 
        
        $year_actual = date('Y');
        $mes_actual = date('m');
        $dia_actual = date('d');

        $fecha_ex = explode("-", $fecha);

        $year_explo = $fecha_ex[0];
        $mes_explo = $fecha_ex[1];
        $dia_explo = $fecha_ex[2];

        $today = date("Y-m-d");

        $dias_pasados = dias_pasados($fecha, $today);

        /*  dias como maximo */
        $newtoday = strtotime('-7 day', strtotime($today));
        $newtoday2 = date("Y-m-d", $newtoday);
        

        if($dias_pasados <= $this->max_days)// && !in_array($user_session, $representantes_libres)
        {
            if($estado_1_vacio == true)
            {
                print 'Debe ingresar al menos 1 producto.';
                return false;
            }else
            {
                #$this->validar_cliente_ruc($cliente);#busca ruc valida cliente.

                $pedidos = new PedidosModel();
                $pedidos->cliente = $cliente;
                $pedidos->condicion_pago = $condicion_pago;
                $pedidos->fecha = $fecha;
                $pedidos->distribuidora = $distribuidora;
                $pedidos->especial = $especial;
                $pedidos->vendedor = $vendedor;
                $pedidos->notas = $notas;
                $pedidos->vendedor_registro = $vendedor_registro;
                $pedidos->array_prod_cod = $array_prod_cod;
                $pedidos->array_prod_name = $array_prod_name;
                $pedidos->array_prod_cant = $array_prod_cant;
                $pedidos->array_prec_list = $array_prec_list;
                $pedidos->array_porc_dscu = $array_porc_dscu;
                $pedidos->array_prec_uni = $array_prec_uni;
                $pedidos->array_prec_uni_igv = $array_prec_uni_igv;
                $pedidos->array_monto_desc = $array_monto_desc;
                $pedidos->array_valor_neto = $array_valor_neto;
                $pedidos->array_observaciones = $array_observaciones;
                $pedidos->array_monto_line_total = $array_monto_line_total;

                print $this->model->_insertar_pedido($pedidos);
            }
        }else
        {
            print "Fuera de fecha";
            return false;
        }

        /* CONDICIONES */

    }
    public function _buscar_pedido()
    {
        $id = $_POST['id'];
        if($this->__get_propietario($id))
        {
            $Class = new PedidosModel();
            print $Class->_buscar_pedido($id);
        }else
        {
            print "404";
        }
    }
    public function _actualizar_pedido()
    {
        $representantes_libres = array(756);

        $user_session = info_usuario('codigo');
        $estado_1_vacio = false;

        $array_prod_cod = array();
        $array_prod_name = array();
        $array_prod_cant = array();
        $array_prod_cod = array();
        $array_prod_desc = array();
        $array_prod_cant = array();
        $array_prec_list = array();
        $array_porc_dscu = array();
        $array_prec_uni = array();
        $array_prec_uni_igv = array();
        $array_monto_desc = array();
        $array_valor_neto = array();
        $array_monto_line_total = array();
        $array_observaciones = array();
        
        $_id = $_POST['id'];
        $fecha = formatfecha($_POST['fecha']);
        $condicion_pago = $_POST['cond_pago'];
        $distribuidora = $_POST['distribuidora'];
        $cliente = $_POST['ruc'];
        $cli_name = $_POST['cli_name'];
        $vendedor = $_POST['cod_vend'];
        $notas = $_POST['notas'];
        $especial = $_POST['especial'];

        $vendedor_registro = $user_session;

        $prod_cod_get = $_POST['prod_cod'];
        $prod_name_get = $_POST['prod_desc'];
        $prod_cant_get = $_POST['prod_cant'];
        $prec_list_get = $_POST['prec_list'];
        $porc_dscu_get = $_POST['porc_dscu'];
        $prec_uni_get = $_POST['prec_uni'];
        $prec_uni_igv_get = $_POST['prec_uni_igv'];
        $monto_desc_get = $_POST['monto_desc'];
        $valor_neto_get = $_POST['valor_neto'];
        $monto_line_total_get = $_POST['monto_line_total'];
        $observaciones_get = $_POST['observaciones'];

        if(strpos($prod_cod_get, '||') !== FALSE)
        {
            $prod_cod = explode("||", $prod_cod_get);
            $prod_cant = explode("||", $prod_cant_get);
            $prod_name = explode("||", $prod_name_get);
            $prec_list = explode("||", $prec_list_get);
            $porc_dscu = explode("||", $porc_dscu_get);
            $prec_uni = explode("||", $prec_uni_get);
            $prec_uni_igv = explode("||", $prec_uni_igv_get);
            $monto_desc = explode("||", $monto_desc_get);
            $valor_neto = explode("||", $valor_neto_get);
            $monto_line_total = explode("||", $monto_line_total_get);
            $observaciones_ = explode("||", $observaciones_get);
        }else
        {
            $prod_cod[] = $prod_cod_get;
            $prod_cant[] = $prod_cant_get;
            $prod_name[] = $prod_name_get;
            $prec_list[] = $prec_list_get;
            $porc_dscu[] = $porc_dscu_get;
            $prec_uni[] = $prec_uni_get;
            $prec_uni_igv[] = $prec_uni_igv_get;
            $monto_desc[] = $monto_desc_get;
            $valor_neto[] = $valor_neto_get;
            $monto_line_total[] = $monto_line_total_get;
            $observaciones_[]= $observaciones_get;
        }

        $count  = count($prod_cod);

        for ($c = 0; $c <= $count - 1; $c++)
        { 
            if($prod_cod[$c] != 0 && $prod_cod[$c] != null)
            {
                if($prod_cant[$c] != 0 && $prod_cant[$c] != null)
                {
                    if($prec_list[$c] != 0 && $prec_list[$c] != null)
                    {
                        $array_prod_cod[] = $prod_cod[$c];
                        $array_prod_cant[] = $prod_cant[$c];
                        $array_prod_name[] = $prod_name[$c];
                        $array_prec_list[] = $prec_list[$c];
                        $array_porc_dscu[] = $porc_dscu[$c];
                        $array_prec_uni[] = $prec_uni[$c];
                        $array_prec_uni_igv[] = $prec_uni_igv[$c];
                        $array_monto_desc[] = $monto_desc[$c];
                        $array_valor_neto[] = $valor_neto[$c];
                        $array_monto_line_total[] = $monto_line_total[$c];
                        $array_observaciones[] = $observaciones_[$c];
                    }else
                    {
                        print 'El precio de lista, no puede ser <b>"0".</b>';
                        return false;
                    }
                }else
                {
                    print "Ingrese cantidad!";
                    return false;
                }
            }
        }

        /* CONDICIONES */
        if($this->__get_propietario($_id) == FALSE){print "Error...";return FALSE;}
        if($this->__get_status($_id) == FALSE){print "No se puede modificar";return FALSE;}

        $cod_repre_exceptions = array(756);
        $usuario_tipo = info_usuario('tipo_user');

        if($usuario_tipo == 5  && !in_array($user_session, $cod_repre_exceptions))
        {
            if($vendedor == null || strlen($vendedor) == 0)
            {
                print "Ingrese codigo de su vendedor";
                return false;
            }else
            {
                if($this->search_repre_exist_lima($vendedor) == false)
                {
                    print "El vendedor no existe."; 
                    return false;
                }
            }
        }else
        {
            $vendedor = info_usuario('codigo');
        }
                
        $prod_cod_repe = repetidos_array(array_filter($array_prod_cod));

        if($prod_cod_repe != 0)
        {
            $order = array_search(current($prod_cod_repe), $array_prod_cod);
            print "El producto <b>".$array_prod_name[$order]. "</b>, esta duplicado.";
            return false;
        }

        if(strlen($_POST['fecha']) == 0){print "Ingrese una fecha";return false;}
        if(strlen($cliente) != 11){print "Verifique la cantidad de digitos del RUC";return false;}
        if(!intval(trim($cliente))){print 'RUC invalido (debe ser númerico)';return false;}
        if(!intval(trim($vendedor))){print 'Vendedor invalido (debe ser númerico) '.$vendedor;return false;}
        if(empty($array_prod_cod) || empty($array_prod_cant)){$estado_1_vacio = true;} 
        
        $year_actual = date('Y');
        $mes_actual = date('m');
        $dia_actual = date('d');

        $fecha_ex = explode("-", $fecha);

        $year_explo = $fecha_ex[0];
        $mes_explo = $fecha_ex[1];
        $dia_explo = $fecha_ex[2];

        $today = date("Y-m-d");

        $dias_pasados = dias_pasados($fecha, $today);

        /*  dias como maximo */
        $newtoday = strtotime('-7 day', strtotime($today));
        $newtoday2 = date("Y-m-d", $newtoday);
        

        if($dias_pasados <= $this->max_days)// && !in_array($user_session, $representantes_libres)
        {
            if($estado_1_vacio == true)
            {
                print 'Debe ingresar al menos 1 producto.';
                return false;
            }else
            {
                #$this->validar_cliente_ruc($cliente);#busca ruc valida cliente.

                $pedidos = new PedidosModel();
                $pedidos->id = $_id;
                $pedidos->cliente = $cliente;
                $pedidos->condicion_pago = $condicion_pago;
                $pedidos->fecha = $fecha;
                $pedidos->distribuidora = $distribuidora;
                $pedidos->notas = $notas;
                $pedidos->especial = $especial;
                $pedidos->vendedor = $vendedor;
                $pedidos->vendedor_registro = $vendedor_registro;
                $pedidos->array_prod_cod = $array_prod_cod;
                $pedidos->array_prod_name = $array_prod_name;
                $pedidos->array_prod_cant = $array_prod_cant;
                $pedidos->array_prec_list = $array_prec_list;
                $pedidos->array_porc_dscu = $array_porc_dscu;
                $pedidos->array_prec_uni = $array_prec_uni;
                $pedidos->array_prec_uni_igv = $array_prec_uni_igv;
                $pedidos->array_monto_desc = $array_monto_desc;
                $pedidos->array_valor_neto = $array_valor_neto;
                $pedidos->array_observaciones = $array_observaciones;
                $pedidos->array_monto_line_total = $array_monto_line_total;

                print $this->model->_actualizar_pedido($pedidos);
            }
        }else
        {
            print "Fuera de fecha";
            return false;
        }

        /* CONDICIONES */



    }
    public function _eliminar_pedido()
    {
        $id = $_POST['id'];
        if($this->__get_propietario($id))
        {
            $Class = new PedidosModel();
            print $Class->_eliminar_pedido($id, $this->max_days);
        }else
        {
            print "404";
        }
    }
    
    

}