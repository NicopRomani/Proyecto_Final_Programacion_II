<?php
require __DIR__ . '/../includes/db.php';
$id=$_GET['id']??null;
if($id){
  $stmt=$conn->prepare("DELETE FROM usuarios WHERE id=?");
  try{ $stmt->execute([$id]); }catch(PDOException $e){ die("Error al eliminar usuario: ".$e->getMessage()); }
}
header("Location: ../index.php");
exit;
?>
