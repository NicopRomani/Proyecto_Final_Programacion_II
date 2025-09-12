<?php
include 'db.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioInput = trim($_POST['usuario'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');

    if ($usuarioInput === '' || $passwordInput === '') {
        $mensaje = "Completa usuario y contraseña.";
    } else {
        // Verificar duplicado
        $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE usuario = :u";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bindParam(':u', $usuarioInput);
        $stmt->execute();
        $existe = (int)$stmt->fetchColumn();

        if ($existe > 0) {
            $mensaje = "Ese usuario ya existe.";
        } else {
            $hash = password_hash($passwordInput, PASSWORD_BCRYPT);
            $sql = "INSERT INTO usuarios (usuario, password) VALUES (:u, :p)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':u', $usuarioInput);
            $stmt->bindParam(':p', $hash);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $mensaje = "No se pudo registrar el usuario.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="form-container">
    <h2>Registrar Usuario</h2>
    <?php if (!empty($mensaje)) { echo "<p class='error'>".$mensaje."</p>"; } ?>
    <form method="post">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrar</button>
    </form>
    <br>
    <a href="login.php" class="btn">Volver al Login</a>
</div>
</body>
</html>
