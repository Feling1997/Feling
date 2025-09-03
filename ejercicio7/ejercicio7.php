<?php

require 'ejercicio7alumnos.php';
require 'conexion.php';
require 'funciones.php';

$estudiantes = [];

$sql = "
    SELECT 
        a.id AS alumno_id,
        a.nombre AS alumno_nombre,
        a.edad,
        c.nombre AS carrera,
        m.nombre AS materia,
        n.nota
    FROM alumnos a
    JOIN carreras c ON a.carrera_id = c.id
    JOIN notas n ON a.id = n.alumno_id
    JOIN materias m ON n.materia_id = m.id
    ORDER BY a.id, m.nombre;
";

$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $id = $fila['alumno_id'];

        if (!isset($estudiantes[$id])) {
            $estudiantes[$id] = [
                'nombre' => $fila['alumno_nombre'],
                'edad' => $fila['edad'],
                'carrera' => $fila['carrera'],
                'nota' => []
            ];
        }

        $estudiantes[$id]['nota'][] = $fila['nota'];
    }
} else {
    echo "No se encontraron datos o error en la consulta: " . $conn->error;
    // Para evitar errores, podés hacer un exit si no hay datos
    exit;
}

$mejorEstudiante = "";
$mejorPromedio =-1;
$mejorIndex =-1;
$index=0;
foreach ($estudiantes as $id=> $estudiante) {
    $promedio = calcularPromedio($estudiante['nota']);
    $estudiantes[$id]['promedio'] = $promedio;
    if ($promedio > $mejorPromedio) {
        $mejorPromedio = $promedio;
        $mejorEstudiante = $estudiante['nombre'];
        $mejorIndex = $index;
    }
    $index++;
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


    <div class="contenedor-botones">
    <a href="nuevo_estudiante.php" class="btn-agregar">Agregar</a>
    <a href="modificar.php?id=<?= $estudiante_id ?>" class="btn-modificar">Modificar</a>
    <a href="eliminar.php?id=<?= $estudiante_id ?>" class="btn-eliminar">Eliminar</a>
    </div>

</body>
</html>