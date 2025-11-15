<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../includes/db.php';

if ($_SESSION['usuario_rol'] != 1) {
    header("Location: hashboard_administrador.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: hashboard_administrador.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $estado = $_POST['estado'] ?? 1;
    $estado = (int)$estado;

    if ($password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE usuarios SET usuario=?, password=?, estado=? WHERE id=?");
        $stmt->execute([$usuario, $hash, $estado, $id]);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET usuario=?, estado=? WHERE id=?");
        $stmt->execute([$usuario, $estado, $id]);
    }
    header("Location: hashboard_administrador.php?msg=Usuario editado");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id=?");
$stmt->execute([$id]);
$u = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$u) {
    header("Location: hashboard_administrador.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <h2>Editar usuario</h2>
            <form method="post" class="form form-centered">
                <input type="text" name="usuario" value="<?= htmlspecialchars($u['usuario']) ?>" required>
                <input type="password" name="password" placeholder="Nueva contraseña (opcional)">
                <select name="estado">
                    <option value="1" <?= $u['estado'] == 1 ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $u['estado'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                </select>
                <button type="submit" class="btn primary">Guardar</button>
                <a href="../index.php" class="btn link">Volver</a>
            </form>
        </div>
    </div>
</body>

</html>