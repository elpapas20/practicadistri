document.addEventListener("DOMContentLoaded", function() {
    fetch('https://api.allorigins.win/get?url=' + encodeURIComponent('https://vandal.elespanol.com/noticias/videojuegos'))
        .then(response => response.json())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data.contents, 'text/html');
            const articles = doc.querySelectorAll('.post');

            const newsTable = document.getElementById('news-table');
            
            articles.forEach(article => {
                const row = document.createElement('tr');
                
                const imgCell = document.createElement('td');
                const img = document.createElement('img');
                const imgSrc = article.querySelector('.post-image img').src;
                img.src = imgSrc || 'default_image.jpg';
                img.alt = article.querySelector('.post-title a').innerText;
                img.style.width = '50px';
                img.style.height = '50px';
                imgCell.appendChild(img);
                row.appendChild(imgCell);
                
                const textCell = document.createElement('td');
                const title = document.createElement('h3');
                title.textContent = article.querySelector('.post-title a').innerText;
                const date = document.createElement('p');
                date.textContent = article.querySelector('.post-date').innerText;
                const description = document.createElement('p');
                description.textContent = article.querySelector('.post-excerpt').innerText;
                const link = document.createElement('a');
                link.href = article.querySelector('.post-title a').href;
                link.className = 'news-category';
                link.textContent = 'Leer más';
                
                textCell.appendChild(title);
                textCell.appendChild(date);
                textCell.appendChild(description);
                textCell.appendChild(link);
                
                row.appendChild(textCell);
                
                newsTable.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching news:', error));
});
