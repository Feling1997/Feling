<?php
$conexion = new mysqli("localhost", "root", "aestudiar", "gestion_productos");

if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Error de conexión"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"];

$sql = "DELETE FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

$response = [];

if ($stmt->execute()) {
    $response["success"] = true;
} else {
    $response["success"] = false;
}

$stmt->close();
$conexion->close();

echo json_encode($response);
?>
