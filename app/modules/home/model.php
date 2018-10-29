<?php

Class HomeModel
{
    public function __construct()
    {
        $this->db = Database::Connection();#Connection DB#
        #__SQL__();
    }
    #__functions__


    
    public function __destruct()
    {
        $this->db = NULL;#Connection Desctruct#
        #__SQL__();
    }


}