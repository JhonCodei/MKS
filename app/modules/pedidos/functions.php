<?php

    function condicion_pago($select)
    {
        $output = null;
        $arrayName = array(0 => 'CE||Contra entrega',
                                            1 => 'F30||Factura a 30 días',
                                            2 => 'F45||Factura a 45 días',
                                            3 => 'F60||Factura a 60 días',
                                            4 => 'L30||Letra a 30 días',
                                            5 => 'L45||Letra a 45 días',
                                            6 => 'L60||Letra a 60 días');
        for ($i=0; $i <= count($arrayName)-1; $i++)
        { 
            $separa = explode('||', $arrayName[$i]);
            
            $value = $separa[0];
            $name = $separa[1];

            if($select == $value)
            {
                $output .= '<option value="'.$value.'" selected>'.$name.'</option>';
            }else
            {
                $output .= '<option value="'.$value.'">'.$name.'</option>';
            }
        }
        return $output;
    }
    function distribuidoras($select)
    {
        $output = null;

        $query = Database::Connection()->prepare("SELECT distribuidora_codigo AS codigo, 
                                                                        distribuidora_descripcion AS descripcion
                                                            FROM
                                                                        tbl_distribuidoras ORDER BY 1 ASC;");
        if($query->execute())
        {
            if($query->rowCount() > 0)
            {
                while ($rQuery0 = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $dst_cod = $rQuery0['codigo'];
                    $dst_desc = ucfirst(strtolower($rQuery0['descripcion']));    

                    if($select == $dst_cod)
                    {
                        $output .= '<option value="'.$dst_cod.'" selectd>'.$dst_desc.'</option>';
                    }else
                    {
                        $output .= '<option value="'.$dst_cod.'">'.$dst_desc.'</option>';
                    }
                    
                }
            }else
            {
                $output = 0;
            }
        }

        return $output;
    }