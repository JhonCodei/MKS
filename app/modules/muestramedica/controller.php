<?php

Class MuestramedicaController
{
    public function __construct()
    {
        is_session_true(); 
        
        $__file__ = "muestramedica";

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
    public function muestra_medica_form()
    {
        $id = $_POST['id'];

        $Class = new MuestramedicaModel();
        print $Class->muestra_medica_form($id);
    }
    public function muestra_medica_form_direct()
    {
        $output = ' <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight" id="basic-addon1" style="font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">Representante</span>
                        <input type="text" readonly="true" class="form-control input-sm border-prsnl text-center" style="width:30%;font-weight: bold;border-color:#4D7CAE;" placeholder="Codigo" value="'.zerofill(info_usuario('codigo'), 3).'">
                        <input type="text" readonly="true" class="form-control input-sm border-prsnl text-center" style="width:70%;font-weight: bold;border-color:#4D7CAE;" placeholder="Representante" value="'.info_usuario('nombre').'">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight" style="font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">Fecha</span>                       
                        <input type="text" class="form-control text-center input-sm border-prsnl" id="fecha_entrega_mm" value="'.date("d/m/Y").'" style="font-weight: bold;width:50%;border-color:#4D7CAE;" readonly="true">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight" style="font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">Estado</span>
                        <select class="form-control form-control-sm input-sm border-prsnl" id="status_mm" style="height:29.5px !important;width:100%;font-weight: bold;border-color:#4D7CAE;" onchange="return change_estado();">
                            <option value="1" selected> Emitido </option>
                            <option value="2"> Visita sin muestra medica </option>
                            <option value="0"> Visita no presente </option>
                        </select>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight waves-effect waves-light" onclick="return load_table_modal_event(0);" style="cursor:pointer;font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">
                        <i class="fa fa-search"></i>&nbsp;Médico</span>
                        <input style="font-weight: bold;border-color:#4D7CAE;width:50%;" type="text" class="form-control text-center input-sm border-prsnl" onkeypress="return max_length(this.value, 16);" placeholder="CMP"  aria-describedby="basic-addon1" id="med_cmp">
                        <span class="input-group-addon input-sm bg-danger text-white font-weight waves-effect waves-light" onclick="return clear_text('."'med_cmp','med_name'".');" style="cursor:pointer;font-weight: bold;border-color:#4D7CAE;">
                        <i class="fa fa-eraser"></i>&nbsp;</span>
                    </div>
                    <div class="input-group">
                        <input style="font-weight: bold;border-color:#4D7CAE;" type="text" readonly="true" class="form-control text-center input-sm border-prsnl"  placeholder="-" aria-describedby="basic-addon1" id="med_name">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight waves-effect waves-light" onclick="return load_table_modal_event(1);" style="cursor:pointer;font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">
                        <i class="fa fa-search"></i>&nbsp;Supervisor</span>
                        <input type="text" onkeypress="return max_length(this.value, 3);" class="form-control text-center input-sm border-prsnl" style="font-weight: bold;width:40%;border-color:#4D7CAE;" placeholder="Codigo" id="cod_sup">
                        <span class="input-group-addon input-sm bg-danger text-white font-weight waves-effect waves-light" onclick="return clear_text('."'cod_sup','name_sup'".');" style="cursor:pointer;font-weight: bold;border-color:#4D7CAE;">
                        <i class="fa fa-eraser"></i>&nbsp;</span>
                    </div>
                    <div class="input-group">
                        <input type="text" readonly="true" class="form-control text-center input-sm border-prsnl"  placeholder="-" style="font-weight: bold;width:50%;border-color:#4D7CAE;" aria-describedby="basic-addon1" id="name_sup">
                    </div>
                    <div class="input-group" style="padding-top:10px;">
                        <button type="button" onclick="return insert_muestra_medica();" style="font-weight: normal;border-color:#21ABA5 !important;border-width: 2px !important;" id="button_on_insert" class="col-lg-12 btn btn-default btn-sm waves-effect waves-light" >
                        <span class="fa fa-plus"></span>&nbsp;Guardar</button>
                    </div>
                    <div class="input-group" style="padding-top:10px;padding-bottom:-100px;">
                        <button type="button" class="col-lg-12 btn btn-danger waves-effect waves-light" style="font-weight: normal;"  onclick="close_modal_mm_await();">
                        <span class="fa fa-close"></span>&nbsp;Cancelar</button>
                    </div>';
                    
            #onblur="return load_table_modal_event(0);" onkeyup="clear_after_input('."'med_cmp'".','."'med_name'".');"
            #onkeyup="clear_after_input('."'cod_sup'".','."'name_sup'".');" onblur="return load_table_modal_event(1);"
        print $output;
    }
    public function  _buscar_supervisor()
    {
        $cod_sup = $_POST['cod_sup'];

        if(empty($cod_sup))
        {
            $cod_sup = 'S';
        }

        $Class = new MuestramedicaModel();
        print $Class->_buscar_supervisor($cod_sup);
    }
    public function  _buscar_medicos()
    {
        $user_session = info_usuario('codigo');
        
        $cmp = $_POST['med_cod'];

        if(strpos($cmp, "*") !== FALSE)
        {
            $cmp_ex = explode("*", $cmp);
            $cmp = $cmp_ex[0];
            $correlativo = (int)$cmp_ex[1];
        }else
        {
            $correlativo = 1;
        }

        if(empty($cmp))
        {
            $cmp = 'S';
        }
        if(empty($correlativo))
        {
            $correlativo = 1;
        }

        $Class = new MuestramedicaModel();
        print $Class->_buscar_medicos($cmp, $correlativo, $user_session);
    }
    public function _buscar_producto()
    {
        $fecha = $_POST['fecha'];
        $target = $_POST['target'];

        if(empty($fecha) || strpos($fecha, "/") === FALSE)
        {
            print 8;
        }else
        {
            $fecha_ex = explode("/", $fecha);
            $mes = (int)$fecha_ex[1];
            $year = (int)$fecha_ex[2];

            if($mes < 10)
            {
                $mes = '0'.$mes;
            }
            
            $periodo = $year.$mes;
            $user_session = info_usuario('codigo');
            $prod_cod = $_POST['prod_cod'];

            if(empty($prod_cod))
            {
                $prod_cod = 'S';
            }

            $Class = new MuestramedicaModel();
            print $Class->_buscar_producto($periodo, $user_session, $prod_cod, $target);
        }        
    }
    public function insert_muestra_medica()
    {
        $estado_1_vacio = false;

        $array_prod_cod = array();
        $array_prod_name = array();
        $array_prod_cant = array();
        $array_prod_stock = array();

        $user_session = info_usuario('codigo');

        $med_cod_array = $_POST['med_cmp'];

        $prod_cod = $_POST['prod_cod'];
        $prod_name = $_POST['prod_desc'];
        $prod_cant = $_POST['prod_cant'];
        $stock = $_POST['stock'];

        $fecha_mm = formatfecha($_POST['fecha_entrega_mm']);

        $status_mm = $_POST['status_mm'];
        $supervisor_mm = $_POST['supervisor_mm'];

        $fecha_ex = explode("/", $_POST['fecha_entrega_mm']);
        $mes = (int)$fecha_ex[1];
        $year = (int)$fecha_ex[2];

        $prod_cod_r = explode(',', $prod_cod);
        $prod_name_r = explode(',', $prod_name);
        $prod_cant_r = explode(',', $prod_cant);
        $stock_r = explode(',', $stock);        

        if($status_mm == 1)
        {
            #clear 0
            for ($c = 0; $c <= count($prod_cod_r) - 1; $c++)
            { 
                if($prod_cod_r[$c] != 0 && $prod_cod_r[$c] != null)
                {
                    if($prod_cant_r[$c] != 0 && $prod_cant_r[$c] != null)
                    {
                        if($prod_cant_r[$c] <= $stock_r[$c])
                        {
                            $array_prod_cod[] = $prod_cod_r[$c];
                            $array_prod_name[] = $prod_name_r[$c];
                            $array_prod_cant[] = $prod_cant_r[$c];
                            $array_prod_stock[] = $stock_r[$c];
                        }else
                        {
                            print 'La cantidad es mayor al stock!';
                            return false; 
                        }
                    }else
                    {
                        print "Ingrese cantidad!";
                        return false;
                    }
                }
            }
        }
        /*EXCEPTIONS*/

        if(strlen($_POST['fecha_entrega_mm']) == 0){print "Ingrese una fecha";return false;}

        if($med_cod_array == null){print "Ingrese el CMP del medico";return false;}
            
        if(strpos($med_cod_array, "*") !== FALSE){$cmp_ex = explode("*", $med_cod_array);$med_cod = $cmp_ex[0];}else{$med_cod = $med_cod_array;}

        if(strlen($med_cod) > 16){print "Verifique la cantidad de digitos del CMP";return false;}

        if($status_mm == 1){$prod_cod_repe = repetidos_array(array_filter($array_prod_cod));}else{$prod_cod_repe = 0;}

        if(!intval(trim($med_cod))){print 'CMP invalido (debe ser númerico)';return false;}

        if(validate_mm_cmp_today($med_cod, $fecha_mm, $user_session) != 0){print 'El medico ya fue registrado en la misma fecha.';return false;}
        
        #validar medico por categoria _vendedor
        $cmp_validate_periodo = validate_cmp_x_cat($med_cod, $fecha_mm ,$user_session);
        $ingreso = $cmp_validate_periodo['ingreso'];#true-false;

        if($ingreso == false){print $cmp_validate_periodo['msj'];return false;}
        #validar medico por categoria _vendedor

        /*</>EXCEPTIONS*/
        
        if($prod_cod_repe == 0)
        {
            if($mes < 10)
            {
                $mes = '0'.$mes;
            }    
    
            $periodo = (int)$year.$mes;
                
            $year_actual = date('Y');
            $mes_actual = date('m');
            $dia_actual = date('d');
    
            $fecha_mm_ex = explode("-", $fecha_mm);

            $year_explo = $fecha_mm_ex[0];
            $mes_explo = $fecha_mm_ex[1];
            $dia_explo = $fecha_mm_ex[2];

            #if($year_actual == $year_explo && $mes_actual == $mes_explo && $dia_explo <= $dia_actual)
            #{# ACTIVAR MES ACTUAL
                /* VALIDAR MAXIMO N DIAS*/
            $max_days = max_days(date('Y-m-d'), $fecha_mm);

            $estado_module = __modules_permissions__('muestramedica');
            $_split_module = explode("~", $estado_module);

            $_status_ = $_split_module[0];
            $_fecha_i_ = $_split_module[1];
            $_fecha_f_ = $_split_module[2];

            // if($user_session != 999)##AUTORIZADOS COD REPRE ##&& $user_session != 0
            // {
            //     if($max_days > 2)
            //     {
            //         print 'Solo se permite el ingreso con un maximo de 2 días !';
            //         return false;
            //     }
            // }

            if($_status_ == "FALSE")##AUTORIZADOS COD REPRE ##&& $user_session != 0
            {
                if($max_days > 2)
                {
                    print 'Solo se permite el ingreso con un maximo de 2 días !';
                    return false;
                }
            }

                /* VALIDAR MAXIMO N DIAS*/
            if($status_mm == 1)
            {
                if(empty($array_prod_cod) || empty($array_prod_cant))
                {
                    $estado_1_vacio = true;
                }
            }
                
            if($estado_1_vacio == true)
            {
                print 'Estado "EMITIDO", debe ingresar al menos 1 producto.';
                return false;
            }else
            {   
                $Class = new MuestramedicaModel();
                print $Class->insert_muestra_medica($user_session, $med_cod, $array_prod_cod, $array_prod_name, $array_prod_cant, $fecha_mm, $status_mm, $supervisor_mm, $periodo, $array_prod_stock);
            }

            /*}else
            {# FUERA DE MES
                print "Fuera de fecha";
                return false;
            }*/
        }else
        {
            $order = array_search(current($prod_cod_repe), $array_prod_cod);
            print "El producto ".$array_prod_name[$order]. ", esta duplicado.";
            return false;
        }# ACTIVAR MES ACTUAL   
    }
    public function listado_mm()
    {
        $user_session = info_usuario('codigo');
        $fecha__mm = formatfecha($_POST['fecha__mm']);

        $Class = new MuestramedicaModel();
        print $Class->listado_mm($user_session, $fecha__mm);
    }
    public function table_medicos_x_dia()
    {
        $user_session = info_usuario('codigo');

        $fecha_ = formatfecha($_POST['fecha__mm']);

        $fecha_ex = explode("-", $fecha_);
        $mes = (int)$fecha_ex[1];
        $year = (int)$fecha_ex[0];

        $Class = new MuestramedicaModel();
        print $Class->table_medicos_x_dia($user_session, $mes, $year);
    }
    public function eliminar_mm()
    {
        $user_session = info_usuario('codigo');
        $cmp = $_POST['cmp'];
        $fecha = $_POST['fecha'];
        $today = date('Y-m-d');
        /* 2 dias como maximo */
        $newtoday = strtotime('-2 day', strtotime($today));
        $newtoday2 = date("Y-m-d", $newtoday);
        
        /*TODOS*/
        // $Class = new MuestramedicaModel();
        // print $Class->eliminar_mm($cmp, $fecha, $user_session);
        /**/
        $estado_module = __modules_permissions__('muestramedica');
        $_split_module = explode("~", $estado_module);

        $_status_ = $_split_module[0];
        $_fecha_i_ = $_split_module[1];
        $_fecha_f_ = $_split_module[2];


        if($_status_ == "TRUE")##AUTORIZADOS COD REPRE ###|| $user_session == 0
        {
            $Class = new MuestramedicaModel();
            print $Class->eliminar_mm($cmp, $fecha, $user_session);
        }else
        {
            if($newtoday2 <= $fecha)
            {
                $Class = new MuestramedicaModel();
                print $Class->eliminar_mm($cmp, $fecha, $user_session);
            }else
            {
                print 0;
                return false;
            }
        }

        /* 2 dias como maximo */

        /* SOLO ELIMIANR EL MISMO DIA*/
    }
    public function cobertura_visitados_detalle()
    {
        $representantes = $_POST['representantes'];
        $fecha = $_POST['fecha'];

        $Class = new MuestramedicaModel();
        print $Class->cobertura_visitados_detalle($representantes, $fecha);
    }
    public function cobertura_fecha_productos()
    {
        $codigo_medico = $_POST['codigo_medico'];
        $dia = $_POST['dia'];
        $mes = $_POST['mes'];
        $year = $_POST['year'];
        $representantes = $_POST['representantes'];

        $Class = new MuestramedicaModel();
        print $Class->cobertura_fecha_productos($codigo_medico, $dia, $mes, $year, $representantes);
    }
    public function update_columns_medpro()
    {
        $repre = $_POST['cod_repre'];
        $periodo = $_POST['periodo'];

        $periodo_array = yearmes_to_year_month($periodo);
        $mes = $periodo_array['mes'];
        $year = $periodo_array['year'];

        $Class = new MuestramedicaModel();
        print $Class->update_columns_medpro($repre, $year, $mes);
    }
    public function productos_stock_representantes()
    {
        $fecha = formatfecha($_POST['fecha_mm']);
        $fecha_ex = explode("-", $fecha);
        $mes = (int)$fecha_ex[1];
        $year = (int)$fecha_ex[0];

        if($mes < 10)
        {
            $mes = '0'.$mes;
        }
        $periodo = $year.$mes;
        $user_session = info_usuario('codigo');

        $Class = new MuestramedicaModel();
        print $Class->productos_stock_representantes($periodo, $user_session);
    }
    public function onsite_modal()
    {
        $output = ' <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight" id="basic-addon1" style="font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">Representante</span>
                        <input type="text" readonly="true" class="form-control input-sm border-prsnl text-center" style="width:30%;font-weight: bold;border-color:#4D7CAE;" placeholder="Codigo" value="'.zerofill(info_usuario('codigo'), 3).'">
                        <input type="text" readonly="true" class="form-control input-sm border-prsnl text-center" style="width:70%;font-weight: bold;border-color:#4D7CAE;" placeholder="Representante" value="'.info_usuario('nombre').'">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight" style="font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">Estado</span>
                        <input style="font-weight: bold;border-color:#4D7CAE;" type="text" readonly="true" class="form-control text-center input-sm border-prsnl"  value="Marcacion" aria-describedby="basic-addon1" id="status_mm">
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight waves-effect waves-light" onclick="return load_table_modal_event(0);" style="cursor:pointer;font-weight: bold;border-color:#4D7CAE;background-color:#4D7CAE !important;">
                        <i class="fa fa-search"></i>&nbsp;Médico</span>
                        <input style="font-weight: bold;border-color:#4D7CAE;width:50%;" type="text" class="form-control text-center input-sm border-prsnl" onkeypress="return max_length(this.value, 16);" placeholder="CMP"  aria-describedby="basic-addon1" id="med_cmp">
                        <span class="input-group-addon input-sm bg-danger text-white font-weight waves-effect waves-light" onclick="return clear_text('."'med_cmp','med_name'".');" style="cursor:pointer;font-weight: bold;border-color:#4D7CAE;">
                        <i class="fa fa-eraser"></i>&nbsp;</span>
                    </div>
                    <div class="input-group">
                        <input style="font-weight: bold;border-color:#4D7CAE;" type="text" readonly="true" class="form-control text-center input-sm border-prsnl"  placeholder="-" aria-describedby="basic-addon1" id="med_name">
                    </div>
                    <div class="input-group" style="padding-top:10px;">
                        <button type="button" onclick="return insert_punto_marcacion();" style="font-weight: normal;border-color:#21ABA5 !important;border-width: 2px !important;" id="button_on_insert" class="col-lg-12 btn btn-default btn-sm waves-effect waves-light" >
                        <span class="fa fa-plus"></span>&nbsp;Guardar</button>
                    </div>
                    <div class="input-group" style="padding-top:10px;padding-bottom:-100px;">
                        <button type="button" class="col-lg-12 btn btn-danger waves-effect waves-light" style="font-weight: normal;"  onclick="close_modal_mm_await();">
                        <span class="fa fa-close"></span>&nbsp;Cancelar</button>
                    </div>';

        print $output;
    }
    public function insert_punto_marcacion()
    {
        $user_data = info_usuario('codigo');
        $med_cod_array = $_POST['med_cod'];
        $fecha_mm = date('Y-m-d');
        $estado = 4;
        $periodo = fecha_to_periodo($fecha_mm);

        if(strpos($med_cod_array, "*") !== FALSE){$cmp_ex = explode("*", $med_cod_array);$med_cod = $cmp_ex[0];}else{$med_cod = $med_cod_array;}
        
        if(validate_mm_cmp_today($med_cod, $fecha_mm, $user_data) != 0){print 2;return false;}

        if(validate_mm_cmp_today_punto($med_cod, $fecha_mm, $user_data, $estado) != 0){print 2;return false;}
               
        $Class = new MuestramedicaModel();
        print $Class->insert_punto_marcacion($user_data, $med_cod, $fecha_mm, $estado, $periodo);
    }
    public function finalizar_marcacion()
    {
        $user_data = info_usuario('codigo');
        $med_cod_array = $_POST['med_cod'];
        $fecha_mm = $_POST['fecha'];
        $today = date('Y-m-d');

        if(strpos($med_cod_array, "*") !== FALSE){$cmp_ex = explode("*", $med_cod_array);$med_cod = $cmp_ex[0];}else{$med_cod = $med_cod_array;}
        
        if(validate_mm_cmp_today($med_cod, $fecha_mm, $user_data) != 0){print 2;return false;}

        if(validate_mm_cmp_today_punto($med_cod, $fecha_mm, $user_data, 5) != 0){print 2;return false;}
        
        if($today == $fecha_mm)
        {       
            $Class = new MuestramedicaModel();
            print $Class->finalizar_marcacion($med_cod, $fecha_mm, $user_data);
        }else
        {
            print 3;
            return false;
        }
    }
    public function eliminar_ingreso_salida()
    {
        $user_session = info_usuario('codigo');
        $cmp = $_POST['cmp'];
        $fecha = $_POST['fecha'];
        $today = date('Y-m-d');
       
        $newtoday = strtotime('-2 day', strtotime($today));
        
        $newtoday2 = date("Y-m-d", $newtoday);
        
        if($today == $fecha)
        {
            $Class = new MuestramedicaModel();
            print $Class->eliminar_ingreso_salida($cmp, $fecha, $user_session);
        }else
        {
            print 0;
            return false;
        }
    }
    public function table_medicos_x_visitar()
    {
        $user_session = info_usuario('codigo');
        $fecha__mm = explode('-',formatfecha($_POST['fecha__mm']));

        $year = $fecha__mm[0];
        $month = $fecha__mm[1];

        $Class = new MuestramedicaModel();
        print $Class->table_medicos_x_visitar($year, $month, $user_session);
    }
    public function cobertura_representante()
    {
        $representantes = info_usuario('codigo');
        $fecha__mm = explode('-', formatfecha($_POST['fecha__mm']));

        $year = $fecha__mm[0];
        $mes = $fecha__mm[1];

        $Class = new MuestramedicaModel();
        print $Class->cobertura_representante($representantes, $mes, $year);
    }
    public function cobertura_visitados()
    {
        $representantes = $_POST['representantes'];
        $mes = $_POST['mes'];
        $year = $_POST['year'];
        $especialidades = $_POST['especialidades'];
        $categorias = $_POST['categorias'];

        $Class = new MuestramedicaModel();
        print $Class->cobertura_visitados($especialidades, $categorias, $representantes, $mes, $year);
    }
    public function cobertura_no_visitados()
    {
        $representantes = $_POST['representantes'];
        $mes = $_POST['mes'];
        $year = $_POST['year'];
        $especialidades = $_POST['especialidades'];
        $categorias = $_POST['categorias'];

        $Class = new MuestramedicaModel();
        print $Class->cobertura_no_visitados($especialidades, $categorias, $representantes, $mes, $year);
    } 


}