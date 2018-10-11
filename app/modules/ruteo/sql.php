<?php 
#Code

function _sql_insertar_ruteo_()
{
  $sql = "INSERT INTO ruteo(vendedor, cliente, objetivo, importe, fecha, hora, obeservaciones, tipo)
          VALUES(:user_session, :codigos, :objetivos, :importes, :fecha, :horas, :observaciones, :tipos)";
  return $sql;
}