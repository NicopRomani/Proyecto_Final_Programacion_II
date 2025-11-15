<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

require __DIR__ . '/../includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM vehiculos WHERE id=?");
    $stmt->execute([$id]);
    header("Location: ../index.php?msg=Vehiculo eliminado");
} else {
    header("Location: ../index.php");
}
exit;
