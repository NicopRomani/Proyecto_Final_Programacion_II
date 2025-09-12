<?php
// register_vehicle.php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $dominio = strtoupper(trim($_POST['dominio'] ?? '')); // opcional
    $anio = trim($_POST['anio'] ?? '');
    $chasis = trim($_POST['chasis'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $vendedor_id = $_SESSION['usuario_id'];

    // Validaciones mínimas
    if ($marca === '' || $modelo === '' || $anio === '' || $chasis === '') {
        $mensaje = "Completá los campos obligatorios: Marca, Modelo, Año y Chasis.";
    } elseif (!ctype_digit($anio) || (int)$anio < 1900 || (int)$anio > 2100) {
        $mensaje = "Año inválido.";
    } else {
        try {
            $sql = "INSERT INTO vehiculos (marca, modelo, dominio, anio, chasis, descripcion, vendedor_id)
                    VALUES (:marca, :modelo, :dominio, :anio, :chasis, :descripcion, :vendedor_id)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':marca' => $marca,
                ':modelo' => $modelo,
                ':dominio' => $dominio === '' ? null : $dominio,
                ':anio' => (int)$anio,
                ':chasis' => $chasis,
                ':descripcion' => $descripcion === '' ? null : $descripcion,
                ':vendedor_id' => $vendedor_id
            ]);
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $mensaje = "Error al registrar vehículo: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registrar Vehículo</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="form-container">
    <h2>Registrar Vehículo</h2>
    <?php if (!empty($mensaje)) { echo "<p class='error'>".$mensaje."</p>"; } ?>
    <form method="post">
        <input type="text" name="marca" placeholder="Marca *" required>
        <input type="text" name="modelo" placeholder="Modelo *" required>
        <input type="text" name="dominio" placeholder="Dominio (opcional)">
        <input type="number" name="anio" placeholder="Año *" required>
        <input type="text" name="chasis" placeholder="Chasis *" required>
        <textarea name="descripcion" placeholder="Descripción (opcional)" style="width:90%;height:90px;margin:10px auto;border-radius:5px;border:none;padding:10px;"></textarea>
        <button type="submit">Guardar</button>
    </form>
    <br>
    <a href="index.php" class="btn">Volver al panel</a>
</div>
</body>
</html>
