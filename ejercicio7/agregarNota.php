<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
// Incluye el archivo que contiene la conexión a la base de datos
require 'conexion.php';

// Verifica si se pasó un ID de estudiante por GET (en la URL)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de estudiante no proporcionado.");
}

// Convierte el ID a entero por seguridad
$alumno_id = intval($_GET['id']);
$mensaje = ""; // Para mostrar mensajes de error o éxito al usuario

// Obtener nombre y carrera del alumno
$stmt = $conn->prepare("SELECT nombre, carrera_id FROM alumnos WHERE id = ?");
$stmt->bind_param("i", $alumno_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Si no se encuentra el alumno, detener ejecución
if ($resultado->num_rows === 0) {
    die("Estudiante no encontrado.");
}

$alumno = $resultado->fetch_assoc(); // Datos del alumno
$stmt->close();

$carrera_id = $alumno['carrera_id'];  // ID de la carrera del alumno

// Obtener materias que correspondan a la carrera del alumno
$stmtMaterias = $conn->prepare("SELECT id, nombre FROM materias WHERE carrera_id = ?");
$stmtMaterias->bind_param("i", $carrera_id);
$stmtMaterias->execute();
$materias = $stmtMaterias->get_result();

// Procesar formulario POST para guardar notas
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $materia_id = intval($_POST['materia_id']); // ID de la materia seleccionada
    $notas = $_POST['notas']; // Arreglo de notas enviadas desde el formulario

    // Verificar cuántas notas ya existen para ese alumno y materia
    $stmtCheck = $conn->prepare("SELECT COUNT(*) AS total FROM notas WHERE alumno_id = ? AND materia_id = ?");
    $stmtCheck->bind_param("ii", $alumno_id, $materia_id);
    $stmtCheck->execute();
    $resultadoCheck = $stmtCheck->get_result();
    $fila = $resultadoCheck->fetch_assoc();
    $cantidadExistente = intval($fila['total']);
    $stmtCheck->close();

    // Filtrar las nuevas notas (solo las no vacías)
    $nuevasNotas = array_filter($notas, function($nota) {
        return $nota !== "";
    });

    // Validar que el total (existentes + nuevas) no sea mayor a 3
    if ($cantidadExistente + count($nuevasNotas) > 3) {
        $mensaje = "Ya existen $cantidadExistente nota(s) registradas para esta materia. Solo se permiten 3 en total.";
    } else {
        // Insertar las nuevas notas en la base de datos
        $stmt = $conn->prepare("INSERT INTO notas (alumno_id, materia_id, nota) VALUES (?, ?, ?)");

        foreach ($nuevasNotas as $nota) {
            $nota_float = floatval($nota); // Convertir a número decimal
            $stmt->bind_param("iid", $alumno_id, $materia_id, $nota_float);
            $stmt->execute();
        }

        $stmt->close();

        // Redirigir después de guardar correctamente
        header("Location: ejercicio7.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nota</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <h2 class="titulo-centrado">Agregar Nota para <?php echo htmlspecialchars($alumno['nombre']); ?></h2>

    <!-- Mostrar mensaje de error si existe -->
    <?php if (!empty($mensaje)): ?>
        <div style="color: red; text-align: center; margin-bottom: 20px;">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <!-- Formulario para ingresar notas -->
    <form method="POST" style="max-width: 500px; margin: auto; padding: 20px; background-color: #fff; border-radius: 8px;">

        <!-- Selección de la materia -->
        <label for="materia_id">Materia:</label>
        <select name="materia_id" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
            <option value="">Seleccione una materia</option>
            <?php while ($row = $materias->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo htmlspecialchars($row['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Campos para ingresar hasta 3 notas -->
        <label>Notas:</label><br>
        <input type="number" name="notas[]" step="0.01" placeholder="Nota 1" style="width: 30%; padding: 8px;"><br><br>
        <input type="number" name="notas[]" step="0.01" placeholder="Nota 2 (opcional)" style="width: 30%; padding: 8px;"><br><br>
        <input type="number" name="notas[]" step="0.01" placeholder="Nota 3 (opcional)" style="width: 30%; padding: 8px;"><br><br>

        <!-- Botones -->
        <div style="text-align: center;">
            <button type="submit" class="btn-agregar">Guardar Notas</button>
            <a href="ejercicio7.php" class="btn-modificar">Volver</a>
        </div>
    </form>

</body>
</html>
