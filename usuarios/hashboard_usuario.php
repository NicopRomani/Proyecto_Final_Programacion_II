<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

$mensaje_exito = $_GET['msg'] ?? null;
$usuario_rol = $_SESSION['usuario_rol'] ?? 2;

$datos_usuarios = [];
$vehiculos_busqueda = [];
$mostrar_busqueda = false;

if (!$mensaje_exito) {
    require __DIR__ . '/../includes/db.php';

    $busqueda = $_GET['busqueda'] ?? '';

    if ($busqueda) {
        $mostrar_busqueda = true;
        $param = '%' . $busqueda . '%';

        $stmt_u = $conn->prepare("SELECT id, usuario, rol FROM usuarios WHERE usuario LIKE ?");
        $stmt_u->execute([$param]);
        $datos_usuarios = $stmt_u->fetchAll(PDO::FETCH_ASSOC);

        $sql_v = "
             SELECT 
                 v.id, v.marca, v.modelo, v.anio, v.dominio, v.descripcion, u.usuario AS vendedor, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido
             FROM vehiculos v 
             JOIN usuarios u ON v.vendedor_id = u.id 
             JOIN clientes c ON v.cliente_id = c.id
             WHERE 
                 v.marca LIKE ? 
                 OR v.modelo LIKE ? 
                 OR v.dominio LIKE ?
                 OR v.chasis LIKE ?
                 OR c.dni LIKE ?
                 OR c.nombre LIKE ? 
                 OR c.apellido LIKE ? 
             ORDER BY v.id DESC
           ";
        $stmt_v = $conn->prepare($sql_v);
        $stmt_v->execute([$param, $param, $param, $param, $param, $param, $param]);
        $vehiculos_busqueda = $stmt_v->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $usuario_rol = $_SESSION['usuario_rol'] ?? 2;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de usuario</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 30px;
        }

        .titulo-principal {
            margin: 0;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #c0392b;
            color: white;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-logout {
            text-decoration: none;
            color: #c0392b;
            background: none;
            border: 1px solid #c0392b;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.2s;
            font-size: 14px;
        }

        .btn-logout:hover {
            background-color: #c0392b;
            color: white;
        }

        .form-centered {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-inline {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            align-items: center;
            max-width: 700px;
            width: 100%;
            justify-content: center;
        }

        .btn-search-group {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .form-centered input[type="text"] {
            flex-grow: 1;
            min-width: 250px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-inline .btn,
        .form-inline button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.2;
            padding: 10px 15px;
            height: 40px;
            margin: 0;
            box-sizing: border-box;
            border: 1px solid transparent;
        }

        .btn-secondary {
            background-color: #f8f8f8;
            color: #333;
            border: 1px solid #ccc !important;
        }

        .btn-secondary:hover {
            background-color: #e8e8e8;
        }

        .alert.warning {
            padding: 15px;
            border-radius: 5px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }

        .full-screen-message {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            z-index: 1000;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 40px 60px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0;
        }
    </style>
</head>

<body>
    <?php if ($mensaje_exito): ?>
        <div class="full-screen-message">
            <div class="alert success">
                <h3 style="margin-top: 0; font-weight: bold; font-size: 1.5em;"> Proceso exitoso </h3>
                <p style="font-size: 1.1em; margin-bottom: 30px;"><?= htmlspecialchars($mensaje_exito) ?></p>

                <a href="hashboard_usuario.php" class="btn primary" style="
                    background-color: #c0392b; color: white; text-decoration: none; 
                    padding: 10px 20px; border-radius: 4px; display: inline-block;
                    border: none; font-weight: bold;">
                    Volver al panel principal
                </a>
            </div>
        </div>
    <?php endif; ?>
    <div class="container">
        <div class="card">
            <div class="panel-header">
                <h2 class="titulo-principal">Panel de usuario</h2>
                <div class="user-actions">
                    <span class="user-badge" title="<?= htmlspecialchars($_SESSION['usuario']) ?> (EMPLEADO)">
                        <?= strtoupper(substr($_SESSION['usuario'], 0, 1)) ?>
                    </span>
                    <a class="btn-logout" href="../logout.php">Cerrar sesion</a>
                </div>
            </div>
            <div class="center" style="margin-bottom: 2rem;">
                <form method="GET" class="form form-centered">
                    <div class="form-inline">
                        <input type="text" name="busqueda" placeholder="Buscar..." value="<?= htmlspecialchars($busqueda) ?>">
                        <div class="btn-search-group">
                            <button type="submit" class="btn primary">Buscar</button>
                            <a href="hashboard_usuario.php" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="center" style="margin-bottom: 1rem;">
                <div class="btn-group">
                    <a class="btn primary" href="../vehiculos/registrar_vehiculo.php">Registrar vehiculo</a>
                </div>
            </div>
            <?php
            if (!isset($mensaje_exito)):

                if ($mostrar_busqueda):
            ?>
                    <h3>Vehiculos (Resultado de la busqueda)</h3>
                    <?php if (empty($vehiculos_busqueda)): ?>
                        <div class="alert warning" style="text-align: center; margin-bottom: 20px;">
                            No se encontro vehiculos para "<?= htmlspecialchars($busqueda) ?>".
                        </div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Año</th>
                                    <th>Dominio</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vehiculos_busqueda as $v): ?>
                                    <tr>
                                        <td><?= $v['id'] ?></td>
                                        <td><?= htmlspecialchars($v['marca']) ?></td>
                                        <td><?= htmlspecialchars($v['modelo']) ?></td>
                                        <td><?= htmlspecialchars($v['anio']) ?></td>
                                        <td class="dominio"><?= htmlspecialchars($v['dominio'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($v['cliente_nombre'] . ' ' . $v['cliente_apellido'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($v['vendedor']) ?></td>
                                        <td>
                                            <a class="btn action" href="../vehiculos/editar_vehiculo.php?id=<?= $v['id'] ?>">Editar</a>
                                            <a class="btn action" href="../vehiculos/ver_info.php?id=<?= $v['id'] ?>">Info</a>
                                            <a class="btn action" href="../vehiculos/eliminar_vehiculo.php?id=<?= $v['id'] ?>" onclick="return confirm('Desea eliminar el vehiculo?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>