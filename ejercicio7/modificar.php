<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
// modificar.php - formulario y lógica para modificar un estudiante y sus notas

require 'conexion.php';
require 'funciones.php';

// Validar que recibimos el ID por GET para saber qué estudiante modificar
// ✅ Esta versión permite recibir el ID tanto por GET (al cargar) como por POST (al guardar)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = (int)$_POST['id'];
    } else {
        die("ID de estudiante no válido en POST.");
    }
} elseif (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    die("ID de estudiante no válido.");
}

// Consultamos el estudiante y sus datos básicos (nombre, edad, carrera)
$stmt = $conn->prepare("SELECT nombre, edad, carrera_id FROM alumnos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Estudiante no encontrado.");
}

$estudiante = $result->fetch_assoc();
$stmt->close();

// Consultar las notas del estudiante por materia
$stmt = $conn->prepare("SELECT materia_id, nota FROM notas WHERE alumno_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$notas = []; // notas agrupadas por materia_id
while ($fila = $result->fetch_assoc()) {
    $materia_id = $fila['materia_id'];
    if (!isset($notas[$materia_id])) {
        $notas[$materia_id] = [];
    }
    $notas[$materia_id][] = $fila['nota'];
}
$stmt->close();

// Obtener todas las carreras y materias para el formulario
$carreras = $conn->query("SELECT id, nombre FROM carreras");
$stmtMaterias = $conn->prepare("SELECT id, nombre FROM materias WHERE carrera_id = ?");
$stmtMaterias->bind_param("i", $estudiante['carrera_id']);
$stmtMaterias->execute();
$materias = $stmtMaterias->get_result();


$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $edad = (int)$_POST['edad'];
    $carrera_id = (int)$_POST['carrera_id'];
    $notas_post = $_POST['notas'];

    // Validaciones básicas
    if ($nombre === '' || $edad <= 0 || $carrera_id <= 0) {
        $error = "Por favor complete todos los campos correctamente.";
    } else {
        // Validar notas
        foreach ($notas_post as $mat_id => $notasMateria) {
            foreach ($notasMateria as $nota) {
                if (!is_numeric($nota) || $nota < 0 || $nota > 10) {
                    $error = "Las notas deben ser números entre 0 y 10.";
                    break 2;
                }
            }
        }

        if (!$error) {
            // Actualizar datos básicos del estudiante
            $resultado = modificarEstudiante($conn, $id, $nombre, $edad, $carrera_id);

            if ($resultado === true) {
                // Eliminar notas previas para insertar las nuevas
                $stmt = $conn->prepare("DELETE FROM notas WHERE alumno_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();

                // Insertar las nuevas notas
                $stmt = $conn->prepare("INSERT INTO notas (alumno_id, materia_id, nota) VALUES (?, ?, ?)");
                foreach ($notas_post as $materia_id => $notasMateria) {
                    foreach ($notasMateria as $nota) {
                        if ($nota !== '') { // Insertar solo si hay nota
                            $notaFloat = (float)$nota;
                            $stmt->bind_param("iid", $id, $materia_id, $notaFloat);
                            if (!$stmt->execute()) {
                                $error = "Error al insertar nota: " . $stmt->error;
                                break 2;
                            }
                        }
                    }
                }
                $stmt->close();

                if (!$error) {
                    // Redirigir a la lista de estudiantes al modificar con éxito
                    header("Location: /Feling/ejercicio7/ejercicio7.php");
                    exit;
                }
            } else {
                $error = $resultado;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Estudiante</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<h2 class="titulo-centrado">Modificar Estudiante</h2>

<?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" action="modificar.php?id=<?php echo $id; ?>" style="max-width: 600px; margin: auto;">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($estudiante['nombre']); ?>" required><br><br>

    <label>Edad:</label><br>
    <input type="number" name="edad" min="1" value="<?php echo $estudiante['edad']; ?>" required><br><br>

    <label>Carrera:</label><br>
    <select name="carrera_id" required>
        <option value="">Seleccione una carrera</option>
        <?php while ($fila = $carreras->fetch_assoc()): ?>
            <option value="<?php echo $fila['id']; ?>" <?php if ($fila['id'] == $estudiante['carrera_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($fila['nombre']); ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <h3>Notas por Materia:</h3>
    <?php
    // Recorrer materias para mostrar inputs de notas con valores existentes
    $materias->data_seek(0); // Reiniciar puntero para recorrer materias de nuevo
    while ($fila = $materias->fetch_assoc()):
        $mat_id = $fila['id'];
        $notasMateria = isset($notas[$mat_id]) ? $notas[$mat_id] : [];
    ?>
        <label><?php echo htmlspecialchars($fila['nombre']); ?>:</label><br>
        <input type="number" step="0.01" name="notas[<?php echo $mat_id; ?>][]" min="0" max="10" placeholder="Nota 1" 
            value="<?php echo isset($notasMateria[0]) ? htmlspecialchars($notasMateria[0]) : ''; ?>" required>
        <input type="number" step="0.01" name="notas[<?php echo $mat_id; ?>][]" min="0" max="10" placeholder="Nota 2" 
            value="<?php echo isset($notasMateria[1]) ? htmlspecialchars($notasMateria[1]) : ''; ?>">
        <input type="number" step="0.01" name="notas[<?php echo $mat_id; ?>][]" min="0" max="10" placeholder="Nota 3" 
            value="<?php echo isset($notasMateria[2]) ? htmlspecialchars($notasMateria[2]) : ''; ?>"><br><br>
    <?php endwhile; ?>

    <button type="submit" class="btn-modificar">Guardar Cambios</button>
    <a href="ejercicio7.php" class="btn-modificar">Cancelar</button>
</form>

</body>
</html>
