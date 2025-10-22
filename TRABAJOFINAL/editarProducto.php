<?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "root", "aestudiar", "gestion_productos");

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Error de conexión"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

$id = intval($data["id"] ?? 0);
$nombre = trim($data["nombre"] ?? '');
$categoria = trim($data["categoria"] ?? '');
$precio = floatval($data["precio"] ?? 0);
$stock = intval($data["stock"] ?? 0);

if (
    $id <= 0 || $nombre === '' || $categoria === '' ||
    $precio <= 0 || $stock < 0
) {
    echo json_encode(["success" => false, "error" => "Datos inválidos para actualización"]);
    exit();
}

// Validar si ya existe otro producto con ese nombre distinto al actual
$sqlCheck = "SELECT COUNT(*) as count FROM productos WHERE LOWER(nombre) = LOWER(?) AND id <> ?";
$stmtCheck = $conexion->prepare($sqlCheck);
$stmtCheck->bind_param("si", $nombre, $id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
$row = $resultCheck->fetch_assoc();

if ($row['count'] > 0) {
    echo json_encode(["success" => false, "error" => "Ya existe otro producto con ese nombre."]);
    $stmtCheck->close();
    $conexion->close();
    exit();
}
$stmtCheck->close();

$sql = "UPDATE productos SET nombre = ?, categoria = ?, precio = ?, stock = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssddi", $nombre, $categoria, $precio, $stock, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
