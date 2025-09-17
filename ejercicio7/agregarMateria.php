<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
// Conexión a la base de datos
require 'conexion.php';
// Obtener las carreras disponibles para el select
$carreras = [];
$result = $conn->query("SELECT id, nombre FROM carreras");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $carreras[] = $row;
    }
}

// Variable para mensajes de error
$error = "";

// Verificamos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreMateria = trim($_POST['nombre']);
    $carrera_id = $_POST['carrera_id'];

    if (empty($nombreMateria) || empty($carrera_id)) {
        $error = "Debes completar todos los campos.";
    } else {
        $stmt = $conn->prepare("INSERT INTO materias (nombre, carrera_id) VALUES (?, ?)");
        $stmt->bind_param("si", $nombreMateria, $carrera_id);

        if ($stmt->execute()) {
            header("Location: ejercicio7.php");
            exit;
        } else {
            $error = "Error al guardar la materia: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Materia</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Asegúrate de tener este archivo -->
</head>
<body>

    <h2 class="titulo-centrado">Agregar Nueva Materia</h2>

    <!-- Si hay error, lo mostramos -->
    <?php if (!empty($error)): ?>
        <p style="color: red; text-align: center;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <!-- Formulario -->
    <form method="POST" style="max-width: 400px; margin: 0 auto; padding: 30px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="margin-bottom: 15px;">
            <label for="nombre">Nombre de la Materia:</label><br>
            <input type="text" name="nombre" id="nombre" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="carrera_id">Carrera:</label><br>
            <select name="carrera_id" id="carrera_id" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="">-- Selecciona una carrera --</option>
                <?php foreach ($carreras as $carrera): ?>
                    <option value="<?php echo $carrera['id']; ?>"><?php echo htmlspecialchars($carrera['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="contenedor-botones" style="margin-top: 20px;">
        <button type="submit" class="btn-agregar">Guardar</button>
        <a href="ejercicio7.php" class="btn-modificar">Volver</a>
        </div>
    </form>


</body>
</html>
