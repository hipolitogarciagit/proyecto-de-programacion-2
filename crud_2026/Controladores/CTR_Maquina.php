<?php
require_once __DIR__ . '/../Modelos/MDL_Maquina.php';

class CTR_Maquina
{

  public function procesarAccion()
  {
    $escritura = $_SERVER['REQUEST_METHOD'] === 'POST'
      || isset($_GET['eliminar']);

    if ($escritura && ($_SESSION['rol'] ?? '') !== 'admin') {
      http_response_code(403);
      exit('No tenés permiso para modificar vehículos.');
    }
    /* Guardar / Editar (POST)*/
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'] ?? '';
      $tipo_vehiculo = $_POST['tipo_vehiculo'] ?? 'moto';
      $marca = trim($_POST['marca'] ?? '');
      $color = trim($_POST['color'] ?? '');
      $modelo = $_POST['modelo'] ?? 'deportivo';

      if (!empty($tipo_vehiculo)) {
        if (!empty($id)) {
          MDL_Maquina::actualizar($id, $tipo_vehiculo, $marca, $color, $modelo);
          $msg = "vehiculo modificado con éxito.";
        } else {
          MDL_Maquina::insertar($tipo_vehiculo, $marca, $color, $modelo);
          $msg = "vehiculo agregado con éxito.";
        }
        header("Location: maquinas.php?msg=" . urlencode($msg));

        exit();
      }
    }

    /* Eliminación (GET)*/
    if (isset($_GET['eliminar'])) {
      MDL_Maquina::eliminar($_GET['eliminar']);
      header("Location: maquinas.php?msg=" . urlencode("vehiculo eliminado"));
      exit();
    }
  }
}