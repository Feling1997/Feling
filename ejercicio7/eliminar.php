<?php
require 'conexion.php';
require 'funciones.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $resultado = eliminarEstudiante($conn, $id);

    if ($resultado === true) {
        header("Location: ejercicio7.php");
        exit;
    } else {
        echo "Error al eliminar: " . $resultado;
    }
} else {
    echo "ID no especificado.";
}
?>
