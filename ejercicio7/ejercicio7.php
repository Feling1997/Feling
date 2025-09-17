<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}

// Importa archivos necesarios
require 'ejercicio7alumnos.php'; // Opcional, si tiene funciones o configuraciones globales
require 'conexion.php';          // Conexión a la base de datos ($conn)
require 'funciones.php';         // Funciones auxiliares, como calcularPromedio()

// Array donde se guardarán todos los estudiantes y sus datos
$estudiantes = [];

// Consulta SQL: une alumnos, carreras, materias y notas
$sql = "
    SELECT 
        a.id AS alumno_id,
        a.nombre AS alumno_nombre,
        a.edad,
        c.nombre AS carrera,
        m.nombre AS materia,
        m.id AS materia_id,
        n.nota
    FROM alumnos a
    JOIN carreras c ON a.carrera_id = c.id
    LEFT JOIN notas n ON a.id = n.alumno_id
    LEFT JOIN materias m ON n.materia_id = m.id
    ORDER BY m.nombre;
";


// Ejecutamos la consulta
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $id = $fila['alumno_id'];

        // Inicializar alumno si no existe en el array
        if (!isset($estudiantes[$id])) {
            $estudiantes[$id] = [
                'nombre' => $fila['alumno_nombre'],
                'edad' => $fila['edad'],
                'carrera' => $fila['carrera'],
                'materias' => []
            ];
        }

        // Si la materia no es null, agregamos las notas
        if (!is_null($fila['materia'])) {
            $materiaNombre = $fila['materia'];

            if (!isset($estudiantes[$id]['materias'][$materiaNombre])) {
                $estudiantes[$id]['materias'][$materiaNombre] = [];
            }

            // Agregamos la nota solo si no es null
            if (!is_null($fila['nota'])) {
                $estudiantes[$id]['materias'][$materiaNombre][] = $fila['nota'];
            }
        }
    }
} else {
    echo "No se encontraron datos o error en la consulta: " . $conn->error;
}


// Variables para calcular el mejor estudiante
$mejorEstudiante = "";
$mejorPromedio = -1;
$mejorIndex = -1;
$index = 0;

// Recorremos los estudiantes para calcular promedios y encontrar al mejor
foreach ($estudiantes as $id => $estudiante) {
    $todasLasNotas = [];

    // Unimos todas las notas de todas las materias
    foreach ($estudiante['materias'] as $notasMateria) {
        $todasLasNotas = array_merge($todasLasNotas, $notasMateria);
    }

    // Calculamos el promedio general del estudiante
    $promedio = calcularPromedio($todasLasNotas);
    $estudiantes[$id]['promedio'] = $promedio;

    // Actualizamos al mejor estudiante si corresponde
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
    <link rel="stylesheet" href="estilos.css"> <!-- Tu archivo CSS -->
</head>
<body>

    <h2 class="titulo-centrado">Lista de Estudiantes</h2>
    
    <table>
        <tr>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Carrera</th>
            <th>Notas por Materia</th>
            <th>Promedio</th>
            <th>Acciones</th>
        </tr>

        <?php $index = 0; ?>
        <?php foreach ($estudiantes as $alumno_id => $estudiante): ?>
            <?php
                // Determinar clase de fila según si es el mejor o si está reprobado
                $claseFila = '';
                if ($index == $mejorIndex) {
                    $claseFila = 'mejor';
                } elseif ($estudiante['promedio'] < 6) {
                    $claseFila = 'reprobado';
                }
                $index++;
            ?>

            <tr class="<?php echo $claseFila; ?>">
                <td><?php echo $estudiante['nombre']; ?></td>
                <td><?php echo $estudiante['edad']; ?></td>
                <td><?php echo $estudiante['carrera']; ?></td>
                <td>
                <?php 
                    if (!empty($estudiante['materias'])) {
                        foreach ($estudiante['materias'] as $materia => $notas) {
                        echo "<strong>" . htmlspecialchars($materia) . ":</strong> " . implode(", ", $notas) . "<br>";
                        }
                    } else {
                        echo "Sin notas";  // O lo que quieras mostrar cuando no haya notas
                    }
                ?>
                </td>
                <td><?php echo number_format($estudiante['promedio'], 2); ?></td>
                <td>
                    <a href="agregarNota.php?id=<?php echo $alumno_id; ?>" class="btn-nota">Agregar nota</a>
                    <a href="modificar.php?id=<?php echo $alumno_id; ?>" class="btn-modificar">Modificar</a>
                    <a href="confirmar_eliminacio.php?id=<?php echo $alumno_id; ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro que deseas eliminar?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="footer">
        <h3>Mejor Estudiante</h3>
        <p>
            El mejor estudiante es: <strong><?php echo $mejorEstudiante; ?></strong> con un promedio de <strong><?php echo number_format($mejorPromedio, 2); ?></strong>.
        </p>
    </div>

    <div class="contenedor-botones">
        <a href="nuevo_estudiante.php" class="btn-agregar">Agregar estudiante</a>
        <a href="agregarMateria.php" class="btn-agregar">Agregar materia</a>
        <a href="agregarCarrera.php" class="btn-agregar">Agregar carrera</a>
        <a href="lista_carreras.php" class="btn-lista">Lista de carreras</a>
        <a href="lista_materias.php" class="btn-lista">Lista de materias</a>
        <a href="login_salida.php" class="btn-eliminar_grande">Cerrar sesión</a>
    </div>

</body>
</html>
