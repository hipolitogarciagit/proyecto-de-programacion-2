<?php
session_start();

if (!isset($_SESSION['user'])) {

  header("Location: index.php");
  exit;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Panel Administrativo</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h2>Bienvenido al panel, <?php echo htmlspecialchars($_SESSION['user']); ?></h2>

  <br>


  <div style="margin: 15px 0;">
    <a href="maquinas.php" class="btn btn-primary"
      style="padding: 8px 15px; background: #0d6efd; color: white; text-decoration: none; border-radius: 4px;">
      Gestionar Vahiculos
    </a>
  </div>

  <br>
  <a href="logout.php">Cerrar Sesión</a>
</body>

</html>