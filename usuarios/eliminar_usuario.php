<?php
session_start();
require '../includes/db.php';

$mensaje = '';
$clase_alerta = 'success';

// Solo admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] != 1) {
    header('Location: ../login.php');
    exit;
}

// Tomar ID por GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // 1) Verificar si el usuario tiene vehiculos asociados
        $stmt = $conn->prepare("SELECT COUNT(*) FROM vehiculos WHERE vendedor_id = ?");
        $stmt->execute([$id]);
        $tieneVehiculos = (int) $stmt->fetchColumn();

        if ($tieneVehiculos > 0) {
            // 2) Tiene vehiculos -> solo lo marco como inactivo
            $stmt = $conn->prepare("UPDATE usuarios SET estado = 0 WHERE id = ?");
            $stmt->execute([$id]);

            $mensaje = "El usuario tiene vehiculos asociados, por lo que no se puede eliminar. Se marco como inactivo.";
        } else {
            // 3) No tiene vehiculos -> se puede eliminar fisicamente
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                $mensaje = "Usuario eliminado correctamente.";
            } else {
                $mensaje = "No se encontro el usuario.";
                $clase_alerta = 'error';
            }
        }
    } catch (PDOException $e) {
        $mensaje = "Error al eliminar: " . $e->getMessage();
        $clase_alerta = 'error';
    }
} else {
    $mensaje = "ID de usuario invalido.";
    $clase_alerta = 'error';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proceso</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container resultado">
    <div class="card card-center text-center">
        <h2>Proceso:</h2>
        <p class="alert <?= htmlspecialchars($clase_alerta) ?>">
            <?= htmlspecialchars($mensaje) ?>
        </p>
        <a href="hashboard_administrador.php" class="btn primary">Volver al panel principal</a>
    </div>
</div>
</body>
</html>
