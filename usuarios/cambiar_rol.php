<?php
session_start();
require __DIR__ . '/../includes/db.php';

// solo el admin puede acceder a esta info
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 1) {
    header("Location: ../index.php");
    exit;
}

$id_usuario_a_editar = $_GET['id'] ?? null;
$error = '';
$usuario_info = null;

if (!$id_usuario_a_editar) {
    $error = "ID de usuario no especificado.";
} else {
    // puedo recuperar los datos del usuario
    $stmt = $conn->prepare("SELECT id, usuario, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario_a_editar]);
    $usuario_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario_info) {
        $error = "Usuario no encontrado.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_info) {
    $nuevo_rol = $_POST['nuevo_rol'] ?? null;

    if ($nuevo_rol != 1 && $nuevo_rol != 2) {
        $error = "Rol no válido";
    } else {
        // el adm no se puede eliminar a si mismo
        if ($usuario_info['id'] == $_SESSION['usuario_id']) {
            $error = "No puedes cambiar tu propio rol en esta pantalla. Pedile a otro administrador que lo haga";
        } else {
            $stmt_update = $conn->prepare("UPDATE usuarios SET rol = :rol WHERE id = :id");
            $stmt_update->execute([
                ':rol' => $nuevo_rol,
                ':id' => $usuario_info['id']
            ]);

            $rol_texto = $nuevo_rol == 1 ? 'ADMINISTRADOR' : 'EMPLEADO';
            header("Location: ../index.php?msg=Rol de " . htmlspecialchars($usuario_info['usuario']) . " actualizado a " . $rol_texto . ".");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cambiar rol de usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <div class="card card-center text-center">
            <h2>Cambiar rol de usuario</h2>

            <?php if ($error): ?>
                <div style="color: white; padding: 10px; border-radius: 4px; background-color: #c0392b; margin-bottom: 20px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($usuario_info && !$error): ?>
                <p>Usuario: <strong><?= htmlspecialchars($usuario_info['usuario']) ?></strong></p>
                <p>Rol actual:
                    <strong><?= $usuario_info['rol'] == 1 ? 'ADMINISTRADOR (1)' : 'EMPLEADO (2)' ?></strong>
                </p>

                <form method="POST" class="form form-centered">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="nuevo_rol">Designar nuevo rol:</label>
                        <select name="nuevo_rol" id="nuevo_rol" class="form-control" required>
                            <option value="2" <?= $usuario_info['rol'] == 2 ? 'selected' : '' ?>>2 - Empleado</option>
                            <option value="1" <?= $usuario_info['rol'] == 1 ? 'selected' : '' ?>>1 - Administrador</option>
                        </select>
                    </div>

                    <button type="submit" class="btn primary">Guardar Rol</button>
                    <a href="../index.php" class="btn secondary">Cancelar</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>