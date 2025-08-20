<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Estudiante</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2class="titulo-centrado">Agregar Nuevo Estudiante</h2>
    <form action="guardar_estudiante.php" method="post" class="formulario-estudiante">
        <label>Nombre: <input type="text" name="nombre" required></label><br><br>
        <label>Edad: <input type="number" name="edad" required></label><br><br>
        <label>Carrera: <input type="text" name="carrera" required></label><br><br>
        <label>Notas (separadas por comas): <input type="text" name="notas" required></label><br><br>
        <button type="submit" class="btn-agregar">Guardar</button>
        <a href="ejercicio7.php" class="btn-agregar" style="backgrund:#aaa;">Cancelar</a>
    </form>
</body>
</html>