<?php
session_start();
require __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$id_vehiculo = $_GET['id'] ?? null;
$mensaje = '';
$vehiculo_data = [];

if (!$id_vehiculo) {
    header("Location: ../index.php?msg=" . urlencode("vehiculo no especificado para editar"));
    exit;
}

$sql_select = "
    SELECT 
        v.*, 
        c.nombre AS cliente_nombre, c.apellido AS cliente_apellido, c.dni AS cliente_dni, c.tel AS cliente_tel, c.id AS cliente_id
    FROM vehiculos v 
    JOIN clientes c ON v.cliente_id = c.id
    WHERE v.id = ?
";
$stmt = $conn->prepare($sql_select);
$stmt->execute([$id_vehiculo]);
$vehiculo_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehiculo_data) {
    header("Location: ../index.php?msg=" . urlencode("Vehiculo no encontrado"));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // datos vehiculo
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $anio = trim($_POST['anio'] ?? '');
    $dominio = trim($_POST['dominio'] ?? '');
    $chasis = trim($_POST['chasis'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    // datos cliente 
    $cliente_nombre = trim($_POST['cliente_nombre'] ?? '');
    $cliente_apellido = trim($_POST['cliente_apellido'] ?? '');
    $cliente_dni = trim($_POST['cliente_dni'] ?? '');
    $cliente_tel = trim($_POST['cliente_tel'] ?? null);
    $cliente_id_actual = $vehiculo_data['cliente_id'];

    if ($marca === '' || $modelo === '' || $anio === '' || $dominio === '' || $cliente_dni === '') {
        $mensaje = "Completar campos obligatorios";
    } else {

        try {
            $conn->beginTransaction();
            $nuevo_cliente_id = $cliente_id_actual;
            $stmt_check = $conn->prepare("SELECT id FROM clientes WHERE dni = ? AND id != ?");
            $stmt_check->execute([$cliente_dni, $cliente_id_actual ?? 0]);
            $cliente_existente_otro = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($cliente_existente_otro) {
                $mensaje = "El DNI pertenece a otro cliente";
                $conn->rollBack();
                $stmt->execute([$id_vehiculo]);
                $vehiculo_data = $stmt->fetch(PDO::FETCH_ASSOC);
                goto end_post;
            } elseif ($cliente_id_actual) {
                $stmt_update_cliente = $conn->prepare("
                    UPDATE clientes 
                    SET nombre = ?, apellido = ?, dni = ?, tel = ? 
                    WHERE id = ?
                ");
                $stmt_update_cliente->execute([$cliente_nombre, $cliente_apellido, $cliente_dni, $cliente_tel, $cliente_id_actual]);
                $nuevo_cliente_id = $cliente_id_actual;
            } else {
                $stmt_insert_cliente = $conn->prepare("
                    INSERT INTO clientes (nombre, apellido, dni, tel) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt_insert_cliente->execute([$cliente_nombre, $cliente_apellido, $cliente_dni, $cliente_tel]);
                $nuevo_cliente_id = $conn->lastInsertId();
            }
            $sql_update_vehiculo = "
                UPDATE vehiculos 
                SET marca = ?, modelo = ?, anio = ?, dominio = ?, chasis = ?, descripcion = ?, cliente_id = ? 
                WHERE id = ?
            ";
            $stmt_update_vehiculo = $conn->prepare($sql_update_vehiculo);
            $stmt_update_vehiculo->execute([
                $marca,
                $modelo,
                $anio,
                $dominio,
                $chasis,
                $descripcion,
                $nuevo_cliente_id,
                $id_vehiculo
            ]);

            $conn->commit();

            header("Location: ../index.php?msg=" . urlencode("Actualizacion exitosa"));
            exit;
        } catch (PDOException $e) {
            $conn->rollBack();
            $mensaje = "Error al actualizar en BD" . $e->getMessage();
        } catch (Exception $e) {
            $conn->rollBack();
            $mensaje = "Error inesperado";
        }
    }
}
end_post:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mensaje != '') {
    $stmt->execute([$id_vehiculo]);
    $vehiculo_data = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Vehículo</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-group-inline {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .form-half {
            flex: 1;
            min-width: 0;
        }

        .form-half input,
        .form-half textarea,
        .form input:not([type="submit"]):not([type="button"]),
        .form textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-centered {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card form-centered">
            <h2>Editar vehiculo (ID: <?= $id_vehiculo ?>)</h2>
            <?php if ($mensaje): ?><div class="alert error"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

            <form method="post" class="form">

                <h3>Datos del vehiculo</h3>

                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="text" name="marca" placeholder="Marca" value="<?= htmlspecialchars($vehiculo_data['marca'] ?? '') ?>" required>
                    </div>
                    <div class="form-half">
                        <input type="text" name="modelo" placeholder="Modelo" value="<?= htmlspecialchars($vehiculo_data['modelo'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="number" name="anio" placeholder="Año" value="<?= htmlspecialchars($vehiculo_data['anio'] ?? '') ?>" required min="1900" max="<?= date('Y') + 1 ?>">
                    </div>
                    <div class="form-half">
                        <input type="text" name="dominio" placeholder="Dominio (Patente)" value="<?= htmlspecialchars($vehiculo_data['dominio'] ?? '') ?>" required>
                    </div>
                </div>

                <input type="text" name="chasis" placeholder="Numero de chasis (VIN)" value="<?= htmlspecialchars($vehiculo_data['chasis'] ?? '') ?>">

                <textarea name="descripcion" placeholder="Observaciones" rows="4"><?= htmlspecialchars($vehiculo_data['descripcion'] ?? '') ?></textarea>
                <hr style="margin: 30px 0;">

                <h3>Datos del cliente</h3>
                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="text" name="cliente_nombre" placeholder="Nombre cliente" value="<?= htmlspecialchars($vehiculo_data['cliente_nombre'] ?? '') ?>" required>
                    </div>
                    <div class="form-half">
                        <input type="text" name="cliente_apellido" placeholder="Apellido cliente" value="<?= htmlspecialchars($vehiculo_data['cliente_apellido'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group-inline">
                    <div class="form-half">
                        <input type="text" name="cliente_dni" placeholder="DNI (obligatorio)" value="<?= htmlspecialchars($vehiculo_data['cliente_dni'] ?? '') ?>" required>
                    </div>
                    <div class="form-half">
                        <input type="text" name="cliente_tel" placeholder="Telefono" value="<?= htmlspecialchars($vehiculo_data['cliente_tel'] ?? '') ?>">
                    </div>
                </div>

                <button type="submit" class="btn primary">Guardar cambios</button>
                <a href="../index.php" class="btn link">Cancelar / Volver</a>
            </form>
        </div>
    </div>
</body>

</html>