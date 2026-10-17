<?php
require_once __DIR__ . '/../conexion.php';

class MDL_Usuario
{

    public static function registrar($email, $passwordHash)
    {
        $db = obtenerConexion();
        $sql = "INSERT INTO usuarios (email, password) VALUES (:email, :password)";
        $stmt = $db->prepare($sql);

        return $stmt->execute

        ([
                'email' => $email,
                'password' => $passwordHash
            ]);
    }

    public static function obtenerPorEmail($email)
    {
        $db = obtenerConexion();
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>