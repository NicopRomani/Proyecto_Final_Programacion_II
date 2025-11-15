<?php
session_start();
require __DIR__ . '/includes/db.php';
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($usuario !== '' && $password !== '') {
        $stmt = $conn->prepare("SELECT id, usuario, password, rol, estado FROM usuarios WHERE usuario=?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['estado'] != 1) {
                $mensaje = "Tu cuenta esta inactiva. Contacta al administrador";
            } else {
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['usuario_rol'] = $user['rol'];

                if ($user['rol'] == 1) {
                    header("Location: usuarios/hashboard_administrador.php");
                    exit;
                } elseif ($user['rol'] == 2) {
                    header("Location: usuarios/hashboard_usuario.php");
                    exit;
                } else {
                    session_destroy();
                    $mensaje = "Rol de usuario no valido. Contacte al administrador";
                }
            }
        } else {
            $mensaje = "Usuario o contraseña incorrecta";
        }
    } else {
        $mensaje = "Completa usuario y contraseña";
    }
}

$mensaje_error_url = $_GET['error'] ?? null;
if ($mensaje_error_url) {
    $mensaje = $mensaje_error_url;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="login-left">
                <img src="img/autos.png" alt="Autos">
            </div>
            <div class="login-right">
                <img src="img/logo.jpg" alt="Logo">
                <h2>Bienvenidos</h2>
                <?php if ($mensaje): ?><div class="alert error"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
                <form method="post" class="form">
                    <input type="text" name="usuario" placeholder="Usuario" required>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <button type="submit" class="btn primary">Ingresar</button>
                </form>
                <a class="btn link" href="usuarios/registrar_usuario.php">Registrar nuevo usuario</a>
            </div>
        </div>
    </div>
</body>

</html>