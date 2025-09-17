<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
// Conectar a la base de datos
require 'conexion.php';

// Consulta SQL para obtener todas las carreras ordenadas alfabéticamente
$sql = "SELECT id, nombre FROM carreras ORDER BY nombre";

// Ejecutar la consulta y guardar el resultado
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Carreras</title>
    <!-- Vinculamos la hoja de estilos externa -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- Título centrado -->
    <h2 class="titulo-centrado">Lista de Carreras</h2>

    <!-- Tabla para mostrar las carreras -->
    <table>
        <tr>
            <th>Nombre</th>   <!-- Columna para nombre de la carrera -->
            <th>ID</th>       <!-- Columna para ID de la carrera -->
        </tr>

        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <!-- Mientras haya filas en el resultado, recorrerlas -->
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['nombre']); ?></td> <!-- Mostrar nombre con protección contra XSS -->
                    <td><?php echo $fila['id']; ?></td>                  <!-- Mostrar el ID -->
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Mostrar mensaje si no hay carreras en la base -->
            <tr><td colspan="2">No hay carreras registradas.</td></tr>
        <?php endif; ?>
    </table>

    <!-- Botón para regresar al menú principal -->
    <div class="contenedor-botones">
        <a href="ejercicio7.php" class="btn-agregar">Volver al menú principal</a>
    </div>

</body>
</html>
