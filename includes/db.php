<?php
$host="localhost"; $dbname="proyectofinal"; $username="root"; $password="";
try {
 $conn=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username,$password);
 $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
 $pdo=$conn;
} catch(PDOException $e){ 
    die("Error de conexion a la base de datos: ".$e->getMessage()); 
}
?>

