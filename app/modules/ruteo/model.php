<?php

Class RuteoModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
        require_once 'helpers/libraries/MPDF/mpdf.php';
    }
    #__functions__
    public function _ruteo_cerrado_($data)
    {
        $today = $data->datetime;
        $fecha = $data->fecha;
        $user_session = $data->vendedor;
        $module = $data->module;
        
        $output = "FALSE~NULL~NULL";

        $sql1 = $this->db->prepare(_sql_validate_permitions());
        $sql1->bindparam(":module", $module);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC);
                #$modulo = $r_sql1['modulo'];
                $usuarios = $r_sql1['usuarios'];
                $inicio = $r_sql1['inicio'];
                $fin = $r_sql1['fin'];
                
                if($usuarios == 'all')
                {
                    if(check_in_range_date($inicio, $fin, $fecha))
                    {
                        $output = "TRUE~".$inicio."~".$fin;
                    }
                    #else{ $output = "FALSE~".$inicio."~".$fin;}
                }else
                {
                    $_split_usuarios = explode(",", $usuarios);

                    if(in_array($user_session, $_split_usuarios) && check_in_range_date($inicio, $fin, $fecha))
                    {
                        $output = "TRUE~".$inicio."~".$fin;
                    }
                }
            }
        }
        return $output;
    }
    public function _check_ruteo_($user_session, $fecha)
    {
        $output = FALSE;

        $sql1 = $this->db->prepare(_sql_buscar_ruteo_());
        $sql1->bindparam(":vendedor", $user_session);
        $sql1->bindparam(":fecha", $fecha);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $output = TRUE;
            }
        }
        return $output;
    }
    public function _buscar_medico($data)
    {
        $id_codigo = $data->codigo;
        $representante = $data->vendedor;
        $periodo = $data->periodo;
        $_in = $data->_in;

        $output = null;
        $WHERE = null;
        $WHERE1 = null;

        $medicos = $this->db->prepare(_sql_buscar_medicos($representante, $_in));
        if(strlen($_in) == 0)
        {
            $medicos->bindparam(':user_session', $representante);
        }else
        {
            $medicos->bindparam(":cmp", $_in);
        } 
        
        if($medicos->execute())
        {
            if($medicos->rowCount() == 1)
            {
                $medicos_r = $medicos->fetch(PDO::FETCH_ASSOC);

                $cmp = $medicos_r['cmp'];
                $nombre = $medicos_r['nombre'];
                $correlative = $medicos_r['correlativo'];
                $categoria = $medicos_r['categoria'];
                $cmp_corr = $cmp.'*'.$correlative;

                $output = $cmp.'~~'.$nombre;
                $type = '1';

            }else if($medicos->rowCount() > 0)
            {
                   $output .= '<table id="table_listado_medicos" data-toggle="table" data-page-size="10" data-pagination="true" class="table-condensed table table-hover table-bordered table-sm" style="display: table;font-size:0.9em;">
                                    <thead class="text-white " style="background-color:#588D9C;">
                                        <th class="text-center">#</th>
                                        <th class="text-center">Codigo'.$codigo.'</th>
                                        <th class="text-center">Nombre</th>
                                        <th class="text-center">Cat.</th>
                                    </thead>
                                    <tbody style="color:black;font-family:verdana;font-size:0.85em;">';

                    while($medicos_r = $medicos->fetch(PDO::FETCH_ASSOC))
                    {
                        $cmp = $medicos_r['cmp'];
                        $nombre = $medicos_r['nombre'];
                        $correlative = $medicos_r['correlativo'];
                        $categoria = $medicos_r['categoria'];
                        $cmp_corr = $cmp.'*'.$correlative;

                        $output .= ' <tr>
                                        <td>
                                            <button class="btn btn-inverse btn-sm waves-effect waves-light" onclick="__send_values__('."'codigo_".$id_codigo."~~cliente_".$id_codigo."'".', '."'".$cmp."~~".$nombre."'".' , '."'val~~val'".', '."'modal-events-mm'".');"><span class="fa fa-plus"></span></button>
                                        </td>
                                        <td>'.$cmp.'</td>
                                        <td>'.$nombre.'</td>
                                        <td class="text-center">'.$categoria.'</td>
                                    </tr>';
                    }
                    $output .= '</tbody></table>';
                    $type = '0';

            }else
            {
                $output = $_in."~~No registrado";#$cmp.'*'.$correlativo.'~~No registrado';
                $type = '1';
            }
        }
        return $type.'||'.$output;
    }
    public function _zonas_supervisores($user_session)
    {
        $output = null;
        $region = 0;

        switch ($variable) {
            case '520':
                $region = 1;
                break;
            case '806':
                $region = 1;
                break;
            case '417':
                $region = 2;
                break;
            case '391':
                $region = 9;
                break;
            case '629':
                $region = 10;
                break;
            case '757':
                $region = 11;
                break;
            case '650':
                $region = 2;
                break;

            default:
                $region = 0;
                break;
        }

        $sql1 = $this->db->prepare(_sql_zonas_supervisores());
        $sql1->bindparam(":region", $region);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $r_sql = $sql1->fetch(PDO::FETCH_ASSOC);
                $output = $r_sql['zonas'];
            }
        }
        return $output;
    }
    public function _buscar_zonas($user_session)
    {
        $output = 0;

        $zonas = $this->db->prepare(_sql_buscar_zonas());
        $zonas->bindparam(":codigo", $user_session);
        if($zonas->execute())
        {
            if($zonas->rowCount() > 0)
            {
                $r_zonas = $zonas->fetch(PDO::FETCH_ASSOC);

                $codigo = $r_zonas['codigo'];
                $portafolio = $r_zonas['portafolio'];
                $region = $r_zonas['region'];
                $zona = $r_zonas['zona'];
                $zona_vista = $r_zonas['zona_vista'];
                $cargo = $r_zonas['cargo'];

                if($cargo == 6 || $cargo == 3)
                {
                    $output = $zona_vista;
                }else if($cargo == 2 || $cargo == 5)
                {
                    $output = $this->_zonas_supervisores($codigo);
                }else if($cargo == 1)
                {
                    $output = $this->_zonas_supervisores($codigo);
                }
            }
        }
        return $output;
    }
    public function _buscar_cliente($cliente)
    {
        $output = null;
        $type = '0';

        $user_session = $cliente->vendedor;
        $id_codigo = $cliente->codigo;
        $_in = $cliente->_in;

        $zonas = $this->_buscar_zonas($user_session);

        // print _sql_buscar_clientes($_in);
        // return false;

        $src_clientes = $this->db->prepare(_sql_buscar_clientes($_in));

        if(strlen($_in) == 0)
        {
            $src_clientes->bindparam(":zonas", $zonas);
        }else
        {
            $src_clientes->bindparam(":in", $_in);
        }      

        if($src_clientes->execute())
        {
            if($src_clientes->rowCount() == 1)
            {
                $r_clientes = $src_clientes->fetch(PDO::FETCH_ASSOC);

                $codigo = $r_clientes['codigo'];
                $ruc = $r_clientes['ruc'];
                $nombre_comercial = $r_clientes['nombre_comercial'];
                $razon_social = $r_clientes['razon_social'];
                $direccion = $r_clientes['direccion'];
                $distrito = $r_clientes['distrito'];

                $output = $codigo.'~~'.$razon_social;
                $type = '1';
            }
            else if($src_clientes->rowCount() > 1)
            {
                #<th class="text-center">Codigo</th>
                $output .= '<table id="table_listado_clientes" data-toggle="table" data-page-size="10" data-pagination="true" class="table-condensed table table-hover table-bordered table-sm" style="display: table;font-size:0.8em;">
                                <thead class="text-white " style="background-color:#588D9C;">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Cod.I.</th>
                                    <th class="text-center">Ruc</th>
                                    <th class="text-center">Nombre C.</th>
                                    <!--<th class="text-center">Razon S.</th>!-->
                                    <th class="text-center">Direccion</th>
                                </thead>
                                <tbody style="color:black;font-family:verdana;font-size:0.85em;">';
                while($r_clientes = $src_clientes->fetch(PDO::FETCH_ASSOC))
                {
                    $codigo = $r_clientes['codigo'];
                    $ruc = $r_clientes['ruc'];
                    $nombre_comercial = $r_clientes['nombre_comercial'];
                    $razon_social = $r_clientes['razon_social'];
                    $direccion = $r_clientes['direccion'];
                    $distrito = $r_clientes['distrito'];
                    #<td>'.$codigo.'</td>
                    #<button class="btn btn-inverse btn-sm waves-effect waves-light" onclick="__send_values__('."'codigo_".$id_codigo."~~cliente_".$id_codigo."'".', '."'".$ruc."~~".$razon_social."'".' , '."'val~~val'".', '."'modal-events-mm'".');">
                    $output .= ' <tr>
                                    <td>
                                        <button class="btn btn-inverse btn-sm waves-effect waves-light" onclick="__send_values__('."'codigo_".$id_codigo."~~cliente_".$id_codigo."'".', '."'".$codigo."~~".$razon_social."'".' , '."'val~~val'".', '."'modal-events-mm'".');">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </td>

                                    <td>'.$codigo.'</td>
                                    <td>'.$ruc.'</td>
                                    <td>'.$nombre_comercial.'</td>
                                    <!--<td>'.$razon_social.'</td>!-->
                                    <td>'.$direccion.' - '.$distrito.'</td>
                                </tr>';
                }
                $output .= '</tbody></table>';
                $type = '0';
            }else
            {
                $output .= $_in.'~~No registrado';
                $type = '1';
            }
        }
        return $type.'||'.$output;
    }
    public function _eliminar_ruteo($user_session, $fecha)
    {
        $sql1 = $this->db->prepare(_sql_eliminar_ruteo_());
        $sql1->bindparam(":vendedor", $user_session);
        $sql1->bindparam(":fecha", $fecha);
        if($sql1->execute()){return TRUE;}
    }
    public function _insertar_ruteo($ruteo)
    {
        $user_session = $ruteo->user_session;
        $fecha = $ruteo->fecha;
        $array_horas = $ruteo->array_horas;
        $array_codigos = $ruteo->array_codigos;
        $array_clientes = $ruteo->array_clientes;
        $array_objetivos = $ruteo->array_objetivos;
        $array_importes = $ruteo->array_importes;
        $array_observaciones = $ruteo->array_observaciones;
        $array_tipos = $ruteo->array_tipos;

        #[DELETE RUTEO]
        $this->_eliminar_ruteo($user_session, $fecha);
        #[DELETE RUTEO]

        if(is_array($array_horas))
        {
            $horas = explode("||", implode($array_horas, "||"));
            $codigos = explode("||", implode($array_codigos, "||"));
            $clientes = explode("||", implode($array_clientes, "||"));
            $objetivos = explode("||", implode($array_objetivos, "||"));
            $importes = explode("||", implode($array_importes, "||"));
            $observaciones = explode("||", implode($array_observaciones, "||"));
            $tipos = explode("||", implode($array_tipos, "||"));
        }else
        {
            $horas = $array_horas;
            $codigos = $array_codigos;
            $clientes = $array_clientes;
            $objetivos = $array_objetivos;
            $importes = $array_importes;
            $observaciones = $array_observaciones;
            $tipos = $array_tipos;
        }
        #go SQL [INSERT]
        $sql1 = $this->db->prepare(_sql_insertar_ruteo_());

        for($i = 0; $i < count($horas); $i++)
        {
            if($importes[$i] == null){$importes_ = 0;}else{$importes_ = $importes[$i];}
            
            $datos_c = explode("|~|", $this->_datos_complementos_($codigos[$i], $user_session));
            $direccion = $datos_c[1];
            $distrito = $datos_c[0];

            $sql1->bindparam(":user_session", $user_session);
            $sql1->bindparam(":fecha", $fecha);
            $sql1->bindparam(":horas", $horas[$i]);
            $sql1->bindparam(":codigos", $codigos[$i]);
            $sql1->bindparam(":clientes", $clientes[$i]);

            $sql1->bindparam(":cod_int", $codigos[$i]);
            $sql1->bindparam(":direccion", $direccion);
            $sql1->bindparam(":distrito", $distrito);

            $sql1->bindparam(":objetivos", $objetivos[$i]);
            $sql1->bindparam(":importes", $importes_);
            $sql1->bindparam(":observaciones", $observaciones[$i]);
            $sql1->bindparam(":tipos", $tipos[$i]);
            if($sql1->execute())
            {
                $output = 1;
            }else
            {
                $output = errorPDO($sql1);
            }
        }
        return $output;
    }
    public function _buscar_ruteo($user_session, $fecha)
    {
        $output = null;
        $i = 1;

        $sql1 = $this->db->prepare(_sql_buscar_ruteo_());
        $sql1->bindparam(":vendedor", $user_session);
        $sql1->bindparam(":fecha", $fecha);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {

                $output .= '<div class="text-left">
                        <button class="btn btn-primary waves-effect waves-light btn-sm" id="btn_save" onclick="_insertar_ruteo();">
                        <span class="fa fa-save"></span>&nbsp; Guardar</button>
                        </div><hr>
                        <div class="text-left">
                        <button class="btn btn-default waves-effect waves-light btn-sm" id="btn_add" onclick="return newElement2(event, 2, '."'div'".' , '."'insertar'".');">
                        <span  class="fa fa-plus"></span></button>&nbsp;&nbsp;<label for="btn_add" style="color:black;">Agregar</label>
                        </div>
                        <div class="row" id="parent-div_insertar">';

                while($r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC))
                {
                    $id = $i++;

                    $codigo =  $r_sql1['codigo'];
                    $cliente = $r_sql1['cliente'];
                    $objetivo = $r_sql1['objetivo'];
                    $hora =  $r_sql1['hora'];
                    $observaciones = $r_sql1['observaciones'];
                    $importe = $r_sql1['importe'];
                    $tipo = $r_sql1['tipo'];

                    $seleted1 = null;
                    $seleted2 = null;

                    if($tipo == "s"){$seleted1 = 'selected';}else{$seleted2 = 'selected';}

                    #$output .= $codigo."|-|".$cliente."|-|".$objetivo."|-|".$hora."|-|".$observaciones."|-|ID=>".$id."\n";
                    #__send_values__('."'codigo_".$id_codigo."~~cliente_".$id_codigo."'".', '."'".$ruc."~~".$razon_social."'".' , '."'val~~val'".', '."'modal-events-mm'".');

                    $output .= '<div class="col-lg-6" id="div'.$id.'">
                        <table class="table table-condensed table-sm">
                        <thead>
                        <tr>
                        <th colspan="2" style="width:auto;"></th>
                        <th style="width:80%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td class="bg-primary input-sm text-white font-weight-bold border-primary" style="border-radius:0px;">
                        <button class="btn btn-danger waves-effect waves-light btn-sm pull-left" onclick="elementRemove('."'div".$id."'".');">
                        <span class="fa fa-minus"></span></button>
                        </td>
                        <td class="text-right bg-primary input-sm text-white font-weight-bold border-primary align-middle" style="border-radius:0px;">HORA&nbsp;&nbsp;
                        </td>
                        <td>
                        <div class="input-group">
                        <input id="hora_'.$id.'" name="horas_insertar[]" value="'.$hora.'" type="text" class="form-control input-sm text-center border border-secondary">
                        <span class="input-group-addon input-sm bg-primary text-white font-weight-bold border border-primary waves-light waves-effect">
                        <i class="md md-access-time"></i>
                        </span>
                        </div>
                        </td>
                        </tr>
                        <tr>
                        <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm">CLIENTE&nbsp;&nbsp;
                        <span class="input-group-addon input-sm bg-success text-white font-weight-bold border border-white waves-light waves-effect"  onclick="return _buscar_modal('.$id.');">
                        <i class="fa fa-search"></i>
                        </span>
                        </td>
                        <td>
                        <div class="input-group">
                        <input id="codigo_'.$id.'" value="'.$codigo.'" name="codigos_insertar[]" onblur="_blur_buscar_clientes_medicos('.$id.');" style="width:20% !important;" pattern="[0-9.]+" type="text" size="5" maxlength="11"  class="form-control input-sm text-center border border-secondary font_inside_input" onkeypress="return max_length(this.value, 10);validateNumber(event);" placeholder="Codigo">
                        <input id="cliente_'.$id.'" value="'.$cliente.'" name="clientes_insertar[]" style="width:30% !important;" type="text" readonly="true"  class="form-control input-sm text-center border border-secondary font_inside_input" placeholder="Cliente">
                        <span class="input-group-addon input-sm bg-danger text-white font-weight-bold border border-danger waves-light waves-effect" onclick="__clear__('."'codigo_".$id.",cliente_".$id."'".');">
                        <i class="fa fa-trash"></i>
                        </span>
                        </div>
                        </td>
                        </tr>
                        <tr>
                        <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBJETIVO&nbsp;&nbsp;
                        </td>
                        <td><select name="objetivos_insertar[]" value="'.$objetivo.'" id="objetivo_'.$id.'" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">'._objetivos_($objetivo).'</select></td>
                        </tr>
                        <tr>
                        <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">IMPORTE&nbsp;&nbsp;</td>
                        <td>
                        <input type="number" id="importe_'.$id.'" value="'.$importe.'" name="importes_insertar[]" class="form-control input-sm border border-secondary text-center font_inside_input" onkeypress="return max_length(this.value, 7);" id="">
                        </td>
                        </tr>
                        <tr>
                        <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">OBSERVACI&Oacute;N&nbsp;&nbsp;</td>
                        <td><textarea id="observacion_'.$id.'" name="observaciones_insertar[]" class="input-sm border border-secondary font_inside_input" style="width: 100%;height:35px;font-size:0.7em !important;resize:none;" cols="30" rows="10">'.$observaciones.'</textarea></td>
                        </tr>
                        <tr>
                        <td colspan="2" class="text-right bg-primary text-white font-weight-bold border-primary input-sm align-middle">TIPO&nbsp;&nbsp;</td>
                        <td>
                        <select id="tipo_'.$id.'" name="tipos_insertar[]" class="form-control form-control-sm border border-secondary font_inside_input" style="text-align-last:center;padding: 1px 5px;">
                        <option value="s" '.$seleted1.'>SI</option>
                        <option value="n" '.$seleted2.'>NO</option>
                        </select>
                        </td>
                        </tr>
                        <tr>
                        <td colspan="3" class="jumbotron"></td>
                        </tr>
                        </tbody>
                        </table>
                        </div>';

                }
                $output .= '</div>';
            }
        }else
        {
            $output = errorPDO($sql1);
        }
        return $output;
    }
    public function _datos_complementos_($codigo, $user_session)
    {
        $direccion = "No especificado";
        $distrito = "No especificado";

        $sql1 = $this->db->prepare(_sql_datos_complementos($codigo, $user_session));
        $sql1->bindparam(":codigo", $codigo);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC);
                $direccion = $r_sql1['direccion'];
                $distrito = $r_sql1['distrito'];
            }
        }
        return $distrito.'|~|'.$direccion;

    }
    public function _listar_ruteo($user_session, $fecha)
    {
        $output = null;
        $i = 1;

        $sql1 = $this->db->prepare(_sql_buscar_ruteo_());
        $sql1->bindparam(":vendedor", $user_session);
        $sql1->bindparam(":fecha", $fecha);

        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
               $output .= '<table id="table_listado_ruteos" data-toggle="table" data-page-size="10" data-pagination="true" class="table-condensed table table-hover table-bordered table-sm" style="display: table;">
                                <thead class="text-white " style="background-color:#588D9C;font-size:0.7em;">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Hora</th>
                                    <th class="text-center">Codigo</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center">Direcci&oacute;n</th>
                                    <th class="text-center">Distrito</th>
                                    <th class="text-center">Objetivo</th>
                                </thead>
                                <tbody style="color:black;font-family:verdana;font-size:0.85em;">';
                while($r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC))
                {
                    $id = $i++;
                    $codigo =  $r_sql1['codigo'];
                    $cliente = $r_sql1['cliente'];
                    $objetivo = $r_sql1['objetivo'];
                    $hora_p = explode(":", $r_sql1['hora']);
                    $hora = $hora_p[0].':'.$hora_p[1];
                    $observaciones = $r_sql1['observaciones'];
                    $importe = $r_sql1['importe'];
                    $tipo = $r_sql1['tipo'];
                    $direccion = $r_sql1['direccion'];
                    $distrito = $r_sql1['distrito'];

                    switch ($objetivo)
                    {
                        case 'vs':
                            $objetivo = 'Visita';
                            break;
                        case 'vn':
                            $objetivo = 'Venta';
                            break;
                        case 'co':
                            $objetivo = 'Cobranza';
                            break;
                        default:
                            # code...
                            break;
                    }

                    $output .= ' <tr>
                                    <td>'.$id.'</td>
                                    <td>'.$hora.'</td>
                                    <td>'.$codigo.'</td>
                                    <td>'.$cliente.'</td>
                                    <td>'.$direccion.'</td>
                                    <td>'.$distrito.'</td>
                                    <td>'.$objetivo.'</td>
                                </tr>';
                }
                $output .= '</tbody></table>';
            }else
            {
                $output = 'Sin registro';
            }
        }
        return $output;
    }
    ####PAGOS####
    public function _listar_ruteo_pagos($listado)
    {
        $output = NULL;
        $_max_day = 0;
        $_min_day = 0;
        $_i_ = 1;

        $year = $listado->year;
        $month = $listado->month;
        $quincena = $listado->quincena;

        $days_max = __days_in_month($month, $year);

        if($quincena == 1){$_min_day = 1;$_max_day = 15;}else{$_min_day = 16;$_max_day = $days_max;}

        $sql1 = $this->db->prepare(_sql_representates_ruteo());
        $sql1->bindparam(":year", $year);
        $sql1->bindparam(":month", $month);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $output .= '<input type="hidden" id="array-data" value="'.$year.'-'.$month.'-'.$quincena.'-'.$_min_day.'-'.$_max_day.'" id="array-data">
                            <table id="table_listado_ruteos_pagos" data-toggle="table" data-page-size="10" data-pagination="true" class="table table-condensed table-hover table-bordered table-sm text-center" style="display: table;font-size:0.85em;">
                            <thead class="text-white" style="background-color:#588D9C;">
                                <th class="text-center"><input type="checkbox" id="master_check" checked="true"></th>
                                <th class="text-center" >Representante</th>';

                for ($i = $_min_day; $i <= $_max_day; $i++)
                {
                    $output .= '<th class="text-center" style="font-size:0.8em;">'.$i.'</th>';
                }

                $output .= '<th class="text-center">Correlativo</th>
                            <th class="text-center">Acciones</th>
                            </thead>
                            <tbody style="color:black;font-family:verdana;font-size:0.9em;">';

                while($r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC))
                {
                    $vendedor_codigo = $r_sql1['vendedor'];
                    $vendedor = $r_sql1['nombre_corto'];

                    $output .= '<tr>
                                <td>
                                <input type="hidden" value="'.$year.'-'.$month.'-'.zerofill($_i_, 3).'" name="correlativos[]">
                                <input type="checkbox" name="codigos[]" value="'.$vendedor_codigo.'" checked="true"></td>
                                <td>'.$vendedor.'</td>';

                    $sql2 = $this->db->prepare(_sql_contar_dias_ruteo());

                    for ($c = $_min_day; $c <= $_max_day; $c++)
                    {
                        $sql2->bindparam(":year", $year);
                        $sql2->bindparam(":month", $month);
                        $sql2->bindparam(":day", $c);
                        $sql2->bindparam(":vendedor", $vendedor_codigo);

                        if($sql2->execute())
                        {
                            $r_sql2 = $sql2->fetch(PDO::FETCH_ASSOC);

                            $cantidad = $r_sql2['cant'];

                            $output .= '<td>'.$cantidad.'</td>';
                        }
                    }
                    $output .= '<td>'.$year.'-'.$month.'-'.zerofill($_i_++,3).'</td>
                                <td>#</td>
                                </tr>';
                }

                $output .= '</tbody></table>';
            }
        }

        return $output;

    }
    public function _count_ruteo_fecha($user_session, $fecha)
    {
        $cantidad = 0;

        $sql1 = $this->db->prepare(_sql_count_ruteo_fecha());
        $sql1->bindparam(":fecha", $fecha);
        $sql1->bindparam(":vendedor", $user_session);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC);
                $cantidad = $r_sql1['cantidad'];
            }
        }
        return $cantidad;
    }
    public function _print_ruteos($data)
    {  
        $output = NULL;
        $_mnt_x_day = 16.07;

        $new_pdf = new mPDF('c', 'A4');

        $css = file_get_contents('public/assets/css/bootstrap.min.css');
        $new_pdf->WriteHTML($css, 1);

        if(strpos($data->codigos, "||") !== FALSE)
        {
            $codigos = explode("||",$data->codigos);
            $correlativos = explode("||",$data->correlativos);
        }else
        {
            $codigos[] = $data->codigos;
            $correlativos[] = $data->correlativos;
        }

        $year = $data->year;
        $month = $data->month;
        $quincena = $data->quincena;
        $_min_day_ = $data->_min_day_;
        $_max_day_ = $data->_max_day_;
        
        for ($c = 0; $c < count($codigos) ; $c++)
        {
            $_x_month = 0;
            $_totales = 0;
            $new_pdf->AddPage();
            $representantes = $codigos[$c];

            $__init_header__ = '<table class="table" style="font-size:0.75em;">
                                    <tbody style="border:1.5px solid black;">
                                    <tr style="border:1.5px solid black;">
                                        <td style="border:1.5px solid black;width:30% !important;">
                                            '.$correlativos[$c].'<br />
                                            RAZON SOCIAL: <br />
                                            RUC: <br />
                                            PERIODO: <br />
                                        </td>
                                        <td style="border:1.5px solid black;width:70% !important;">
                                            PLANILLAS DE GASTO DE MOVILIDAD LOCAL - POR TRABAJADOR <br />
                                            MKS UNIDOS S.A. <br />
                                            AV. SEPARADORA INDUSTRIAL N° 487  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            FECHA DE EMISION &nbsp;&nbsp;'. date('d M Y') .' <br />
                                            DEL '.$_min_day_.'/'.$month.'/'.$year.'&nbsp;&nbsp;&nbsp;&nbsp;AL&nbsp;&nbsp;&nbsp;&nbsp;'.$_max_day_.'/'.$month.'/'.$year.'
                                        </td>
                                    </tr>
                                    <tr style="border:1.5px solid black;">
                                        <td style="border:1.5px solid black;"></td>
                                        <td style="border:1.5px solid black;">
                                        DATOS DEL TRABAJADOR <br />
                                        NOMBRES Y APELLIDOS:    '.strtoupper(search_repre_name($representantes)).'
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D.N.I:
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>';

            $new_pdf->WriteHTML($__init_header__);
            $new_pdf->Ln(5);

            $sql1 = $this->db->prepare(_sql_listado_ruteo_pagos());
            $sql1->bindparam(":codigos", $representantes);
            $sql1->bindparam(":year", $year);
            $sql1->bindparam(":month", $month);
            $sql1->bindparam(":_min_day_", $_min_day_);
            $sql1->bindparam(":_max_day_", $_max_day_);
            if($sql1->execute())
            {
                if($sql1->rowCount() > 0)
                {
                    $output = '<table class="table table-bordered table-condensed" style="font-size: 0.75em;">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:10% !important;">Fecha</th>
                                        <th class="text-center" style="width:10% !important;">Gastos Destinos</th>
                                        <th class="text-center" style="width:10% !important;">Motivo</th>
                                        <th class="text-center" style="width:10% !important;">Destinos</th>
                                        <th class="text-center" style="width:10% !important;">Viaje</th>
                                        <th class="text-center" style="width:10% !important;">Codigo</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size:0.6em !important;">';

                    $fecha_mirrow = NULL;
                    $_init_ = 1;

                    while($r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC))
                    {
                        $fecha = $r_sql1['fecha'];

                        $vendedor = $r_sql1['vendedor'];
                        $cliente = $r_sql1['cliente'];
                        $cliente_desc = $r_sql1['cliente_desc'];
                        $objetivo = "atencion al cliente";#$r_sql1['objetivo'];
                        $destino = $r_sql1['destino'];
                        $distrito = $r_sql1['distrito'];
                        $viaje = $r_sql1['viaje'];
                        $cliente_int = $r_sql1['cliente_int'];

                        if(strlen($cliente_int) == 0)
                        {
                            $cliente_int = $cliente;
                        }

                        $rt_x_fecha = $this->_count_ruteo_fecha($representantes, $fecha);

                        if($fecha_mirrow == NULL)
                        {
                            $sum_mnt_x_day = 0;
                        }

                        if($fecha != $fecha_mirrow && $fecha_mirrow != NULL)
                        {
                           
                            $output .= '<tr>
                                            <td class="text-center">'.fecha_db_to_view($fecha_mirrow).'</td>
                                            <td></td>
                                            <td>Total</td>
                                            <td></td>
                                            <td class="text-center">'.round($sum_mnt_x_day, 2).'</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>';
                            $_totales += $sum_mnt_x_day;
                            $sum_mnt_x_day = 0;  
                        }

                        $rt_x_vs_ = ($_mnt_x_day/$rt_x_fecha);

                        $output .= '<tr>
                                        <td class="text-center">'.fecha_db_to_view($fecha).'</td>
                                        <td>'.strtoupper($cliente_desc).'</td>
                                        <td>'.strtoupper($objetivo).'</td>
                                        <td>'.strtoupper($destino.' - '.$distrito).'</td>
                                        <td class="text-center">'.round($rt_x_vs_, 2).'</td>
                                        <td class="text-center">'.$cliente_int.'</td>
                                    </tr>';

                        $sum_mnt_x_day += $rt_x_vs_;

                        $fecha_mirrow = $fecha;
                        
                        if($sql1->rowCount() == $_init_)
                        {
                            $output .= '
                                    <tr>
                                        <td class="text-center">'.fecha_db_to_view($fecha_mirrow).'</td>
                                        <td></td>
                                        <td>Total</td>
                                        <td></td>
                                        <td class="text-center">'.round($sum_mnt_x_day, 2).'</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>';
                            $_totales += $sum_mnt_x_day;
                        }
                        $_init_++;
                        
                    }
                    
                    $output .= '</tbody></table>';
                    $output .= '<br />
                                <div class="text-right">
                                    <div>TOTALES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$_totales.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                </div>';
                    $output .= '<br /><br />';                    
                    $output .= '<div class="text-right">
                                    <div class="text-center">________________________</div>
                                    <div class="text-center" style="font-size:0.8em !important;">'.strtoupper(search_repre_name($representantes)).'</div>
                                </div>';
                }
            }
            $new_pdf->WriteHTML($output);
        }
        $new_pdf->SetJS('this.print();');
        $correlative_ = "Reporte_".date('ymdhis').".pdf";
        $new_pdf->Output($correlative_, "I");

    }
    ####PAGOS####
    public function _print_test($data)
    {
        $pdf = new mPDF();
       // Logo
        $cabecera = '<table class="">
                    <tbody>
                    <tr>
                        <td>
                            2018-10-055<br />
                            RAZON SOCIAL: <br />
                            RUC: <br />
                            PERIODO: <br />
                        </td>
                        <td>
                            PLANILLAS DE GASTO DE MOVILIDAD LOCAL - POR TRABAJADOR <br />
                            MKS UNIDOS S.A. <br />
                            AV. SEPARADORA INDUSTRIAL N° 487  -  FECHA DE EMISION '. date('d M Y') .' <br />
                            DEL {{_MIN_DAY_}} AL {{_MAX_DAY_}}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        DATOS DEL TRABAJADOR <br />
                        NOMBRES Y APELLIDOS:    {{NOMBRE_REPRESENTANTE}}
                        D.N.I:
                        </td>
                    </tr>
                    </tbody>
                </table>';

        $cabecera2 = '<table class="">
                    <tbody>
                    <tr>
                        <td>
                            2018-10-055<br />
                            RAZON SOCIAL: <br />
                            RUC: <br />
                            PERIODO: <br />
                        </td>
                        <td>
                            PLANILLAS DE GASTO DE MOVILIDAD LOCAL - POR TRABAJADOR222 <br />
                            MKS UNIDOS S.A. <br />
                            AV. SEPARADORA INDUSTRIAL N° 487  -  FECHA DE EMISION '. date('d M Y') .' <br />
                            DEL {{_MIN_DAY_}} AL {{_MAX_DAY_}}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        DATOS DEL TRABAJADOR <br />
                        NOMBRES Y APELLIDOS:    {{NOMBRE_REPRESENTANTE}}
                        D.N.I:
                        </td>
                    </tr>
                    </tbody>
                </table>';
        //$pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial','B',10);
        $pdf->WriteHTML($cabecera);
        $pdf->Ln(20);

        $pdf->SetFont('Arial','B',12);
        $pdf->WriteHTML($cabecera);


        $pdf->AddPage();
        $pdf->WriteHTML($cabecera2);

        $pdf->Output();
    }
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}