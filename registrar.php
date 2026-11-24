<?php
header('Content-Type: application/json');

$conexion = new mysqli("localhost", "fernando", "12345678", "taller_automotriz");

if ($conexion->connect_error) {
    echo json_encode(["status" => "error", "message" => "Error de conexión: " . $conexion->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre   = $_POST['nombre'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $servicio = $_POST['servicio'] ?? '';
    $mensaje  = $_POST['mensaje'] ?? '';

    $stmt = $conexion->prepare("INSERT INTO solicitudes (nombre, telefono, servicio, mensaje) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $telefono, $servicio, $mensaje);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
}

$conexion->close();
?>