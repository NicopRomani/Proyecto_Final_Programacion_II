<?php
require __DIR__ . '/../includes/db.php';
$id=$_GET['id']??null;
if($id){
  $stmt=$conn->prepare("DELETE FROM vehiculos WHERE id=?");
  $stmt->execute([$id]);
}
header("Location: ../index.php");
exit;
?>
