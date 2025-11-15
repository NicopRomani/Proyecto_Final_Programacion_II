<?php
require __DIR__ . '/../includes/db.php';

$rol = 2;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    $estado = 1;
    $mensaje = '';

    if ($usuario && $password) {
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO usuarios(usuario,password,rol,estado) VALUES(?,?,?,?)");
            $stmt->execute([$usuario, $hash, $rol, $estado]);

            $mensaje = "El usuario **" . htmlspecialchars($usuario) . "** ha sido registrado con exito";
            header("Location: ../index.php?msg=" . urlencode($mensaje));
            exit;
        } catch (PDOException $e) {
            $mensaje_error = "Error al registrar el usuario ";
            header("Location: ../login.php?error=" . urlencode($mensaje_error));
            exit;
        }
    } else {
        $mensaje_error = "Por favor complete todos los campos.";
        header("Location: registrar_usuario.php?error=" . urlencode($mensaje_error));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h2>Registrar usuario</h2>
            <?php
            if (isset($_GET['error'])): ?>
                <div class="alert error"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>
            <form method="post" class="form form-centered">
                <input type="text" name="usuario" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" class="btn primary">Guardar</button>
                <a href="../index.php" class="btn link">Volver</a>
            </form>
        </div>
    </div>
</body>

</html>