<?php
// Parámetros de conexión
$servername = "localhost";  // usualmente localhost si trabajás en tu PC local
$username = "root";         // tu usuario de MySQL (por defecto es root)
$password = "aestudiar";    // la contraseña que configuraste para MySQL
$dbname = "ejercicio7Alumnos";  // nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
