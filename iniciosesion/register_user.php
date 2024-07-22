<?php
include 'db.php'; // Asegúrate de que la ruta de inclusión sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $photo = 'default.jpg'; // Foto por defecto

    // Verificar si el nombre de usuario ya existe
    $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = 'El nombre de usuario ya está en uso';
        $stmt->close();
        header('Location: register.php?error=' . urlencode($error));
        exit;
    }

    $stmt->close();

    // Insertar el nuevo usuario
    $stmt = $conn->prepare('INSERT INTO users (username, password, photo) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $username, $hashed_password, $photo);

    if ($stmt->execute()) {
        $message = 'Usuario registrado exitosamente';
        header('Location: login.php?message=' . urlencode($message));
        exit;
    } else {
        $error = 'Error al registrar el usuario';
        header('Location: register.php?error=' . urlencode($error));
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: register.php');
    exit;
}
?>
