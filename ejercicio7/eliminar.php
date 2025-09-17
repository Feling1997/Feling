<?php
require 'conexion.php';
require 'funciones.php';

// Verifica que llegó por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $accion = $_POST['accion'];

    if ($accion === 'eliminar_notas') {
        // Eliminar solo las notas del alumno
        $stmt = $conn->prepare("DELETE FROM notas WHERE alumno_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: ejercicio7.php?mensaje=notas_eliminadas");
            exit;
        } else {
            echo "Error al eliminar notas.";
        }
    } elseif ($accion === 'eliminar_alumno') {
        // Eliminar alumno y (por clave foránea ON DELETE CASCADE) sus notas
        $resultado = eliminarEstudiante($conn, $id);
        if ($resultado === true) {
            header("Location: ejercicio7.php?mensaje=alumno_eliminado");
            exit;
        } else {
            echo "Error al eliminar alumno: " . $resultado;
        }
    } else {
        echo "Acción no válida.";
    }
} else {
    echo "Acceso inválido.";
}
?>
