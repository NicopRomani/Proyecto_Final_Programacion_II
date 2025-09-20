<?php
require __DIR__ . '/../includes/db.php';
$id = $_GET['id'] ?? null;
if (!$id) {
  header("Location: ../index.php");
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $marca = $_POST['marca'] ?? '';
  $modelo = $_POST['modelo'] ?? '';
  $anio = $_POST['anio'] ?? '';
  $dominio = $_POST['dominio'] ?: null;
  $chasis = $_POST['chasis'] ?? '';
  $descripcion = $_POST['descripcion'] ?? '';
  $stmt = $conn->prepare("UPDATE vehiculos SET marca=?,modelo=?,anio=?,dominio=?,chasis=?,descripcion=? WHERE id=?");
  $stmt->execute([$marca, $modelo, $anio, $dominio, $chasis, $descripcion, $id]);
  header("Location: ../index.php");
  exit;
}
$stmt = $conn->prepare("SELECT * FROM vehiculos WHERE id=?");
$stmt->execute([$id]);
$v = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Editar vehículo</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <div class="container">
    <div class="card">
      <h2>Editar vehículo</h2>
      <form method="post" class="form form-centered">
        <input type="text" name="marca" value="<?= htmlspecialchars($v['marca']) ?>" required>
        <input type="text" name="modelo" value="<?= htmlspecialchars($v['modelo']) ?>" required>
        <input type="text" name="anio" value="<?= htmlspecialchars($v['anio']) ?>" required>
        <input type="text" name="dominio" value="<?= htmlspecialchars($v['dominio'] ?? '') ?>" placeholder="Dominio">
        <input type="text" name="chasis" value="<?= htmlspecialchars($v['chasis']) ?>" required>
        <textarea name="descripcion"><?= htmlspecialchars($v['descripcion'] ?? '') ?></textarea>
        <button type="submit" class="btn primary">Guardar</button>
        <a href="../index.php" class="btn link">Volver</a>
      </form>
    </div>
  </div>
</body>

</html>