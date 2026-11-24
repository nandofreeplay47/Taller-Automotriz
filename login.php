<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: admin.php");
    exit;
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once '/var/www/config.php';
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conexion->prepare("SELECT id, password FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        if (password_verify($password, $fila['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $usuario;
            header("Location: admin.php");
            exit;
        }
    }
    $error = "Usuario o contraseña incorrectos.";
    $stmt->close();
    $conexion->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Taller Automotriz</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1e1e2f; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .caja { background: #2a2a40; padding: 30px; border-radius: 8px; width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.4); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: none; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #e63946; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #d62828; }
        .error { color: #ff6b6b; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="caja">
        <h2>Panel del Taller</h2>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
