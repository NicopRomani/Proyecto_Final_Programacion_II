<?php
session_start();
include 'db.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="form-container">
    <h2>Panel Principal</h2>
    <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap; margin-bottom:10px;">
        <a href="register_user.php" class="btn">Registrar Usuario</a>
        <a href="register_vehicle.php" class="btn">Registrar Vehiculo</a>
        <a href="logout.php" class="btn">Cerrar sesion</a>
    </div>

    <h3>Usuarios registrados</h3>
    <table>
        <tr><th>ID</th><th>Usuario</th><th>Acciones</th></tr>
        <?php
        $stmt = $conn->query("SELECT * FROM usuarios");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarioLabel = isset($row['usuario']) ? $row['usuario'] : (isset($row['nombre']) ? $row['nombre'] : (isset($row['username']) ? $row['username'] : '—'));
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$usuarioLabel}</td>
                <td>
                    <a href='editar_usuario.php?id={$row['id']}'>Editar</a> | 
                    <a href='eliminar_usuario.php?id={$row['id']}' onclick=\"return confirm('¿Seguro que deseas eliminar este usuario?');\">Eliminar</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <h3>Vehiculos registrados</h3>
    <table>
        <tr><th>ID</th><th>Marca</th><th>Modelo</th><th>Dominio</th><th>Año</th><th>Acciones</th></tr>
        <?php
        $stmt = $conn->query("SELECT * FROM vehiculos");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dom = $row['dominio'] ?? '';
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['marca']}</td>
                <td>{$row['modelo']}</td>
                <td>".htmlspecialchars($dom)."</td>
                <td>{$row['anio']}</td>
                <td>
                    <a href='editar_vehiculo.php?id={$row['id']}'>Editar</a> | 
                    <a href='eliminar_vehiculo.php?id={$row['id']}' onclick=\"return confirm('¿Seguro que deseas eliminar este vehículo?');\">Eliminar</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</div>
</body>
</html>