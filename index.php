<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}
require __DIR__ . '/includes/db.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Panel</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <div class="container">
    <div class="card">

      <h2>Panel principal</h2>
      <div style="text-align:center; margin-bottom: 1rem;">
        <span class="badge"><?= htmlspecialchars($_SESSION['usuario']) ?> (<?= htmlspecialchars($_SESSION['estado']) ?>)</span>
        <a class="btn link" href="logout.php">Cerrar sesión</a>
      </div>

      <div class="center">
        <div class="btn-group">
          <a class="btn primary" href="usuarios/registrar_usuario.php">Registrar usuario</a>
          <a class="btn primary" href="vehiculos/registrar_vehiculo.php">Registrar vehículo</a>
        </div>
      </div>

      <h3>Usuarios</h3>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php $stmt = $conn->query("SELECT * FROM usuarios");
          while ($u = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td><?= htmlspecialchars($u['usuario']) ?></td>
              <td><?= $u['estado'] ?></td>
              <td>
                <a class="btn action" href="usuarios/editar_usuario.php?id=<?= $u['id'] ?>">Editar</a>
                <a class="btn action" href="usuarios/eliminar_usuario.php?id=<?= $u['id'] ?>" onclick="return confirm('¿Eliminar?');">Eliminar</a>
                <?php if ($u['estado'] === 'activo'): ?>
                  <a class="btn action" href="usuarios/cambiar_estado.php?id=<?= $u['id'] ?>&estado=inactivo">Desactivar</a>
                <?php else: ?>
                  <a class="btn action" href="usuarios/cambiar_estado.php?id=<?= $u['id'] ?>&estado=activo">Activar</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <h3>Vehículos</h3>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Dominio</th>
            <th>Descripción</th>
            <th>Vendedor</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT v.id,v.marca,v.modelo,v.anio,v.dominio,v.descripcion,u.usuario AS vendedor
            FROM vehiculos v JOIN usuarios u ON v.vendedor_id=u.id ORDER BY v.id DESC";
          $stmt = $conn->query($sql);
          while ($v = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
              <td><?= $v['id'] ?></td>
              <td><?= htmlspecialchars($v['marca']) ?></td>
              <td><?= htmlspecialchars($v['modelo']) ?></td>
              <td><?= htmlspecialchars($v['anio']) ?></td>
              <td class="dominio"><?= htmlspecialchars($v['dominio'] ?? '') ?></td>
              <td class="descripcion"><?= htmlspecialchars($v['descripcion'] ?? '') ?></td>
              <td><?= htmlspecialchars($v['vendedor']) ?></td>
              <td>
                <a class="btn action" href="vehiculos/editar_vehiculo.php?id=<?= $v['id'] ?>">Editar</a>
                <a class="btn action" href="vehiculos/eliminar_vehiculo.php?id=<?= $v['id'] ?>" onclick="return confirm('¿Eliminar vehículo?');">Eliminar</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

    </div>
  </div>
</body>

</html>