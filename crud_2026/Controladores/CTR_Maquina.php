<?php
// Carga el modelo que realiza las consultas a la base de datos.
require_once __DIR__ . '/../Modelos/MDL_Maquina.php';

// Controla las acciones permitidas sobre los vehiculos.
class CTR_Maquina
{

  // Procesa guardar, actualizar y eliminar segun la accion recibida.
  public function procesarAccion()
  {
    // Detecta si la accion requiere modificar datos.
    $escritura = $_SERVER['REQUEST_METHOD'] === 'POST'
      || isset($_GET['eliminar']);

    // Impide que un usuario sin rol admin modifique datos.
    if ($escritura && ($_SESSION['rol'] ?? '') !== 'admin') {
      http_response_code(403);
      exit('No tenés permiso para modificar vehículos.');
    }
    // Procesa el formulario enviado por metodo POST.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Lee el identificador y los datos enviados por el formulario.
      $id = $_POST['id'] ?? '';
      $tipo_vehiculo = $_POST['tipo_vehiculo'] ?? 'moto';
      $marca = trim($_POST['marca'] ?? '');
      $color = trim($_POST['color'] ?? '');
      $modelo = $_POST['modelo'] ?? 'deportivo';

      // Solo continua si se indico el tipo de vehiculo.
      if (!empty($tipo_vehiculo)) {
        // Un id existente actualiza el registro.
        if (!empty($id)) {
          MDL_Maquina::actualizar($id, $tipo_vehiculo, $marca, $color, $modelo);
          $msg = "vehiculo modificado con éxito.";
        } else {
          // Sin id existente crea un nuevo registro.
          MDL_Maquina::insertar($tipo_vehiculo, $marca, $color, $modelo);
          $msg = "vehiculo agregado con éxito.";
        }
        // Redirige a la lista y muestra el mensaje obtenido.
        header("Location: maquinas.php?msg=" . urlencode($msg));

        exit();
      }
    }

    // Elimina el vehiculo indicado en la consulta GET.
    if (isset($_GET['eliminar'])) {
      MDL_Maquina::eliminar($_GET['eliminar']);
      header("Location: maquinas.php?msg=" . urlencode("vehiculo eliminado"));
      exit();
    }
  }
}