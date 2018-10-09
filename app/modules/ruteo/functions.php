<?php

function _objetivos_($select)
{
        $output = null;
        $arrayName = array(0 => 'vs||Visita',
                           1 => 'co||Cobranza',
                           2 => 'vn||Venta');
        for ($i=0; $i <= count($arrayName)-1; $i++)
        { 
            $separa = explode('||', $arrayName[$i]);
            
            $value = $separa[0];
            $name = $separa[1];

            if($select == $value)
            {
                $output .= '<option value="'.$value.'" selected><label class="align-middle">'.$name.'</label></option>';
            }else
            {
                $output .= '<option value="'.$value.'"><label class="align-middle">'.$name.'</label></option>';
            }
        }
        return $output;
}
