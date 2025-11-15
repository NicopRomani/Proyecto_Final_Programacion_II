<?php
session_start();
require __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$mensaje = '';
$vendedor_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Datos del Vehículo
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $anio = trim($_POST['anio'] ?? '');
    $dominio = trim($_POST['dominio'] ?? '');
    $chasis = trim($_POST['chasis'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? ''); 

    // Datos del Cliente
    $cliente_nombre = trim($_POST['cliente_nombre'] ?? '');
    $cliente_apellido = trim($_POST['cliente_apellido'] ?? '');
    $cliente_dni = trim($_POST['cliente_dni'] ?? '');
    $cliente_tel = trim($_POST['cliente_tel'] ?? null);
    
    if ($marca === '' || $modelo === '' || $anio === '' || $dominio === '' || $cliente_dni === '') {
        $mensaje = "Completar los datos obligatorios";
    } else {
        
        try {
            $conn->beginTransaction();
            $cliente_id = null;

            $stmt_cliente = $conn->prepare("SELECT id FROM clientes WHERE dni = ?");
            $stmt_cliente->execute([$cliente_dni]);
            $cliente_existente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);

            if ($cliente_existente) {
                $cliente_id = $cliente_existente['id'];
                $stmt_update = $conn->prepare("
                    UPDATE clientes 
                    SET nombre = ?, apellido = ?, tel = ? 
                    WHERE id = ?
                ");
                $stmt_update->execute([$cliente_nombre, $cliente_apellido, $cliente_tel, $cliente_id]);
                
            } else {
                
                $stmt_insert = $conn->prepare("
                    INSERT INTO clientes (nombre, apellido, dni, tel) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt_insert->execute([$cliente_nombre, $cliente_apellido, $cliente_dni, $cliente_tel]);
                $cliente_id = $conn->lastInsertId(); 
            }

            
            $stmt_vehiculo = $conn->prepare("
                INSERT INTO vehiculos (marca, modelo, anio, dominio, chasis, descripcion, vendedor_id, cliente_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt_vehiculo->execute([
                $marca, 
                $modelo, 
                $anio, 
                $dominio, 
                $chasis, 
                $descripcion, 
                $vendedor_id, 
                $cliente_id
            ]);

            $conn->commit();
            header("Location: ../index.php?msg=" . urlencode("Vehiculo registrado"));
            exit;

        } catch (PDOException $e) {
            $conn->rollBack();
            $mensaje = "Error al registrar: " . $e->getMessage();
        } catch (Exception $e) {
            $conn->rollBack();
            $mensaje = "Error inesperado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar vehiculo</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-group-inline { display: flex; gap: 10px; margin-bottom: 10px; }
        .form-half { flex: 1; }
        .form-half input, .form-half textarea { width: 100%; box-sizing: border-box; }
        .form-centered { max-width: 600px; margin: 0 auto; }
        .form input:not([type="submit"]):not([type="button"]), .form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card form-centered">
            <h2>Registrar nuevo vehiculo</h2>
            <?php if ($mensaje): ?><div class="alert error"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
            
            <form method="post" class="form">
                
                <h3>Datos del vehiculo</h3>

                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="text" name="marca" placeholder="Marca" value="<?= htmlspecialchars($_POST['marca'] ?? '') ?>" required>
                    </div>
                    <div class="form-half">
                        <input type="text" name="modelo" placeholder="Modelo" value="<?= htmlspecialchars($_POST['modelo'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="number" name="anio" placeholder="Año" value="<?= htmlspecialchars($_POST['anio'] ?? '') ?>" required min="1900" max="<?= date('Y') + 1 ?>">
                    </div>
                    <div class="form-half">
                        <input type="text" name="dominio" placeholder="Dominio (Patente)" value="<?= htmlspecialchars($_POST['dominio'] ?? '') ?>" required>
                    </div>
                </div>

                <input type="text" name="chasis" placeholder="ultimos 7 nro del chasis" value="<?= htmlspecialchars($_POST['chasis'] ?? '') ?>">
                
                <textarea name="descripcion" placeholder="Observaciones" rows="4"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                <hr style="margin: 30px 0;">

                <h3>Datos del Cliente Asociado</h3>

                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="text" name="cliente_nombre" placeholder="Nombre cliente" value="<?= htmlspecialchars($_POST['cliente_nombre'] ?? '') ?>" required>
                    </div>
                    <div class="form-half">
                        <input type="text" name="cliente_apellido" placeholder="Apellido cliente" value="<?= htmlspecialchars($_POST['cliente_apellido'] ?? '') ?>" required>
                    </div>
                </div>
                
                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="text" name="cliente_dni" placeholder="DNI (obligatorio)" value="<?= htmlspecialchars($_POST['cliente_dni'] ?? '') ?>" required>
                    </div>
                    <div class="form-half">
                        <input type="text" name="cliente_tel" placeholder="Telefono" value="<?= htmlspecialchars($_POST['cliente_tel'] ?? '') ?>">
                    </div>
                </div>
                
                <button type="submit" class="btn primary">Registrar vehiculo</button>
                <a href="../index.php" class="btn link">Volver</a>
            </form>
        </div>
    </div>
</body>
</html>