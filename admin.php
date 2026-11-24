<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

require_once '/var/www/config.php';
$conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Lógica para eliminar registro si se envía el ID
if (isset($_GET['eliminar'])) {
    $id_eliminar = intval($_GET['eliminar']);
    $stmt = $conexion->prepare("DELETE FROM solicitudes WHERE id = ?");
    $stmt->bind_param("i", $id_eliminar);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php");
    exit;
}

$resultado = $conexion->query("SELECT id, nombre, telefono, servicio, mensaje, fecha_registro FROM solicitudes ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes - Taller Automotriz</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1e1e2f; color: #fff; padding: 30px; margin: 0; }
        .top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        a.salir { background: #e63946; color: #fff; padding: 8px 14px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: #2a2a40; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #3a3a55; }
        th { background: #161625; color: #f4a261; }
        tr:hover { background: #32324d; }
        .btn-editar { background: #2a9d8f; color: white; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 13px; margin-right: 5px; }
        .btn-eliminar { background: #e63946; color: white; padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-editar:hover { background: #21867a; }
        .btn-eliminar:hover { background: #c5283d; }
    </style>
</head>
<body>
    <div class="top">
        <h2>Solicitudes del Taller (Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?>)</h2>
        <a class="salir" href="logout.php">Cerrar sesión</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Servicio</th>
                <th>Mensaje</th>
                <th>Fecha de Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= $fila['id'] ?></td>
                <td><?= htmlspecialchars($fila['nombre']) ?></td>
                <td><?= htmlspecialchars($fila['telefono']) ?></td>
                <td><?= htmlspecialchars($fila['servicio']) ?></td>
                <td><?= htmlspecialchars($fila['mensaje']) ?></td>
                <td><?= $fila['fecha_registro'] ?></td>
                <td>
                    <a class="btn-editar" href="editar.php?id=<?= $fila['id'] ?>">Editar</a>
                    <a class="btn-eliminar" href="admin.php?eliminar=<?= $fila['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar esta solicitud?');">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
