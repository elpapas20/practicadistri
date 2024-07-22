<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Interface</title>
    <link rel="stylesheet" href="instyles.css">
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <h2>Login</h2>
            <?php if (isset($_GET['error'])): ?>
                <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <form action="authenticate.php" method="post"> <!-- Asegúrate de que la ruta sea correcta -->
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" placeholder="Ingrese su usuario" required>
                
                <label for="password">Clave</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Clave" required>
                    <img src="ojo1.png" alt="Mostrar/Ocultar contraseña" class="toggle-password" id="togglePassword">
                </div>
                
                <button type="submit">Ingresar</button>
                <a href="register.php" class="register-link">Registrarme</a>
            </form>
        </div>
        <div class="carousel-container">
            <div class="carousel">
                <img src="mc.jpg" alt="Imagen 1" class="active">
                <img src="fr.jpeg" alt="Imagen 2">
                <img src="tr.jpg" alt="Imagen 3">
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            const img = document.getElementById('togglePassword');
            img.src = type === 'password' ? 'ojo1.png' : 'ojo2.png'; // Cambia 'ojo1.png' y 'ojo2.png' por las rutas correctas de tus imágenes.
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
