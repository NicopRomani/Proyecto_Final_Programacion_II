<?php
require __DIR__ . '/../includes/db.php';
$id=$_GET['id']??null;
$estado=$_GET['estado']??null;
if($id && $estado){
  $stmt=$conn->prepare("UPDATE usuarios SET estado=? WHERE id=?");
  $stmt->execute([$estado,$id]);
}
header("Location: ../index.php");
exit;
?>
