<?php
session_start();

// Si ya está logueado, redirige
if (isset($_SESSION['usuario'])) {
    header("Location: ejercicio7.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <div class="contenedor-login">
        <form action="validar_login.php" method="POST" class="formulario-login">
            <h2 class="titulo-login">Iniciar Sesión</h2>

            <div class="campo-formulario">
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario" required>
            </div>

            <div class="campo-formulario">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="centrar-boton">
                <button type="submit" class="btn-agregarCarrera">Ingresar</button>
            </div>
        </form>
    </div>

</body>
</html>
