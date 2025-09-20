<?php
require __DIR__ . '/../includes/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $usuario=$_POST['usuario']??'';
  $password=$_POST['password']??'';
  $estado=$_POST['estado']??'activo';
  if($usuario && $password){
    $hash=password_hash($password,PASSWORD_BCRYPT);
    $stmt=$conn->prepare("INSERT INTO usuarios(usuario,password,estado) VALUES(?,?,?)");
    $stmt->execute([$usuario,$hash,$estado]);
    header("Location: ../index.php"); exit;
  }
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Registrar usuario</title>
<link rel="stylesheet" href="../css/style.css"></head><body>
<div class="container"><div class="card">
<h2>Registrar usuario</h2>
<form method="post" class="form form-centered">
  <input type="text" name="usuario" placeholder="Usuario" required>
  <input type="password" name="password" placeholder="Contraseña" required>
  <select name="estado">
    <option value="activo">Activo</option>
    <option value="inactivo">Inactivo</option>
  </select>
  <button type="submit" class="btn primary">Guardar</button>
  <a href="../index.php" class="btn link">Volver</a>
</form>
</div></div></body></html>
