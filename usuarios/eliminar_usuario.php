<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['usuario_rol'] != 1) {
    header("Location: ../login.php?error=No puedes eliminar usuarios");
    exit;
}
require __DIR__ . '/../includes/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    if ($id == $_SESSION['usuario_id']) {
        header("Location: hashboard_administrador.php?msg=No puedes eliminarte a ti mismo");
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
    try {
        $stmt->execute([$id]);
        header("Location: hashboard_administrador.php?msg=Usuario eliminado");
    } catch (PDOException $e) {
        header("Location: hashboard_administrador.php?msg=Error al eliminar: " . $e->getMessage());
        die("No se pudo eliminar el usuario: " . $e->getMessage());
    }
} else {
    header("Location: hashboard_administrador.php");
}
exit;
