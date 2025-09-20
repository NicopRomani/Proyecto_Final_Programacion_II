<?php
require __DIR__ . '/../includes/db.php';
$id=$_GET['id']??null; if(!$id){ header("Location: ../index.php"); exit; }
if($_SERVER['REQUEST_METHOD']==='POST'){
  $usuario=$_POST['usuario']??'';
  $password=$_POST['password']??'';
  $estado=$_POST['estado']??'activo';
  if($password){
    $hash=password_hash($password,PASSWORD_BCRYPT);
    $stmt=$conn->prepare("UPDATE usuarios SET usuario=?, password=?, estado=? WHERE id=?");
    $stmt->execute([$usuario,$hash,$estado,$id]);
  } else {
    $stmt=$conn->prepare("UPDATE usuarios SET usuario=?, estado=? WHERE id=?");
    $stmt->execute([$usuario,$estado,$id]);
  }
  header("Location: ../index.php"); exit;
}
$stmt=$conn->prepare("SELECT * FROM usuarios WHERE id=?"); $stmt->execute([$id]); $u=$stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Editar usuario</title>
<link rel="stylesheet" href="../css/style.css"></head><body>
<div class="container"><div class="card">
<h2>Editar usuario</h2>
<form method="post" class="form form-centered">
  <input type="text" name="usuario" value="<?= htmlspecialchars($u['usuario']) ?>" required>
  <input type="password" name="password" placeholder="Nueva contraseña (opcional)">
  <select name="estado">
    <option value="activo" <?= $u['estado']=='activo'?'selected':'' ?>>Activo</option>
    <option value="inactivo" <?= $u['estado']=='inactivo'?'selected':'' ?>>Inactivo</option>
  </select>
  <button type="submit" class="btn primary">Guardar</button>
  <a href="../index.php" class="btn link">Volver</a>
</form>
</div></div></body></html>
