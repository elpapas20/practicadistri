<?php
session_start();
include 'db.php';

// Manejar la publicación de comentarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $content = $_POST['content'];
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $stmt = $conn->prepare('INSERT INTO comments (user_id, username, content, image_path) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('isss', $userId, $username, $content, $imagePath);
    $stmt->execute();
    $stmt->close();
}

// Obtener comentarios
$comments = [];
$result = $conn->query('SELECT * FROM comments ORDER BY created_at DESC');
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
$result->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameZone</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1 class="fade-in">GameZone</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-info">
                <img src="img/<?php echo isset($_SESSION['photo']) ? htmlspecialchars($_SESSION['photo']) : 'default.jpg'; ?>" alt="Foto de perfil" class="profile-pic">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="iniciosesion/logout.php" class="logout-link">Cerrar sesión</a>
            </div>
        <?php else: ?>
            <div class="auth-links">
                <a href="iniciosesion/login.php" class="nav-link">Login</a>
                <a href="iniciosesion/register.php" class="nav-link">Register</a>
            </div>
        <?php endif; ?>
    </header>
    <nav class="navbar">
        <a href="#" class="nav-link">Home</a>
        <a href="#" class="nav-link">New Releases</a>
        <a href="#" class="nav-link">Top Rated</a>
        <a href="#" class="nav-link">Upcoming</a>
        <a href="#" class="nav-link">Categories</a>
    </nav>
    <div class="content">
        <div class="main-content">
            <?php include 'games.php'; ?>
            <?php if (!empty($games)): ?>
                <div id="main-game">
                    <?php $game = $games[0]; ?>
                    <!-- Game Card -->
                    <div class="game-card slide-in" data-game-id="<?php echo $game['id']; ?>">
                        <div class="image-container">
                            <img src="<?php echo $game['image']; ?>" alt="<?php echo $game['title']; ?>" class="game-image">
                        </div>
                        <div class="game-details">
                            <h3><?php echo $game['title']; ?></h3>
                            <div class="rating-section">
                                <div class="rating" data-rating="<?php echo $game['rating']; ?>">
                                    <p><?php echo $game['rating']; ?>%</p>
                                </div>
                            </div>
                            <p class="description">
                                <?php echo $game['description']; ?>
                            </p>
                            <div class="button-container">
                                <h4>Donde Obtener</h4>
                                <a href="<?php echo $game['official_site']; ?>" target="_blank" class="btn">Sitio Oficial</a>
                                <a href="<?php echo $game['store_link']; ?>" target="_blank" class="btn">Consiguelo Ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Librero Section -->
            <div class="bookshelf-section">
                <h2>Otros Juegos</h2>
                <div class="bookshelf">
                    <?php if (!empty($games)): ?>
                        <?php foreach ($games as $game): ?>
                            <div class="book" data-game-id="<?php echo $game['id']; ?>">
                                <img src="<?php echo $game['image']; ?>" alt="<?php echo $game['title']; ?>">
                                <p><?php echo $game['title']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay otros juegos disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Comment Section -->
            <?php if (isset($_SESSION['username'])): ?>
                <div class="comment-section">
                    <h2>Comentarios</h2>
                    <form action="index.php" method="post" enctype="multipart/form-data">
                        <textarea name="content" rows="4" placeholder="Escribe un comentario..." required></textarea>
                        <input type="file" name="image" accept="image/*">
                        <button type="submit" name="comment">Comentar</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="comments">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        <?php if ($comment['image_path']): ?>
                            <img src="<?php echo htmlspecialchars($comment['image_path']); ?>" alt="Imagen del comentario" class="comment-image">
                        <?php endif; ?>
                        <small><?php echo $comment['created_at']; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 GameZone</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            const games = <?php echo json_encode($games); ?>;
            const bookshelf = document.querySelector('.bookshelf');
            const mainGame = document.getElementById('main-game');

            bookshelf.addEventListener('click', (event) => {
                const book = event.target.closest('.book');
                if (book) {
                    const gameId = book.getAttribute('data-game-id');
                    const game = games.find(g => g.id == gameId);

                    if (game) {
                        mainGame.innerHTML = `
                            <div class="game-card slide-in" data-game-id="${game.id}">
                                <div class="image-container">
                                    <img src="${game.image}" alt="${game.title}" class="game-image">
                                </div>
                                <div class="game-details">
                                    <h3>${game.title}</h3>
                                    <div class="rating-section">
                                        <div class="rating" data-rating="${game.rating}">
                                            <p>${game.rating}%</p>
                                        </div>
                                    </div>
                                    <p class="description">
                                        ${game.description}
                                    </p>
                                    <div class="button-container">
                                        <h4>Donde Obtener</h4>
                                        <a href="${game.official_site}" target="_blank" class="btn">Sitio Oficial</a>
                                        <a href="${game.store_link}" target="_blank" class="btn">Consiguelo ahora</a>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }
            });
        });
    </script>
</body>
</html>
