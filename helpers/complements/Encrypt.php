<?php

function descrypt($var)
{
	$key    	= "[!$$###]]";
	$cadena 	= explode('-', base64_decode($var)); 
	$indice 	= 0; 
	$tope   	= count($cadena); 
	$n      	= strlen($key); 
	$decode 	= "";
	     
	do    
	{ 
		$decode .= chr(ord($key[$indice % $n]) ^ $cadena[$indice]); 
	} 
	while (++$indice < $tope);	    
	$decode  = str_replace($key, '', $decode); 

	return $decode;
}

function encrypt($var)
{
	$key       	= "[!$$###]]";
	$bodycode  	= $var.$key;
	$indice    	= 0; 
	$tope      	= strlen($bodycode); 
	$n         	= strlen($key); 
	$resultado 	= array(); 
				     
	do    
	{ 
		$resultado[$indice] = ord($key[$indice % $n]) ^ ord($bodycode[$indice]); 
	} 
	while (++$indice < $tope); 
	$code =	base64_encode(utf8_encode(implode('-',$resultado))); 	

	return $code;
}
?>