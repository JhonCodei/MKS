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

    
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}