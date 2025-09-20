<?php
require __DIR__ . '/../includes/db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $marca=$_POST['marca']??'';
  $modelo=$_POST['modelo']??'';
  $anio=$_POST['anio']??'';
  $dominio=$_POST['dominio']?:null;
  $chasis=$_POST['chasis']??'';
  $descripcion=$_POST['descripcion']??'';
  $vendedor_id=1; // ajustar según sesión
  $stmt=$conn->prepare("INSERT INTO vehiculos(marca,modelo,anio,dominio,chasis,descripcion,vendedor_id) VALUES(?,?,?,?,?,?,?)");
  $stmt->execute([$marca,$modelo,$anio,$dominio,$chasis,$descripcion,$vendedor_id]);
  header("Location: ../index.php"); exit;
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Registrar vehículo</title>
<link rel="stylesheet" href="../css/style.css"></head><body>
<div class="container"><div class="card">
<h2>Registrar vehículo</h2>
<form method="post" class="form form-centered">
  <input type="text" name="marca" placeholder="Marca" required>
  <input type="text" name="modelo" placeholder="Modelo" required>
  <input type="text" name="anio" placeholder="Año" required>
  <input type="text" name="dominio" placeholder="Dominio (opcional)">
  <input type="text" name="chasis" placeholder="Chasis" required>
  <textarea name="descripcion" placeholder="Descripción"></textarea>
  <button type="submit" class="btn primary">Guardar</button>
  <a href="../index.php" class="btn link">Volver</a>
</form>
</div></div></body></html>
