<?php

Class PerfilModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__
    public function cambio_password($user, $password)
    {
        try
        {
            $update = $this->db->prepare("UPDATE tbl_usuarios SET usuario_password = :password WHERE usuario_usuario = :user");
            $update->bindParam(":password", $password , PDO::PARAM_STR);
            $update->bindParam(":user", $user , PDO::PARAM_STR);
            if($update->execute())
            {
                $output = 1;
            }else
            {
                $output = erroPDO($update);
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