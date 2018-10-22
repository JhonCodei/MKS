<?php

Class RuteoModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function _ruteo_cerrado_($datetime)
    {
        $output = FALSE;

        // $estado = $this->db->prepare();
        // if($estado)
        // {

        // }
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

        $output = null;
        $WHERE = null;
        $WHERE1 = null;

        $medicos = $this->db->prepare(_sql_buscar_medicos($representante));
        $medicos->bindparam(':user_session', $representante);
        if($medicos->execute())
        {
            if($medicos->rowCount() > 0)
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
                $output = "Sin medicos";#$cmp.'*'.$correlativo.'~~No registrado';
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
        $zonas = $this->_buscar_zonas($user_session);
        $src_clientes = $this->db->prepare(_sql_buscar_clientes());
        $src_clientes->bindparam(":zonas", $zonas);

        if($src_clientes->execute())
        {
            if($src_clientes->rowCount() > 0)
            {
                #<th class="text-center">Codigo</th>
                $output .= '<table id="table_listado_clientes" data-toggle="table" data-page-size="10" data-pagination="true" class="table-condensed table table-hover table-bordered table-sm" style="display: table;font-size:0.8em;">
                                <thead class="text-white " style="background-color:#588D9C;">
                                    <th class="text-center">#</th>

                                    <th class="text-center">Ruc</th>
                                    <th class="text-center">Nombre C.</th>
                                    <th class="text-center">Razon S.</th>
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
                    $output .= ' <tr>
                                    <td>
                                        <button class="btn btn-inverse btn-sm waves-effect waves-light" onclick="__send_values__('."'codigo_".$id_codigo."~~cliente_".$id_codigo."'".', '."'".$ruc."~~".$razon_social."'".' , '."'val~~val'".', '."'modal-events-mm'".');">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </td>

                                    <td>'.$ruc.'</td>
                                    <td>'.$nombre_comercial.'</td>
                                    <td>'.$razon_social.'</td>
                                    <td>'.$direccion.' - '.$distrito.'</td>
                                </tr>';
                }
                $output .= '</tbody></table>';
                $type = '0';
            }else
            {
                $output .= '0~~No registrado';
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

        $this->_eliminar_ruteo($user_session, $fecha);

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

            $sql1->bindparam(":user_session", $user_session);
            $sql1->bindparam(":fecha", $fecha);
            $sql1->bindparam(":horas", $horas[$i]);
            $sql1->bindparam(":codigos", $codigos[$i]);
            $sql1->bindparam(":clientes", $clientes[$i]);
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
                        <input id="codigo_'.$id.'" value="'.$codigo.'" name="codigos_insertar[]" style="width:20% !important;" pattern="[0-9.]+" type="number" size="5" maxlength="11"  class="form-control input-sm text-center border border-secondary font_inside_input" onkeypress="return max_length(this.value, 10);validateNumber(event);" placeholder="Codigo">
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
        // if($codigo > 10000000001)
        // {
        //     $sql1->bindparam(":user_session", $user_session);
        // }
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

                    $datos_c = explode("|~|", $this->_datos_complementos_($codigo, $user_session));
                    $direccion = $datos_c[1];
                    $distrito = $datos_c[0];

                    switch ($objetivo) {
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
    public function _listar_ruteo_pagos($listado)
    {
        $output = NULL;
        $_max_day = 0;
        $_min_day = 0;
        $_i_ = 1;

        $year = $listado->year;
        $month = $listado->month;
        $quincena = $listado->quincena;

        $days_max = cal_days_in_month(CAL_GREGORIAN, $month, $year);

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
                                <td><input type="checkbox" name="codigos[]" value="'.$vendedor_codigo.'" checked="true"></td>
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
    public function _print_ruteos($data)
    {
        $output = NULL;
        $new_pdf = new mPDF('c', 'A4');
        $cabecera = '<table class="table" style="width:100%;background-color:black;">
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
                                        AV. SEPARADORA INDUSTRIAL N 487  -  FECHA DE EMISION '. date('d MM YYYY') .' <br />
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
        $new_pdf->WriteHTML('<h1>Hello world!</h1>');
        $new_pdf->Output('reporte12.pdf', 'I');

        #$codigos = str_replace("||", ",", $data->codigos);
/*
        if(strpos($data->codigos, "||") !== FALSE)
        {
            $codigos = explode("||",$data->codigos);
        }else
        {
            $codigos[] = $data->codigos;
        }

        $year = $data->year;
        $month = $data->month;
        $quincena = $data->quincena;
        $_min_day_ = $data->_min_day_;
        $_max_day_ = $data->_max_day_;

        $new_name = 'owker';

        for ($c = 0; $c < count($codigos) ; $c++)
        {
            $representantes = $codigos[$c];

            $_header_a1 = "2018-10-055 \nRAZON SOCIAL: \nRUC: \nPERIODO:";

            $_header_a2 = " DATOS DEL TRABAJADOR \nNOMBRES Y APELLIDOS:    ".strtoupper(search_repre_name($representantes))."D.N.I:";

            $_header_a3 = '';
            $_header_a4 = "PLANILLAS DE GASTO DE MOVILIDAD LOCAL - POR TRABAJADOR \nMKS UNIDOS S.A. \nAV. SEPARADORA INDUSTRIAL N° 487  -  FECHA DE EMISION ". date('d M Y') ." \nDEL ".$_min_day_." AL ".$_max_day_;

            $__init_header__ = '<table class="">
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
                                            DEL '.$_min_day_.' AL '.$_max_day_.'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                        DATOS DEL TRABAJADOR <br />
                                        NOMBRES Y APELLIDOS:    '.strtoupper(search_repre_name($representantes)).'
                                        D.N.I:
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>';


            // $new_pdf->Cell($_header_a1, 7, 0, 1, 0, 'C');
            // $new_pdf->Cell($_header_a2, 7, 1, 1, 0, 'C');
            // $new_pdf->Cell($_header_a3, 7, 2, 1, 0, 'C');
            // $new_pdf->Cell($_header_a4, 7, 3, 1, 0, 'C');
            // $new_pdf->Ln();

            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a1), 7, 0, 1, 0, 'C');
            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a2), 7, 1, 1, 0, 'C');
            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a3), 7, 2, 1, 0, 'C');
            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a4), 7, 3, 1, 0, 'C');


            // $new_pdf->AddPage();
            // $new_pdf->SetFont('Arial','B',8);

            // $new_pdf->Cell($_header_a1, 7, 0, 1, 0, 'C');
            // $new_pdf->Cell($_header_a2, 7, 1, 1, 0, 'C');
            // $new_pdf->Cell($_header_a3, 7, 2, 1, 0, 'C');
            // $new_pdf->Cell($_header_a4, 7, 3, 1, 0, 'C');
            #$new_pdf->WriteHTML($__init_header__);

            // $new_pdf->MultiCell(50, 5, $_header_a1, 1, 1, 0, 'C');
            // $new_pdf->MultiCell(100, 5, $_header_a4, 1, 2, 0, 'C');
            // $new_pdf->MultiCell(50, 5, $_header_a1, 1, 1, 0, 'C');
            // $new_pdf->MultiCell(100, 5, $_header_a4, 1, 2, 0, 'C');
            // $new_pdf->Ln();
            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a1), 7, 0, 1, 0, 'C');
            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a2), 7, 1, 1, 0, 'C');
            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a3), 7, 2, 1, 0, 'C');
            // $new_pdf->Cell($new_pdf->WriteHTML($_header_a4), 7, 3, 1, 0, 'C');
            //$new_pdf->Ln(5);

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
                    $output = '<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>'.$representantes.'</th>
                                        <th>Gastos Destinos</th>
                                        <th>Motivo</th>
                                        <th>Destinos</th>
                                        <th>Viaje</th>
                                        <th>cod</th>
                                    </tr>
                                </thead>
                                <tbody>';
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
                        $viaje = $r_sql1['viaje'];

                        if($fecha != $fecha_mirrow && $fecha_mirrow != NULL)
                        {
                            $output .= '<tr>
                                        <td>'.$fecha_mirrow.'</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>350</td>
                                    </tr>';
                        }

                        $output .= '<tr>
                                        <td>'.$fecha.'</td>
                                        <td>'.$cliente_desc.'</td>
                                        <td>'.strtoupper($objetivo).'</td>
                                        <td>'.$destino.'</td>
                                        <td>'.$viaje.'</td>
                                        <td>'.$cliente.'</td>
                                    </tr>';
                        $fecha_mirrow = $fecha;

                        if($sql1->rowCount() == $_init_)
                        {
                            $output .= '<tr>
                                        <td>'.$fecha_mirrow.'</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>350</td>
                                    </tr>';
                        }
                        $_init_++;
                    }
                    $output .= '</tbody></table>';
                }

            }
            $new_pdf->WriteHTML($output);
        }
        $new_pdf->writeHTML("<div>LOREM</div>");
        $new_pdf->Output();*/

    }
    public function _print_ruteos2($data)
    {
        $output = 'xd';

        $codigos = str_replace("||", ",", $data->codigos);
        $year = $data->year;
        $month = $data->month;
        $quincena = $data->quincena;
        $_min_day_ = $data->_min_day_;
        $_max_day_ = $data->_max_day_;

        $new_name = 'owker';

        $sql1 = $this->db->prepare(_sql_listado_ruteo_pagos());
        $sql1->bindparam(":codigos", $codigos);
        $sql1->bindparam(":year", $year);
        $sql1->bindparam(":month", $month);
        $sql1->bindparam(":_min_day_", $_min_day_);
        $sql1->bindparam(":_max_day_", $_max_day_);

        /*if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $cabecera = '<table class="table" style="width:100%;">
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
                                        AV. SEPARADORA INDUSTRIAL N° 487  -  FECHA DE EMISION '. date('d MM YYYY') .' <br />
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

                $output .= '<table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Gastos Destinos</th>
                                    <th>Motivo</th>
                                    <th>Destinos</th>
                                    <th>Viaje</th>
                                    <th>cod</th>
                                </tr>
                            </thead>
                            <tbody>';
                while($r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC))
                {
                    $vendedor = $r_sql1['vendedor'];
                    $fecha = $r_sql1['fecha'];
                    $cliente = $r_sql1['cliente'];
                    $cliente_desc = $r_sql1['cliente_desc'];
                    $objetivo = "atencion al cliente";#$r_sql1['objetivo'];
                    $destino = $r_sql1['destino'];
                    $viaje = $r_sql1['viaje'];

                    $output = '<tr>
                                    <td>'.$fecha.'</td>
                                    <td>'.$cliente_desc.'</td>
                                    <td>'.$objetivo.'</td>
                                    <td>'.$destino.'</td>
                                    <td>'.$viaje.'</td>
                                    <td>'.$cliente.'</td>
                                </tr>';
                }
                $output .= '</tbody></table>';
            }
        }else
        {
            return errorPDO($sql1);
        }*/

    }
    public function _print_ruteos221($data)
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