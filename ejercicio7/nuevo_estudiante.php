<?php
require 'conexion.php';
require 'funciones.php';

$carreras = $conn->query("SELECT id, nombre FROM carreras");
$materias = $conn->query("SELECT id, nombre FROM materias");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $carrera_id = $_POST['carrera_id'];
    $notas = $_POST['notas'];

    $resultado = agregarEstudiante($conn, $nombre, $edad, $carrera_id, $notas);

    if ($resultado === true) {
        header("Location: ejercicio7.php");
        exit;
    } else {
        $error = $resultado;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Estudiante</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <h2 class="titulo-centrado">Agregar Nuevo Estudiante</h2>

    <?php if (isset($error)): ?>
        <p style="color: red; text-align: center;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="margin-bottom: 15px;">
            <label for="nombre">Nombre:</label><br>
            <input type="text" name="nombre" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="edad">Edad:</label><br>
            <input type="number" name="edad" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="carrera_id">Carrera:</label><br>
            <select name="carrera_id" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="">Seleccione una carrera</option>
                <?php while ($row = $carreras->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <h4>Notas por materia:</h4>
            <?php
            // Volvemos a ejecutar la consulta porque ya se consumió arriba
            $materias = $conn->query("SELECT id, nombre FROM materias");
            while ($row = $materias->fetch_assoc()):
            ?>
                <div style="margin-bottom: 10px;">
                    <label><strong><?php echo $row['nombre']; ?>:</strong></label><br>
                    <input type="number" step="0.01" name="notas[<?php echo $row['id']; ?>][]" placeholder="Nota 1" required style="width: 30%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                    <input type="number" step="0.01" name="notas[<?php echo $row['id']; ?>][]" placeholder="Nota 2" style="width: 30%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                    <input type="number" step="0.01" name="notas[<?php echo $row['id']; ?>][]" placeholder="Nota 3" style="width: 30%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                </div>
            <?php endwhile; ?>
        </div>

        <div class="contenedor-botones" style="margin-top: 20px;">
            <button type="submit" class="btn-agregar">Guardar</button>
            <a href="ejercicio7.php" class="btn-modificar">Volver</a>
        </div>
    </form>

</body>
</html>
