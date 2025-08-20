<?php

$archivo='ejercicio7alumnos.php';
// Obtiene los datos del formulario
$nombre = $_POST['nombre'];
$edad = $_POST['edad'];
$carrera = $_POST['carrera'];
// Convierte las notas de texto a un array de números
$notas = array_map('floatval', explode(',', $_POST['notas']));

include $archivo;

$estudiantes[] = array(
    'nombre' => $nombre,
    'edad' => $edad,
    'carrera' => $carrera,
    'nota' => $notas
);



$contenido = "<?php\n\$estudiantes = " . var_export($estudiantes, true) . ";\n?>";

file_put_contents($archivo, $contenido);

header('Location: ejercicio7.php');
exit;
?>