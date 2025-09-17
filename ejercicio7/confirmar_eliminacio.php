<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
require 'conexion.php';     // Conexión a la base de datos
require 'funciones.php';    // Funciones auxiliares si las usás

// VERIFICAR Y OBTENER EL ALUMNO POR ID
if (!isset($_GET['id'])) {
    echo "ID no especificado.";
}

$id = intval($_GET['id']); // Sanear el valor recibido

// Buscar el nombre del alumno
$stmt = $conn->prepare("SELECT nombre FROM alumnos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Alumno no encontrado.";
}

$alumno = $resultado->fetch_assoc();
$nombre = $alumno['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Eliminación</title>
    <!-- ✅ Enlazamos tu archivo de estilos externo -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- ✅ Título de la página con el nombre del alumno -->
    <h2 class="titulo-centrado">
        ¿Qué deseas hacer con<br><strong><?php echo htmlspecialchars($nombre); ?></strong>?
    </h2>

    <!-- ✅ Formulario con opciones de eliminación -->
    <form action="eliminar.php" method="POST" style="max-width: 500px; margin: auto; background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

        <!-- ✅ Pasamos el ID como campo oculto -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <!-- ✅ Botones con acciones -->
        <div class="contenedor-botones">
            <!-- Solo elimina notas del alumno -->
            <button type="submit" name="accion" value="eliminar_notas" class="btn-modificar">
                Eliminar solo notas
            </button>

            <!-- Elimina completamente al alumno y sus notas -->
            <button type="submit" name="accion" value="eliminar_alumno" class="btn-eliminar">
                Eliminar alumno
            </button>
        </div>

        <!-- ✅ Botón para cancelar -->
        <div class="centrar-boton">
            <a href="ejercicio7.php" class="btn-agregarCarrera">Cancelar</a>
        </div>
    </form>

</body>
</html>
