<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
// Importamos el archivo de conexión a la base de datos
require 'conexion.php';

// Variable para guardar mensajes de error o éxito
$mensaje = "";

// Verificamos si el formulario fue enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtenemos el nombre ingresado por el usuario y eliminamos espacios extra
    $nombreCarrera = trim($_POST["nombre"]);

    // Validamos que el nombre no esté vacío
    if ($nombreCarrera === "") {
        $mensaje = "Por favor, ingrese un nombre de carrera.";
    } else {
        // Preparamos la consulta para insertar una nueva carrera
        $stmt = $conn->prepare("INSERT INTO carreras (nombre) VALUES (?)");

        // Asignamos el parámetro (nombre de la carrera)
        $stmt->bind_param("s", $nombreCarrera);

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            // Si se insertó correctamente, redirigimos a la página principal
            header("Location: ejercicio7.php");
            exit;
        } else {
            // Si hay error en la ejecución, lo mostramos
            $mensaje = "Error al agregar la carrera: " . $stmt->error;
        }

        // Cerramos la consulta
        $stmt->close();
    }
}
?>

<!-- Interfaz HTML del formulario -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nueva Carrera</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Reutilizamos tus estilos -->
</head>
<body>

    <h2 class="titulo-centrado">Agregar Nueva Carrera</h2>

    <!-- Mostramos el mensaje de error o éxito -->
    <?php if ($mensaje): ?>
        <p class="error" style="text-align: center; color: red;"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <!-- Formulario para agregar carrera -->
    <form method="POST" action="agregarCarrera.php" style="max-width: 400px; margin: auto;">
        <label>Nombre de la carrera:</label><br>
        <input type="text" name="nombre" required style="width: 100%; padding: 10px; margin-top: 10px;"><br><br>

        <div style="text-align: center;">
            <button type="submit" class="btn-modificar">Guardar Carrera</button>
            <a href="ejercicio7.php" class="btn-eliminar">Volver</a>
        </div>
    </form>

</body>
</html>
