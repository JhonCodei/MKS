<?php 

function errorPDO(	$Statement	)
{
	$ErrorArray = implode(	','	, $Statement->errorInfo()	);
	$ErrorView  = end(	(	explode(	','	, $ErrorArray	)	)	);	

	return @$ErrorView;
}

?>