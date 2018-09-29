<?php 
#Code

function listar_pedido_sql()
{
	$sql = "SELECT 
										orden_pedido.id AS id,
										orden_pedido.vendedor_id AS vendedor,
										orden_pedido.cliente_id AS cliente_ruc,
										maestro_clientes.nombre_comercial AS nombre_comercial,
										orden_pedido.distribuidora_id AS cod_dist,
										tbl_distribuidoras.distribuidora_descripcion AS desc_dist,
										orden_pedido.fecha_orden AS fecha,
										orden_pedido.pago_condicion AS condicion_pago,
										orden_pedido.vendedor_registro AS creador_pedido,
										orden_pedido.estado AS estado,
										GROUP_CONCAT(orden_pedido_items.id
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS id_items,
										GROUP_CONCAT(orden_pedido_items.producto_id
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS codigo_producto,
										GROUP_CONCAT(orden_pedido_items.producto_dsc
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS desc_producto,
										GROUP_CONCAT(orden_pedido_items.unidades
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS cantidad
									FROM
										orden_pedido
														INNER JOIN
										orden_pedido_items ON orden_pedido_id = orden_pedido.id
														INNER JOIN
										tbl_distribuidoras ON tbl_distribuidoras.distribuidora_codigo = orden_pedido.distribuidora_id
														INNER JOIN
										maestro_clientes ON maestro_clientes.ruc = orden_pedido.cliente_id
									WHERE
										fecha_orden = :fecha
														AND vendedor_registro = :vendedor OR vendedor_id = :vendedor
														GROUP BY orden_pedido.id;";
	return $sql;
}
function buscar_pedido_sql()
{
	$sql = "SELECT 
										orden_pedido.id AS id,
										orden_pedido.vendedor_id AS vendedor,
										orden_pedido.cliente_id AS cliente_ruc,
										maestro_clientes.nombre_comercial AS nombre_comercial,
										orden_pedido.distribuidora_id AS cod_dist,
										tbl_distribuidoras.distribuidora_descripcion AS desc_dist,
										orden_pedido.fecha_orden AS fecha,
										orden_pedido.pago_condicion AS condicion_pago,
										orden_pedido.vendedor_registro AS creador_pedido,
										orden_pedido.estado AS estado,
										GROUP_CONCAT(orden_pedido_items.id
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS id_items,
										GROUP_CONCAT(orden_pedido_items.producto_id
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS codigo_producto,
										GROUP_CONCAT(orden_pedido_items.producto_dsc
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS desc_producto,
										GROUP_CONCAT(orden_pedido_items.unidades
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS cantidad,
										GROUP_CONCAT(orden_pedido_items.precio_lista
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS precio_lista,
										GROUP_CONCAT(orden_pedido_items.precio_unitario
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS precio_unitario,
										GROUP_CONCAT(orden_pedido_items.descuento
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS descuento,
										GROUP_CONCAT(orden_pedido_items.descuento
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS impuesto,
										GROUP_CONCAT(orden_pedido_items.precio_linea
														ORDER BY orden_pedido_items.id
														SEPARATOR '||') AS precio_linea
									FROM
										orden_pedido
														INNER JOIN
										orden_pedido_items ON orden_pedido_id = orden_pedido.id
														INNER JOIN
										tbl_distribuidoras ON tbl_distribuidoras.distribuidora_codigo = orden_pedido.distribuidora_id
														INNER JOIN
										maestro_clientes ON maestro_clientes.ruc = orden_pedido.cliente_id
									WHERE
										orden_pedido.id = :id";
	return $sql;
}
function estado_pedido_sql()
{
		$sql = "SELECT orden_pedido.estado AS estado FROM orden_pedido WHERE orden_pedido.id = :id";
		return $sql;
}
function propietario_pedido_sql()
{
		$sql = "SELECT orden_pedido.vendedor_id AS vendedor, orden_pedido.vendedor_registro AS vendedor_registro FROM orden_pedido WHERE orden_pedido.id = :id";
		return $sql;
}