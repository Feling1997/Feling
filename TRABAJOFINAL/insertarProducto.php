<?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "aestudiar", "gestion_productos");

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Error de conexión a la base de datos"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$nombre = trim($data["nombre"] ?? '');
$categoria = trim($data["categoria"] ?? '');
$precio = floatval($data["precio"] ?? 0);
$stock = intval($data["stock"] ?? 0);

if ($nombre === '' || $categoria === '' || $precio <= 0 || $stock < 0) {
    echo json_encode(["success" => false, "error" => "Datos inválidos"]);
    exit();
}

// Validar si ya existe un producto con ese nombre (insensible a mayúsculas)
$sqlCheck = "SELECT COUNT(*) as count FROM productos WHERE LOWER(nombre) = LOWER(?)";
$stmtCheck = $conexion->prepare($sqlCheck);
$stmtCheck->bind_param("s", $nombre);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$row = $resultCheck->fetch_assoc();

if ($row['count'] > 0) {
    echo json_encode(["success" => false, "error" => "Ya existe un producto con ese nombre."]);
    $stmtCheck->close();
    $conexion->close();
    exit();
}
$stmtCheck->close();

// Insertar nuevo producto
$sql = "INSERT INTO productos (nombre, categoria, precio, stock) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdi", $nombre, $categoria, $precio, $stock);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id_insertado" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
