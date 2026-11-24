<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '/var/www/config.php';
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$id = intval($_GET['id'] ?? 0);

// Si se envió el formulario guardado, actualiza la base de datos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_post = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $servicio = trim($_POST['servicio']);
    $mensaje = trim($_POST['mensaje']);

    $stmt = $conexion->prepare("UPDATE solicitudes SET nombre = ?, telefono = ?, servicio = ?, mensaje = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $telefono, $servicio, $mensaje, $id_post);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit;
}

// Obtener los datos actuales para mostrarlos en las cajas de texto
$stmt = $conexion->prepare("SELECT * FROM solicitudes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();

if (!$fila) {
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Solicitud</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1e1e2f; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .caja { background: #2a2a40; padding: 30px; border-radius: 8px; width: 350px; box-shadow: 0 4px 12px rgba(0,0,0,0.4); }
        label { display: block; margin-top: 10px; font-size: 14px; color: #f4a261; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; border: none; border-radius: 4px; box-sizing: border-box; }
        .botones { display: flex; justify-content: space-between; margin-top: 20px; }
        button { padding: 10px 15px; background: #2a9d8f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        a.cancelar { padding: 10px 15px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="caja">
        <h2>Editar Solicitud #<?= $fila['id'] ?></h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $fila['id'] ?>">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($fila['nombre']) ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($fila['telefono']) ?>" required>

            <label>Servicio:</label>
            <input type="text" name="servicio" value="<?= htmlspecialchars($fila['servicio']) ?>" required>

            <label>Mensaje:</label>
            <textarea name="mensaje" rows="3"><?= htmlspecialchars($fila['mensaje']) ?></textarea>

            <div class="botones">
                <a class="cancelar" href="admin.php">Cancelar</a>
                <button type="submit">Guardar cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
