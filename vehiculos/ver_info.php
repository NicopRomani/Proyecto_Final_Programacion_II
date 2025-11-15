<?php
session_start();
require __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$id_vehiculo = $_GET['id'] ?? null;

if (!$id_vehiculo) {
    header("Location: ../index.php?msg=" . urlencode("vehiculo no especificado"));
    exit;
}

$motor_busqueda = "
    SELECT 
        v.marca, v.modelo, v.anio, v.dominio, v.chasis, v.descripcion,
        u.usuario AS vendedor, 
        c.nombre AS cliente_nombre, 
        c.apellido AS cliente_apellido, 
        c.dni AS cliente_dni, 
        c.tel AS cliente_tel
    FROM vehiculos v 
    JOIN usuarios u ON v.vendedor_id = u.id 
    JOIN clientes c ON v.cliente_id = c.id
    WHERE v.id = ?
";

$stmt = $conn->prepare($motor_busqueda);
$stmt->execute([$id_vehiculo]);
$info_vehiculo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$info_vehiculo) {
    header("Location: ../index.php?msg=" . urlencode("Vehiculo no encontrado"));
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informacion del vehiculo</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .container { max-width: 800px; }
        .card { padding: 40px; }
        .info-group { margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 15px; }
        .info-group:last-child { border-bottom: none; }
        .info-group h4 { color: #c0392b; margin-top: 0; }
        .info-row { 
            display: flex; 
            gap: 20px; 
            margin-bottom: 10px; 
            flex-wrap: wrap; 
        }
        .info-item {
            flex: 1; 
            min-width: 150px; 
        }

   
        .info-label { 
            font-weight: bold; 
            color: #555; 
            display: block;
            margin-bottom: 3px; 
            font-size: 0.9em;
        }
        .info-value { 
            background-color: #f7f7f7; 
            padding: 8px; 
            border-radius: 4px; 
            white-space: pre-wrap; 
            word-wrap: break-word; 
            display: block; 
            min-height: 35px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Informacion del vehiculo (ID: <?= $id_vehiculo ?>)</h2>
            <div class="info-group">
                <h4>Datos del vehiculo</h4>
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Marca:</span>
                        <span class="info-value"><?= htmlspecialchars($info_vehiculo['marca']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Modelo:</span>
                        <span class="info-value"><?= htmlspecialchars($info_vehiculo['modelo']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Año:</span>
                        <span class="info-value"><?= htmlspecialchars($info_vehiculo['anio']) ?></span>
                    </div>
                </div> 
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Dominio (Patente):</span>
                        <span class="info-value"><?= htmlspecialchars($info_vehiculo['dominio']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Chasis:</span>
                        <span class="info-value"><?= htmlspecialchars($info_vehiculo['chasis']) ?></span>
                    </div>
                    <div class="info-item" style="visibility: hidden;"></div> 
                </div>
            </div>
            
            <div class="info-group">
                <h4>Informacion del cliente</h4>
                    <div class="info-row">
                        <div class="info-item">
                            <span class="info-label">Nombre completo:</span>
                            <span class="info-value"><?= htmlspecialchars($info_vehiculo['cliente_nombre'] . ' ' . $info_vehiculo['cliente_apellido']) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">DNI:</span>
                            <span class="info-value"><?= htmlspecialchars($info_vehiculo['cliente_dni'] ) ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Telefono:</span>
                            <span class="info-value"><?= htmlspecialchars($info_vehiculo['cliente_tel'] ?? 'no registrado') ?></span>
                        </div>
                     </div>
    </div>
            <div class="info-group">
                <h4>Observaciones</h4>
                <div class="info-row">
                    <div class="info-item" style="flex: 0 0 30%;"> <span class="info-label">Vendedor</span>
                        <span class="info-value"><?= htmlspecialchars($info_vehiculo['vendedor']) ?></span>
                    </div>
                </div>
                <span class="info-label">Observaciones</span>
                <div class="info-value" style="min-height: 80px;"><?= htmlspecialchars($info_vehiculo['descripcion']) ?></div>
            </div>
            <a href="../index.php" class="btn secondary">Volver al panel principal</a>
            <a href="editar_vehiculo.php?id=<?= $id_vehiculo ?>" class="btn primary">Editar vehiculo</a>
        </div>
    </div>
</body>
</html>