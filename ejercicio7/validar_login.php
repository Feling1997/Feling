<?php
session_start();
require 'conexion.php'; // Conexión a la base de datos

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// Buscar usuario en la tabla `alumnos`
$sql = "SELECT id, nombre, usuario, contraseña, tipo FROM alumnos WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado && $resultado->num_rows === 1) {
    $alumno = $resultado->fetch_assoc();

    if ($password === $alumno['contraseña']) {
        // Guardar datos en sesión
        $_SESSION['usuario'] = $alumno['usuario'];
        $_SESSION['rol'] = $alumno['tipo']; // 'admin' o 'alumno'
        $_SESSION['usuario_id'] = $alumno['id'];
        $_SESSION['nombre_usuario'] = $alumno['nombre'];

        header("Location: ejercicio7.php");
        exit;
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.location.href='login.php';</script>";
}
?>
