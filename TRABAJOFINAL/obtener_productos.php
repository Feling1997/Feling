<?php
$host = "localhost";
$user = "root";
$pass = "aestudiar";
$db_name = "gestion_productos";

$conexion = new mysqli($host, $user, $pass, $db_name);

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $conexion->connect_error]);
    exit();
}

header('Content-Type: application/json');

$sql = "SELECT id, nombre, categoria, precio, stock FROM productos";
$resultado = $conexion->query($sql);

$productos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = [
            "id" => (int)$fila["id"],
            "nombre" => $fila["nombre"],
            "categoria" => $fila["categoria"],
            "precio" => (float)$fila["precio"],
            "stock" => (int)$fila["stock"],
            "valortotal" => (float)$fila["precio"]*(int)$fila["stock"]
        ];
    }
}

$conexion->close();

echo json_encode($productos);
?>
