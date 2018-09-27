<?php

Class UsuariosModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    
    public function list_menu()
    {
        $output = null;

        $select = $this->db->prepare("SELECT 
                                        menu_cod, menu_descripcion
                                    FROM
                                        tbl_maestro_menus");
        if($select->execute())
        {
            while($r_select = $select->fetch(PDO::FETCH_ASSOC))
            {
                $output .= '<option value="'.$r_select['menu_cod'].'">'.$r_select['menu_descripcion'].'</option>';
            }
        }else
        {
            $output = '<option value="">--</option>';
        }
        return $output;
    }
    public function list_usuarios()
    {
        $output = null;
        try
        {
            $Query = $this->db->prepare("SELECT 
                                            u_detalle_codigo AS codigo,
                                            usuario_usuario AS usuario,
                                            u_detalle_nombre AS nombre,
                                            usuario_root AS tipo_user,
                                            view_region AS region,
                                            view_region_visita AS region_visita,
                                            view_portafolio AS portafolio,
                                            u_detalle_menu AS menu,
                                            usuario_estado AS estado,
                                            view_tipo AS tipo
                                        FROM
                                            tbl_usuarios
                                                INNER JOIN
                                            tbl_view_data ON view_usuario = usuario_usuario
                                                INNER JOIN
                                            tbl_usuario_detalle ON u_detalle_usuario = usuario_usuario
                                        GROUP BY 2;");

            if($Query->execute())
            {
                if($Query->rowCount() > 0)
                {
                    $output = '<table id="table-list-usuarios" class="table table-striped table-bordered table-condensed table-sm" style="font-size:0.85em;width:auto;" >
                                <thead class="text-white thead-color-p">
                                    <th class="text-center th-left-round">Codigo</th>
                                    <th class="text-center">Usuario</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">TipoUsr</th>
                                    <th class="text-center">Region</th>
                                    <th class="text-center">Region_visita</th>
                                    <th class="text-center">Portafolio</th>
                                    <th class="text-center">Modulos</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center th-right-round">Acciones</th>
                                </thead>
                                </table>';
                    while($rQuery = $Query->fetch(PDO::FETCH_ASSOC))
                    {

                        $codigo = $rQuery['codigo'];
                        $usuario = $rQuery['usuario'];
                        $nombre = $rQuery['nombre'];
                        $tipo_user = $rQuery['tipo_user'];
                        $region = $rQuery['region'];
                        $region_visita = $rQuery['region_visita'];
                        $portafolio = $rQuery['portafolio'];
                        $menu = $rQuery['menu'];
                        $estado = $rQuery['estado'];
                        $tipo = $rQuery['tipo'];


                        switch ($tipo) {
                            case '0':
                                $tipo = 'Todo';
                                break;
                            case '1':
                                $tipo = 'Ventas';
                                break;
                            case '2':
                                $tipo = 'Visita';
                                break;
                            case '3':
                                $tipo = 'Venta-Visita';
                                break;
                            case '4':
                                $tipo = 'Reportes';
                                break;
                            default:
                                # code...
                                break;
                        }

                        if($estado == 1)
                        {
                            $estado = 'Activo';
                        }else
                        {
                            $estado = 'Inactivo';
                        }

                        $button = '<button onclick="return search_user('."'".$usuario."'".');" 
                                    class="btn btn-primary waves-effect waves-light btn-sm"><span class="fa fa-pencil"></span>Editar</button>';

                        $result['codigo'] = $codigo;
                        $result['usuario'] = $usuario;
                        $result['nombre'] = $nombre;
                        $result['tipo_user'] = $tipo_user;
                        $result['region'] = $region;
                        $result['region_visita'] = $region_visita;
                        $result['portafolio'] = $portafolio;
                        $result['menu'] = $menu;
                        $result['tipo_view'] = $tipo;
                        $result['estado'] = $estado;
                        $result['btn1'] = $button;

                        $data['data'][] = array_map("utf8_encode", $result);
                    }
                }else
                {
                    $output = '<b>No hay datos</b>';
                    $data['data']['error'] = '';
                }
            }else
            {
                $output = "Error  , => " . errorPDO($Query);
            }
        }catch(PDOException $e)
        {
            $output = "Exception, => " . $e->getMessage();
        }

        return $output.'||'.json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    public function search_user($usuario)
    {
        $Query = $this->db->prepare("SELECT 
                                            u_detalle_codigo AS codigo,
                                            usuario_usuario AS usuario,
                                            u_detalle_nombre AS nombre,
                                            usuario_root AS tipo_user,
                                            view_region AS region,
                                            view_region_visita AS region_visita,
                                            usuario_password AS passwd,
                                            view_portafolio AS portafolio,
                                            u_detalle_menu AS menu,
                                            usuario_estado AS estado,
                                            view_tipo as tipo
                                        FROM
                                            tbl_usuarios
                                                INNER JOIN
                                            tbl_view_data ON view_usuario = usuario_usuario
                                                INNER JOIN
                                            tbl_usuario_detalle ON u_detalle_usuario = usuario_usuario
                                        WHERE usuario_usuario = :usuario");
                                        
            $Query->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            if($Query->execute() && $Query->rowCount() > 0)
            {
                $rQuery = $Query->fetch(PDO::FETCH_ASSOC);

                $unir_reg = $rQuery['region'].'-'.$rQuery['region_visita'];

                $output = $rQuery['codigo']."~~".$rQuery['usuario']."~~".$rQuery['nombre']."~~".$rQuery['tipo_user']."~~".$unir_reg."~~".$rQuery['portafolio']."~~".$rQuery['menu'].'~~'.descrypt($rQuery['passwd']).'~~'.$rQuery['tipo'];
            }else
            {
                $output = errorPDO($Query);
            }
            return $output;
    }
    public function update_usuarios($nombres, $root, $usuario, $password, $codigo, $portafolio, $region, $menu_array, $tipo_view)
    {
        try
        {
            $data_reg = explode('-',$region);
            $region_visita = $data_reg[1];
            $region = $data_reg[0];

            $update = $this->db->prepare("  UPDATE tbl_usuarios 
                                                SET usuario_password = :password, 
                                                    usuario_root = :root 
                                                WHERE usuario_usuario = :usuario;

                                            UPDATE tbl_view_data 
                                                SET view_portafolio = :portafolio, 
                                                    view_region = :region,
                                                    view_region_visita = :region_visita,
                                                    view_tipo = :tipo_view
                                                WHERE view_usuario = :usuario;

                                            UPDATE tbl_usuario_detalle 
                                                SET u_detalle_codigo = :codigo, 
                                                    u_detalle_nombre = :nombres,
                                                    u_detalle_menu = :menu_array
                                                WHERE u_detalle_usuario = :usuario;");
            $update->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $update->bindParam(":root", $root, PDO::PARAM_INT);
            $update->bindParam(":nombres", $nombres, PDO::PARAM_STR);
            $update->bindParam(":password", $password, PDO::PARAM_STR);
            $update->bindParam(":codigo", $codigo, PDO::PARAM_INT);
            $update->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);
            $update->bindParam(":region", $region, PDO::PARAM_STR);
            $update->bindParam(":region_visita", $region_visita, PDO::PARAM_STR);
            $update->bindParam(":menu_array", $menu_array, PDO::PARAM_INT);
            $update->bindParam(":tipo_view", $tipo_view, PDO::PARAM_INT);
            if($update->execute())
            {
                $output = 1;
            }else
            {
                $output = errorPDO($update);
            }
        }catch(PDOException $e)
        {
            $output = "Exception , => " . $e->getMessage();
        }
        return $output;
    }
    public function insert_usuarios($nombres, $root, $usuario, $password, $codigo, $portafolio, $region, $menu_array, $estado, $tipo_view)
    {
        try
        {
            $data_reg = explode('-',$region);
            $region_visita = $data_reg[1];
            $region = $data_reg[0];

            $Query = $this->db->prepare("SELECT 
                                            u_detalle_codigo AS codigo,
                                            usuario_usuario AS usuario,
                                            u_detalle_nombre AS nombre,
                                            usuario_root AS tipo_user,
                                            view_region AS region,
                                            view_portafolio AS portafolio,
                                            u_detalle_menu AS menu,
                                            usuario_estado AS estado,
                                            view_tipo AS tipo
                                        FROM
                                            tbl_usuarios
                                                INNER JOIN
                                            tbl_view_data ON view_usuario = usuario_usuario
                                                INNER JOIN
                                            tbl_usuario_detalle ON u_detalle_usuario = usuario_usuario
                                        WHERE usuario_usuario = :usuario");
            $Query->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            if($Query->execute())
            {   
                if($Query->rowCount() == 0)
                {
                    $insert = $this->db->prepare("INSERT INTO tbl_usuarios(usuario_usuario, usuario_password, usuario_estado, usuario_root)
                                                    VALUES(:usuario, :password, :estado, :root);
                                                INSERT INTO tbl_usuario_detalle(u_detalle_codigo, u_detalle_usuario, u_detalle_nombre, u_detalle_menu)
                                                    VALUES(:codigo, :usuario, :nombres, :menu_array);
                                                INSERT INTO tbl_view_data(view_usuario, view_portafolio, view_region, view_region_visita, view_tipo)
                                                    VALUES(:usuario, :portafolio, :region, :region_visita, :tipo_view);");
                    $insert->bindParam(":usuario", $usuario, PDO::PARAM_STR);
                    $insert->bindParam(":root", $root, PDO::PARAM_INT);
                    $insert->bindParam(":nombres", $nombres, PDO::PARAM_STR);
                    $insert->bindParam(":password", $password, PDO::PARAM_STR);
                    $insert->bindParam(":codigo", $codigo, PDO::PARAM_INT);
                    $insert->bindParam(":portafolio", $portafolio, PDO::PARAM_STR);
                    $insert->bindParam(":region", $region, PDO::PARAM_STR);
                    $insert->bindParam(":region_visita", $region_visita, PDO::PARAM_STR);
                    $insert->bindParam(":menu_array", $menu_array, PDO::PARAM_INT);
                    $insert->bindParam(":estado", $estado, PDO::PARAM_INT);
                    $insert->bindParam(":tipo_view", $tipo_view, PDO::PARAM_INT);
                    if($insert->execute())
                    {
                        $output = 1;
                    }else
                    {
                        $output = "Insert, => " . errorPDO($insert);
                    }
                }else
                {
                    $output = "Existe . . .";
                }
            }else
            {
                $output = "Query, => " . errorPDO($Query);
            }

        }catch(PDOException $e)
        {
            $output = "Exception , => " . $e->getMessage();
        }
        return $output;
    }

    
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}