<?php
require_once __DIR__ . '/../conexion.php';

class MDL_Maquina
{

  public static function obtenerTodas()
  {

    $conexion = obtenerConexion();
    $stmt = $conexion->query("SELECT * FROM maquinas ORDER BY id DESC");


    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function obtenerPorId($id)
  {
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("SELECT * FROM maquinas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public static function insertar($tipo_vehiculo, $marca, $color, $modelo)
  {
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("INSERT INTO maquinas (tipo_vehiculo, marca, color, modelo) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$tipo_vehiculo, $marca, $color, $modelo]);
  }

  public static function actualizar($id, $tipo_vehiculo, $marca, $color, $modelo)
  {
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("UPDATE maquinas SET tipo_vehiculo= ?, marca = ?, color = ?, modelo = ? WHERE id = ?");
    return $stmt->execute([$tipo_vehiculo, $marca, $color, $modelo, $id]);
  }

  public static function eliminar($id)
  {
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("DELETE FROM maquinas WHERE id = ?");
    return $stmt->execute([$id]);
  }
}