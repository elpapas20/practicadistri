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
                                <a href="${game.store_link}" target="_blank" class="btn">Microsoft Store</a>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
    });
});
