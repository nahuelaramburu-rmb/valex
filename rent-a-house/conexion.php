<?php
$host = 'localhost'; // Se mantiene el host
$dbname = 'c2800777_valex'; // Nombre de la base de datos del segundo bloque
$usuario = 'c2800777_valex'; // Usuario del segundo bloque
$password = '02mizaWOgi'; // Contraseña del segundo bloque
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>