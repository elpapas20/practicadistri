<?php
$servername = "localhost";
$username = "root"; // Tu nombre de usuario MySQL
$password = ""; // Tu contraseña MySQL
$dbname = "gamezone";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
