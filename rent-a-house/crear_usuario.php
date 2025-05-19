<?php
require 'conexion.php'; // Asegúrate de que este archivo conecta a tu base de datos

$username = 'luisv';
$password = 'valderrey';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (:username, :password)");
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hashedPassword);

if ($stmt->execute()) {
    echo "Usuario de prueba 'admin' creado correctamente.\n";
} else {
    print_r($stmt->errorInfo());
}
?>