<?php

Class LoginModel
{
    public function __construct()
    {
        $this->db = Database::Connection();
    }
    public function logeo($usuario, $password)
    {
        try
        {
            $Query0 = $this->db->prepare(logueo());
            $Query0->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            if($Query0->execute())
            {
                if($Query0->rowCount() > 0)
                {
                    $rQuery0 = $Query0->fetch(PDO::FETCH_ASSOC);

                    if(trim(descrypt($rQuery0['usuario_password'])) == trim($password))
                    {
                        $user_ = $rQuery0['usuario_usuario'];

                        $menu_load = _get_menu_list($user_);
                        
                        if($menu_load == '0')
                        {
                            $output = "0~~A ocurrido un error.";
                        }else
                        {                            
                            $exp = explode("=", $menu_load);

                            $exp2 = explode("~", $exp[1]);
                                    
                            $_SESSION['user_id'] = $rQuery0['usuario_id'];
                            $_SESSION['user_user'] = $rQuery0['usuario_usuario'];
                            $_SESSION['timeout'] = date('Y-m-d H:i:s');

                            $output = "1~~".$exp2[1];
                        }                                             
                    }else
                    {
                        $output = "0~~La contrase&ntilde;a es incorrecta.";
                    }
                }else
                {
                    $output = "0~~El usuario no existe.";
                }
            }else
            {
                $output = "0~~[0] => " . errorPDO($Query0);
            }
        }catch(PDOException $e)
        {
            $output = "0~~Error => " . $e->getMessage();
        }
        return $output;
    }
    public function __destruct()
    {
        $this->db = null;
    }
}


?>