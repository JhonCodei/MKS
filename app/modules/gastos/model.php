<?php

Class GastosModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function search_gastos($vendedor, $periodo, $quincena)
    {
        $id = 0;

        $sql1 = $this->db->prepare(_sql_search_gastos());
        $sql1->bindparam(":vendedor", $vendedor);
        $sql1->bindparam(":periodo", $periodo);
        $sql1->bindparam(":quincena", $quincena);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0)
            {
                $r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC);
                
                $id = $r_sql1['id'];
            }
        }
        return $id;
    }
    public function _insertar_gasto($gastos)
    {
        $output = 0;

        $vendedor = $gastos->vendedor;
        $periodo = $gastos->periodo;
        $quincena = $gastos->quincena;
        $fecha = $gastos->fecha;
        $motivo = $gastos->motivo;
        $tipo = $gastos->tipo;
        $documentos = $gastos->documentos;
        $ruc = $gastos->ruc;
        $importe = $gastos->importe;
        $ventas = $gastos->ventas;
        $ruc_cliente = $gastos->ruc_cliente;
        $observaciones = $gastos->observaciones;

        $_gastos_id = $this->search_gastos($vendedor, $periodo, $quincena);
        
        if( $_gastos_id == 0 )
        {
            $_gastos = $this->db->prepare(_sql_insert_gastos());
            $_gastos->bindparam(":vendedor", $vendedor);
            $_gastos->bindparam(":periodo", $periodo);
            $_gastos->bindparam(":quincena", $quincena);
            if($_gastos->execute())
            {
                $_gastos_id = $this->db->lastInsertId();
            }
        }

        
        $_gastos_detalle = $this->db->prepare(_sql_insert_gastos_detalle());
        $_gastos_detalle->bindparam(":fecha", $fecha);
        $_gastos_detalle->bindparam(":motivo", $motivo);
        $_gastos_detalle->bindparam(":tipo", $tipo);
        $_gastos_detalle->bindparam(":documento", $documentos);
        $_gastos_detalle->bindparam(":ruc", $ruc);
        $_gastos_detalle->bindparam(":importe", $importe);
        $_gastos_detalle->bindparam(":ventas", $ventas);
        $_gastos_detalle->bindparam(":ruc_cliente", $ruc_cliente);
        $_gastos_detalle->bindparam(":observacion", $observaciones);
        $_gastos_detalle->bindparam(":id_gastos", $_gastos_id);
        if($_gastos_detalle->execute())
        {
            $output = 1;
        }else
        {
            $output = errorPDO($_gastos_detalle);
        }

        return $output;

    }
    public function listado_gastos($data)
    {
        $output = null;

        $vendedor = $data->vendedor;
        $periodo = $data->periodo;
        $quincena = $data->quincena;

        $sql1 = $this->db->prepare(_sql_listado_gastos());
        $sql1->bindparam(":vendedor", $vendedor);
        $sql1->bindparam(":periodo", $periodo);
        $sql1->bindparam(":quincena", $quincena);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0) 
            {
                $output = '<table id="listado-gastos" class="table table-bordered table-sm">
                                <thead style="background-color:#476269;font-size:0.75em;" class="text-center text-white">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Fecha</th>
                                    <th class="text-center">Motivo</th>
                                    <th class="text-center">Tipo Doc.</th>
                                    <th class="text-center">Doc</th>
                                    <th class="text-center">Ruc</th>
                                    <th class="text-center">Importe</th>
                                    <th class="text-center">Ventas</th>
                                    <th class="text-center">Ruc Cliente</th>
                                    <th class="text-center">Observaciones</th>
                                </thead>
                            <tbody  style="font-size:0.85em;color:black;">';
                while ($r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC))
                {
                    $id = $r_sql1['id'];
                    $fecha = fecha_db_to_view($r_sql1['fecha']);
                    $motivo = $r_sql1['motivo'];
                    $tipo = $r_sql1['tipo'];
                    $documento = $r_sql1['documento'];
                    $ruc = $r_sql1['ruc'];
                    $importe = $r_sql1['importe'];
                    $ventas = $r_sql1['ventas'];
                    $ruc_cliente = $r_sql1['ruc_cliente'];
                    $observacion = $r_sql1['observacion'];

                    $btn1 = '<button class="btn btn-default waves-effect waves-light btn-sm" onclick="editar_gasto('.$id.');"><span class="fa fa-pencil"></span></button>';
                    $btn2 = '<button class="btn btn-danger waves-effect waves-light btn-sm" onclick="eliminar_gasto('.$id.');"><span class="fa fa-trash"></span></button>';

                    $output .= '<tr>
                                <td class="text-center">'.$btn1.$btn2.'</td>
                                <td class="text-center">'.$fecha.'</td>
                                <td class="text-center">'._view_motivos_($motivo).'</td>
                                <td class="text-center">'._view_tipo_documentos_($tipo).'</td>
                                <td class="text-center">'.$documento.'</td>
                                <td class="text-center">'.$ruc.'</td>
                                <td class="text-center">'.$importe.'</td>
                                <td class="text-center">'.$ventas.'</td>
                                <td class="text-center">'.$ruc_cliente.'</td>
                                <td class="text-center">'.$observacion.'</td>
                                </tr>';
                }
                $output .= '</tbody></table>';
            }
        }
        return $output;
    }
    public function editar_gasto($data)
    {
        $output = null;

        $id = $data->id;

        $sql1 = $this->db->prepare(_sql_listado_gastos_id());
        $sql1->bindparam(":id", $id);
        if($sql1->execute())
        {
            if($sql1->rowCount() > 0) 
            {
                $r_sql1 = $sql1->fetch(PDO::FETCH_ASSOC);

                $fecha = fecha_db_to_view($r_sql1['fecha']);
                $motivo = $r_sql1['motivo'];
                $tipo = $r_sql1['tipo'];
                $documento = $r_sql1['documento'];
                $ruc = $r_sql1['ruc'];
                $importe = $r_sql1['importe'];
                $ventas = $r_sql1['ventas'];
                $ruc_cliente = $r_sql1['ruc_cliente'];
                $observacion = $r_sql1['observacion'];

                $output = $fecha.'|~|'.$motivo.'|~|'.$tipo.'|~|'.$documento.'|~|'.$ruc.'|~|'.$importe.'|~|'.$ventas.'|~|'.$ruc_cliente.'|~|'.$observacion;
            }
        }
        return $output;
    }
    public function update_gasto($gastos)
    {
        $output = 0;

        $vendedor = $gastos->vendedor;
        $id = $gastos->id;
        $periodo = $gastos->periodo;
        $quincena = $gastos->quincena;
        $fecha = $gastos->fecha;
        $motivo = $gastos->motivo;
        $tipo = $gastos->tipo;
        $documentos = $gastos->documentos;
        $ruc = $gastos->ruc;
        $importe = $gastos->importe;
        $ventas = $gastos->ventas;
        $ruc_cliente = $gastos->ruc_cliente;
        $observaciones = $gastos->observaciones;

                
        $_gastos_detalle = $this->db->prepare(_sql_update_gastos_detalle());
        $_gastos_detalle->bindparam(":fecha", $fecha);
        $_gastos_detalle->bindparam(":motivo", $motivo);
        $_gastos_detalle->bindparam(":tipo", $tipo);
        $_gastos_detalle->bindparam(":documento", $documentos);
        $_gastos_detalle->bindparam(":ruc", $ruc);
        $_gastos_detalle->bindparam(":importe", $importe);
        $_gastos_detalle->bindparam(":ventas", $ventas);
        $_gastos_detalle->bindparam(":ruc_cliente", $ruc_cliente);
        $_gastos_detalle->bindparam(":observacion", $observaciones);
        $_gastos_detalle->bindparam(":id", $id);
        if($_gastos_detalle->execute())
        {
            $output = 1;
        }else
        {
            $output = errorPDO($_gastos_detalle);
        }

        return $output;

    }
    public function eliminar_gasto($data)
    {
        $id = $data->id;

        $sql1 = $this->db->prepare(_sql_eliminar_gasto());
        $sql1->bindparam(":id", $id);
        if($sql1->execute())
        {
            $output = 1;
        }else
        {
            $output = errorPDO($sql1);
        }
        return $output;
    } 
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}