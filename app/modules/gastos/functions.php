<?php

function _motivos_($select)
{
		$output = null;
		$arrayName = array(0 => 'PSJ||Pasajes',
                     1 => 'ALI||Alimentos',
                     2 => 'HOS||Hospedajes',
                     3 => 'OTR||Otros');
   for ($i = 0; $i < count($arrayName); $i++)
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
function _view_motivos_($select)
{
	switch ($select)
	{
		case 'PSJ':
			$r = "Pasajes";
			break;
		case 'ALI':
			$r = "Alimentos";
			break;
		case 'HOS':
			$r = "Hospedajes";
			break;
		case 'OTR':
			$r = "Otros";
			break;
		default:
			# code...
			break;
	}
	return $r;
}
function _tipo_documentos_($select)
{
		$output = null;
		$arrayName = array(0 => 'FAC||Factura',
                     1 => 'RHP||Recibo por honorarios profesionales',
                     2 => 'BVN||Botela de venta',
																					3 => 'BCAC||Botelto de compañia de aviación comercial',
																					4 => 'TKTM||Ticket o cinta emitido por maquina registradora',
																					5 => 'BVT||Boleto de viaje (Terrestre)');
   for ($i = 0; $i < count($arrayName); $i++)
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
function _view_tipo_documentos_($select)
{
	switch ($select)
	{
		case 'FAC':
			$r = "Factura";
			break;
		case 'RHP':
			$r = "Recibo por honorarios profesionales";
			break;
		case 'BVN':
			$r = "Botela de venta";
			break;
		case 'BCAC':
			$r = "Botelto de compañia de aviación comercial";
			break;
		case 'TKTM':
			$r = "Ticket o cinta emitido por maquina registradora";
			break;
		case 'BVT':
			$r = "Boleto de viaje (Terrestre)";
			break;
		default:
			# code...
			break;
	}
	return $r;
}
function validate_periodo_gastos()
{
    $dia = date('d');

    if($dia <= 15)
    {
        $option = '<option value="1" selected>Primera</option>
                    <option value="2">Segunda</option>';
    }else
    {
        $option = '<option value="1">Primera</option>
                    <option value="2" selected>Segunda</option>';
    }
    print $option;
}