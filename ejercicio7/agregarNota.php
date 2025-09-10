<?php
// Incluye el archivo que contiene la conexión a la base de datos
require 'conexion.php';

// Verifica si se pasó un ID de estudiante por GET (en la URL)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de estudiante no proporcionado.");
}

$alumno_id = intval($_GET['id']);
$mensaje = "";

// Obtener el nombre del alumno y su carrera_id en una sola consulta para optimizar
$stmt = $conn->prepare("SELECT nombre, carrera_id FROM alumnos WHERE id = ?");
$stmt->bind_param("i", $alumno_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    die("Estudiante no encontrado.");
}

$alumno = $resultado->fetch_assoc();
$stmt->close();

$carrera_id = $alumno['carrera_id'];  // Aquí está el id de la carrera del alumno

// Obtener materias que correspondan a la carrera del alumno
$stmtMaterias = $conn->prepare("SELECT id, nombre FROM materias WHERE carrera_id = ?");
$stmtMaterias->bind_param("i", $carrera_id);
$stmtMaterias->execute();
$materias = $stmtMaterias->get_result();

// Procesar formulario POST para guardar notas
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $materia_id = intval($_POST['materia_id']);
    $notas = $_POST['notas'];

    $stmt = $conn->prepare("INSERT INTO notas (alumno_id, materia_id, nota) VALUES (?, ?, ?)");

    foreach ($notas as $nota) {
        if ($nota !== "") {
            $nota_float = floatval($nota);
            $stmt->bind_param("iid", $alumno_id, $materia_id, $nota_float);
            $stmt->execute();
        }
    }
    $stmt->close();

    header("Location: ejercicio7.php");
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

    <form method="POST" style="max-width: 500px; margin: auto; padding: 20px; background-color: #fff; border-radius: 8px;">

        <label for="materia_id">Materia:</label>
        <select name="materia_id" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
            <option value="">Seleccione una materia</option>
            <?php while ($row = $materias->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo htmlspecialchars($row['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Notas:</label><br>
        <input type="number" name="notas[]" step="0.01" placeholder="Nota 1" style="width: 30%; padding: 8px;"><br><br>
        <input type="number" name="notas[]" step="0.01" placeholder="Nota 2 (opcional)" style="width: 30%; padding: 8px;"><br><br>
        <input type="number" name="notas[]" step="0.01" placeholder="Nota 3 (opcional)" style="width: 30%; padding: 8px;"><br><br>

        <div style="text-align: center;">
            <button type="submit" class="btn-agregar">Guardar Notas</button>
            <a href="ejercicio7.php" class="btn-modificar">Volver</a>
        </div>
    </form>

</body>
</html>
