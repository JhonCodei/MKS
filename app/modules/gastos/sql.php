<?php 
#Code
function _sql_eliminar_gasto()
{
	$sql = "DELETE FROM gastos_detalle WHERE id = :id";
	return $sql;
}
function _sql_update_gastos_detalle()
{
		$sql = "UPDATE gastos_detalle SET fecha = :fecha, motivo = :motivo, tipo = :tipo, documento = :documento, ruc = :ruc, importe = :importe, 
										ventas = :ventas, ruc_cliente = :ruc_cliente, observacion = :observacion WHERE id = :id";
		return $sql;
}
function _sql_listado_gastos_id()
{
	$sql = "SELECT 
    fecha,
    motivo,
    tipo,
    documento,
    ruc,
    importe,
    ventas,
    ruc_cliente,
    observacion
				FROM
    gastos_detalle
WHERE
    id = :id;";
	return $sql;
}
function _sql_listado_gastos()
{
	$sql = "SELECT 
				gastos_detalle.id as id,
    fecha,
    motivo,
    tipo,
    documento,
    ruc,
    importe,
    ventas,
    ruc_cliente,
    observacion
	FROM
					gastos_detalle
									INNER JOIN
					gastos ON gastos.id = gastos_detalle.id_gastos
	WHERE
					vendedor = :vendedor AND periodo = :periodo
									AND quincena = :quincena";
	return $sql;
}
function _sql_search_gastos()
{
		$sql = "SELECT id FROM gastos WHERE vendedor = :vendedor AND periodo = :periodo AND quincena = :quincena LIMIT 0,1";
		return $sql;
}
function _sql_insert_gastos()
{
		$sql = "INSERT INTO gastos(vendedor, periodo, quincena) VALUES (:vendedor, :periodo, :quincena)";
		return $sql;
}
function _sql_insert_gastos_detalle()
{
	$sql = "INSERT INTO gastos_detalle(fecha, motivo, tipo, documento, ruc, importe, ventas, ruc_cliente, observacion, id_gastos)
									VALUES(:fecha, :motivo, :tipo, :documento, :ruc, :importe, :ventas, :ruc_cliente, :observacion, :id_gastos)";
		return $sql;
}