<?php
// Carga la conexion compartida con la base de datos.
require_once __DIR__ . '/../conexion.php';

// Contiene las operaciones de base de datos para vehiculos.
class MDL_Maquina
{

  // Obtiene todos los registros para mostrarlos en la tabla.
  public static function obtenerTodas()
  {

    // Abre la conexion y consulta los vehiculos por id descendente.
    $conexion = obtenerConexion();
    $stmt = $conexion->query("SELECT * FROM maquinas ORDER BY id DESC");


    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Busca un vehiculo por su id para cargarlo en el formulario.
  public static function obtenerPorId($id)
  {
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("SELECT * FROM maquinas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public static function insertar($tipo_vehiculo, $marca, $color, $modelo)
  {
    // Inserta un nuevo vehiculo usando parametros preparados.
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("INSERT INTO maquinas (tipo_vehiculo, marca, color, modelo) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$tipo_vehiculo, $marca, $color, $modelo]);
  }

  public static function actualizar($id, $tipo_vehiculo, $marca, $color, $modelo)
  {
    // Actualiza los datos del vehiculo que coincida con el id.
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("UPDATE maquinas SET tipo_vehiculo= ?, marca = ?, color = ?, modelo = ? WHERE id = ?");
    return $stmt->execute([$tipo_vehiculo, $marca, $color, $modelo, $id]);
  }

  public static function eliminar($id)
  {
    // Elimina de la base de datos el vehiculo indicado por id.
    $conexion = obtenerConexion();
    $stmt = $conexion->prepare("DELETE FROM maquinas WHERE id = ?");
    return $stmt->execute([$id]);
  }
}