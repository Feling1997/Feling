<?php

require 'ejercicio7alumnos.php';
require 'funciones.php';

$mejorEstudiante = "";
$mejorPromedio =-1;
$mejorIndex =-1;
foreach ($estudiantes as $index=> $estudiante) {
    $promedio = calcularPromedio($estudiante['nota']);
    $estudiantes[$index]['promedio'] = $promedio; // Guardar promedio en el array
    if ($promedio > $mejorPromedio) {
        $mejorPromedio = $promedio;
        $mejorEstudiante = $estudiante['nombre'];
        $mejorIndex = $index;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Estudiantes</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <h2 class="titulo-centrado">Lista de Estudiantes</h2>
    
    <table>
        <tr>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Carrera</th>
            <th>Notas</th>
            <th>Promedio</th>
        </tr>

        <?php foreach ($estudiantes as $index => $estudiante): ?>
            <?php
                // Determinar clase para la fila
                $claseFila = '';
                if ($index == $mejorIndex) {
                    $claseFila = 'mejor';
                } elseif ($estudiante['promedio'] < 6) {
                    $claseFila = 'reprobado';
                }
            ?>
            <tr class="<?php echo $claseFila; ?>">
                <td><?php echo $estudiante['nombre']; ?></td>
                <td><?php echo $estudiante['edad']; ?></td>
                <td><?php echo $estudiante['carrera']; ?></td>
                <td><?php echo implode(", ", $estudiante['nota']); ?></td>
                <td><?php echo number_format($estudiante['promedio'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="footer">
        <h3>Mejor Estudiante</h3>
        <p>El mejor estudiante es: <strong><?php echo $mejorEstudiante; ?></strong> con un promedio de <strong><?php echo number_format($mejorPromedio, 2); ?></strong>.</p>
    </div>

    <p class="btn-container">
    <p class="centrar-boton">
    <a href="nuevo_estudiante.php" class="btn-agregar">Agregar Nuevo Estudiante</a>
    </p>

</body>
</html>