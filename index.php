<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$mensaje_exito = $_GET['msg'] ?? '';
$query_string = '';

if (!empty($mensaje_exito)) {
    $query_string = '?msg=' . urlencode($mensaje_exito);
}

$usuario_rol = $_SESSION['usuario_rol'] ?? 2;
if ($usuario_rol == 1) {
    header("Location: usuarios/hashboard_administrador.php" . $query_string);
    exit;
} else {
    header("Location: usuarios/hashboard_usuario.php" . $query_string);
    exit;
}
