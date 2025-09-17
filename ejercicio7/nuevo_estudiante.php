<?php
session_start();

// Si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
// Importamos la conexión a la base de datos
require 'conexion.php';

// Obtenemos las carreras disponibles para mostrar en el select
$carreras = $conn->query("SELECT id, nombre FROM carreras");

// Verificamos si el formulario fue enviado vía POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibimos y limpiamos los datos enviados por el formulario
    $nombre = trim($_POST['nombre']);      // Nombre del estudiante
    $edad = intval($_POST['edad']);         // Edad, convertimos a entero
    $carrera_id = intval($_POST['carrera_id']);  // ID de la carrera seleccionada

    // Validaciones simples para evitar campos vacíos o inválidos
    if ($nombre === "") {
        $error = "Por favor ingrese un nombre válido.";
    } elseif ($edad <= 0) {
        $error = "Por favor ingrese una edad válida.";
    } elseif ($carrera_id <= 0) {
        $error = "Por favor seleccione una carrera válida.";
    } else {
        // Preparar consulta para insertar el nuevo estudiante en la tabla alumnos
        $stmt = $conn->prepare("INSERT INTO alumnos (nombre, edad, carrera_id) VALUES (?, ?, ?)");

        // Vinculamos los parámetros: nombre (string), edad (int), carrera_id (int)
        $stmt->bind_param("sii", $nombre, $edad, $carrera_id);

        // Ejecutamos la consulta y verificamos el resultado
        if ($stmt->execute()) {
            // Si se inserta correctamente, redirigimos a la página principal
            header("Location: ejercicio7.php");
            exit;
        } else {
            // Si ocurre algún error, lo guardamos para mostrarlo al usuario
            $error = "Error al guardar el estudiante: " . $stmt->error;
        }

        // Cerramos la sentencia preparada
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Estudiante</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Archivo CSS externo -->
</head>
<body>

    <h2 class="titulo-centrado">Agregar Nuevo Estudiante</h2>

    <!-- Mostrar mensaje de error si existe -->
    <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- Formulario para agregar estudiante -->
    <form method="POST" style="max-width: 600px; margin: 0 auto; 
                              background-color: #fff; padding: 30px; border-radius: 8px; 
                              box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

        <!-- Campo para ingresar el nombre -->
        <div style="margin-bottom: 15px;">
            <label for="nombre">Nombre:</label><br>
            <input 
                type="text" 
                name="nombre" 
                required 
                style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;"
                autofocus
            >
        </div>

        <!-- Campo para ingresar la edad -->
        <div style="margin-bottom: 15px;">
            <label for="edad">Edad:</label><br>
            <input 
                type="number" 
                name="edad" 
                required 
                min="1" 
                style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;"
            >
        </div>

        <!-- Select para elegir la carrera -->
        <div style="margin-bottom: 15px;">
            <label for="carrera_id">Carrera:</label><br>
            <select 
                name="carrera_id" 
                required 
                style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;"
            >
                <option value="">Seleccione una carrera</option>
                <?php while ($row = $carreras->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo htmlspecialchars($row['nombre']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Botones para enviar formulario o volver -->
        <div class="contenedor-botones" style="margin-top: 20px; display: flex; gap: 10px;">
            <button 
                type="submit" 
                class="btn-agregar" 
                style="flex: 1; padding: 12px; border-radius: 5px; border: none; background-color: #4CAF50; color: white; font-weight: bold;"
            >
                Guardar
            </button>
            <a 
                href="ejercicio7.php" 
                class="btn-modificar" 
                style="flex: 1; padding: 12px; border-radius: 5px; border: 1px solid #4CAF50; 
                       color: #4CAF50; text-align: center; text-decoration: none; font-weight: bold; line-height: 24px;"
            >
                Volver
            </a>
        </div>

    </form>

</body>
</html>
