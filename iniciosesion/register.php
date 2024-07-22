<?php
session_start();
include '../db.php'; // Asegúrate de que la ruta de inclusión sea correcta

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GameZone</title>
    <link rel="stylesheet" href="instyles.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Register</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <form action="register.php" method="post">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required>
                    <img src="ojo1.png" alt="Mostrar/Ocultar contraseña" class="toggle-password" id="togglePassword">
                </div>

                <button type="submit">Register</button>
            </form>
            <a href="login.php" class="login-link">Already have an account? Login here</a>
        </div>
        <div class="carousel-container">
            <div class="carousel">
                <img src="../img/mc.jpg" alt="Imagen 1" class="active">
                <img src="../img/fr.jpeg" alt="Imagen 2">
                <img src="../img/tr.jpg" alt="Imagen 3">
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            const img = document.getElementById('togglePassword');
            img.src = type === 'password' ? 'ojo1.png' : 'ojo2.png'; // Cambia 'ojo.png' y 'ojo-abierto.png' por las rutas correctas de tus imágenes.
        });

        let currentIndex = 0;
        const images = document.querySelectorAll('.carousel img');
        const totalImages = images.length;

        function showNextImage() {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % totalImages;
            images[currentIndex].classList.add('active');
        }

        setInterval(showNextImage, 3000); // Cambia de imagen cada 3 segundos
    </script>
</body>
</html>
