<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
// Conectar a la base de datos
require 'conexion.php';

// Consulta SQL para obtener todas las materias junto con el nombre de su carrera asociada
// Se usa JOIN para unir la tabla materias con la tabla carreras según carrera_id
$sql = "
    SELECT 
        m.id AS materia_id, 
        m.nombre AS materia_nombre, 
        c.nombre AS carrera_nombre
    FROM materias m
    LEFT JOIN carreras c ON m.carrera_id = c.id
    ORDER BY m.nombre
";

// Ejecutar la consulta y guardar el resultado
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Materias</title>
    <!-- Vinculamos la hoja de estilos externa -->
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <!-- Título centrado -->
    <h2 class="titulo-centrado">Lista de Materias</h2>

    <!-- Tabla para mostrar las materias con su carrera -->
    <table>
        <tr>
            <th>Nombre</th>         <!-- Columna para nombre de la materia -->
            <th>Carrera</th>        <!-- Nueva columna para mostrar la carrera -->
        </tr>

        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <!-- Recorremos cada fila del resultado -->
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['materia_nombre']); ?></td>  <!-- Mostrar nombre materia -->
                    <td>
                        <?php 
                            // Mostrar el nombre de la carrera o "Sin carrera asignada" si es null
                            echo $fila['carrera_nombre'] ? htmlspecialchars($fila['carrera_nombre']) : 'Sin carrera asignada'; 
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <!-- Mensaje si no hay materias -->
            <tr><td colspan="3">No hay materias registradas.</td></tr>
        <?php endif; ?>
    </table>

    <!-- Botón para volver al menú principal -->
    <div class="contenedor-botones">
        <a href="ejercicio7.php" class="btn-agregar">Volver al menú principal</a>
    </div>

</body>
</html>
