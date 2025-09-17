<?php
session_start();

// Usuario y clave
$usuario_valido = "admin";
$password_valido = "1234";

// Recibe datos del formulario
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// 📌 Verifica credenciales
if ($usuario === $usuario_valido && $password === $password_valido) {
    $_SESSION['usuario'] = $usuario;
    header("Location: ejercicio7.php");
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href='login.php';</script>";
}
?>
