<?php
session_start();
include 'db.php'; // Asegúrate de que la ruta de inclusión sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare('SELECT id, password, photo FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $photo);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['photo'] = $photo ? $photo : 'default.jpg'; // Usar 'default.jpg' si no hay foto
            header('Location: ../index.php?message=' . urlencode('Contraseña correcta'));
            exit;
        } else {
            $error = 'Clave incorrecta';
        }
    } else {
        $error = 'Usuario no encontrado';
    }
    $stmt->close();
    $conn->close();
} else {
    $error = 'Método de solicitud no válido';
}

header('Location: login.php?error=' . urlencode($error)); // Ajusta la ruta de redirección
exit;
?>
