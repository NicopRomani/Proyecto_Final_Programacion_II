<?php
session_start();
require '../includes/db.php';

// Si no está logueado, redirige
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca       = $_POST['marca']   ?? null;
    $modelo      = $_POST['modelo']  ?? null;
    $anio        = $_POST['anio']    ?? null;
    $dominio     = !empty($_POST['dominio'])     ? $_POST['dominio']     : null;
    $chasis      = !empty($_POST['chasis'])      ? $_POST['chasis']      : null;
    $descripcion = !empty($_POST['descripcion']) ? $_POST['descripcion'] : null;

    $vendedor_id = $_SESSION['usuario_id'];

    try {
        // ✅ Validación: verificar si el dominio ya existe
        if ($dominio !== null) {
            $check = $conn->prepare("SELECT COUNT(*) FROM vehiculos WHERE dominio = :dominio");
            $check->execute([':dominio' => $dominio]);
            $existe = $check->fetchColumn();

            if ($existe > 0) {
                echo "<script>alert('⚠️ Ya existe un vehículo con el dominio $dominio'); window.history.back();</script>";
                exit;
            }
        }

        // 🚀 Insertar vehículo
        $sql = "INSERT INTO vehiculos (marca, modelo, anio, dominio, chasis, descripcion, vendedor_id)
                VALUES (:marca, :modelo, :anio, :dominio, :chasis, :descripcion, :vendedor_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':marca'       => $marca,
            ':modelo'      => $modelo,
            ':anio'        => $anio,
            ':dominio'     => $dominio,
            ':chasis'      => $chasis,
            ':descripcion' => $descripcion,
            ':vendedor_id' => $vendedor_id
        ]);

        header("Location: ../index.php");
        exit;

    } catch (PDOException $e) {
        echo "Error al registrar vehículo: " . $e->getMessage();
    }
}

 


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar vehículo</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container">
  <div class="card">
    <h2>Registrar vehículo</h2>

    <!-- Igual que anoche: campos apilados y centrados -->
    <form method="POST" class="form form-centered">
      <input type="text"   name="marca"   placeholder="Marca" required>
      <input type="text"   name="modelo"  placeholder="Modelo" required>
      <input type="number" name="anio"    placeholder="Año" required>
      <input type="text"   name="dominio" placeholder="Dominio (opcional)">
      <input type="text"   name="chasis"  placeholder="Ingresar los ultimos 7 digitos">
      <textarea name="descripcion" placeholder="Descripción (opcional)"></textarea>

      <button type="submit" class="btn primary">Guardar</button>
      <a href="../index.php" class="btn link">Volver</a>
    </form>
  </div>
</div>
</body>
</html>
