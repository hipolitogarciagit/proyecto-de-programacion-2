<?php
require_once "../Modelos/MDL_Usuario.php";
session_start();

if (isset($_POST['accion'])) {
  $accion = $_POST['accion'];

  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if ($accion === 'registrar') {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    try {
      MDL_Usuario::registrar($email, $passwordHash);
      header("Location: ../index.php?exito=Registrado correctamente");
    } catch (Exception $e) {
      header("Location: ../index.php?error=El correo ya está registrado");
    }
    exit;
  }

  if ($accion === 'login') {
    $usuario = MDL_Usuario::obtenerPorEmail($email);

    if ($usuario && password_verify($password, $usuario['password'])) {
      $_SESSION['user'] = $usuario['email'];
      header("Location: ../admin.php");
    } else {
      header("Location: ../index.php?error=DATOS INCORRECTOS");
    }
    exit;
  }
}
?>