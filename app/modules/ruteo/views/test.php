<?php

$fecha = "2018-10-16";#formatfecha($_POST['fecha']);
$today = "2018-10-13";#date("Y-m-d");

$add_month = 3;#MESES DISPONIBLES

$_split_today = explode('-', $today);

$t_day = $_split_today[2];
$t_month = $_split_today[1];
$t_year = $_split_today[0];


$_split_date = explode("-", $fecha);
$year = $_split_date[0];
$month = $_split_date[1];
$day = $_split_date[2];

$days_in_month = __days_in_month($month, $year);

$build_pool_1 = NULL;
$build_pool_2 = NULL;
$_pool_dates = array();

if(FALSE)#SI HAY EXCEPCIONES SQL
{
	
}else
{
	if($t_day <= 13)
	{
					$build_pool_1 = $t_year.'-'.$t_month.'-16';
					$build_pool_2 = date("Y-m-d", strtotime("+".$add_month." month", strtotime($build_pool_1)));

	}else if ($t_day <= 25)
	{
					$build_pool_1 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-01')));
					$build_pool_2 = date("Y-m-d", strtotime("+".$add_month." month", strtotime($build_pool_1))); 

	}else if ($t_day > 25 && $t_day <= $days_in_month)
	{
					$build_pool_1 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-16')));
					$build_pool_2 = date("Y-m-d", strtotime("+".$add_month." month", strtotime($build_pool_1)));
	}

	
}
#####BUILD POOL FECHAS###
for($i = $build_pool_1; $i <= $build_pool_2; $i = date("Y-m-d", strtotime($i ."+ 1 days")))
{
		$_pool_dates[] = $i;
}

if(in_array($fecha, $_pool_dates))
{
	print "YUUUUP<pre>"	;
	var_dump($_pool_dates);
}else
{
	print "NOOOOOP<pre>"	;
	var_dump($_pool_dates);
}









/*$_rt_max_opn_1 = 13;# 16 - 31 - in month 
$_rt_max_opn_2 = 25;# 01 - 15 - next month



$_split_date = explode("-", $fecha);
$year = $_split_date[0];
$month = $_split_date[1];
$day = $_split_date[2];

$days_in_month = __days_in_month($month, $year);
        #VAR'S


        #################### Intervalos ##################

$interval_1_i = 16;
$interval_1_t = __days_in_month($month, $year);

$interval_2_i = 01;
$interval_2_t = 15;

$build_fecha1 = NULL;
$build_fecha2 = NULL;

if(FALSE)#SI HAY EXCEPCIONES SQL
{
	
}else
{
	if($t_day <= 13)
	{
					$build_fecha1 = $t_year.'-'.$t_month.'-16';
					$build_fecha2 = $t_year.'-'.$t_month.'-'.$days_in_month;
	}else if ($t_day <= 25)
	{
					$build_fecha1 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-01')));
					$build_fecha2 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-15')));            
	}else
	{
		$build_fecha1 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-01')));
		$build_fecha2 = date("Y-m-d", strtotime("+1 month", strtotime($t_year.'-'.$t_month.'-15')));
	}
}


if(check_in_range_date($build_fecha1, $build_fecha2, $fecha))
{
    print "Oqi =>   <br> F1= " . $build_fecha1."  <br>  F2= ".$build_fecha2." <br> F = ".$fecha;
}else
{
    print "CERRADO  =>   <br> F1= " . $build_fecha1."  <br>  F2= ".$build_fecha2." <br> F = ".$fecha;
}*/


