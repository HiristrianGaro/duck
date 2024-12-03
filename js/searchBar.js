function debounce(func, wait) {
    let timeout;

    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };

        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// function getSelectedFilters() {
//     const filters = [];
//     document.querySelectorAll('.dropdown-menu .form-check-input:checked').forEach(checkbox => {
//         filters.push(checkbox.value);
//     });
//     return filters;
// }

document.querySelectorAll('.dropdown-menu .form-check-input').forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        const searchTerm = document.getElementById('searchInput').value;
        debouncedSearchUsersByEmail(searchTerm); // Ricerca con i filtri aggiornati
    });
});



document.addEventListener('DOMContentLoaded', function() {
    // Chiamata iniziale per caricare i primi 10 utenti
    searchUsersByEmail('');
});


function searchUsersByEmail(searchTerm) {
    const filters = getSelectedFilters(); // Raccogli i filtri selezionati
    fetch('../backend/search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ searchTerm: searchTerm, filters: filters })
    })
    .then(response => response.text()) // Ottieni la risposta come testo
    .then(text => {
        try {
            // Tenta di analizzare il testo come JSON
            const data = JSON.parse(text);
            updateSearchResults(data);
        } catch (error) {
            // Se il parsing JSON fallisce, logga l'errore e il testo della risposta
            console.error('JSON parsing error:', error);
            console.error('Response text:', text);
            throw error; // Rilancia l'errore per eventuali ulteriori gestioni
        } 
    })
    .catch(error => console.error('Error:', error));
}

const debouncedSearchUsersByEmail = debounce(searchUsersByEmail, 500);

// Event listener sull'input di ricerca
document.getElementById('searchInput').addEventListener('input', function(e) {
    debouncedSearchUsersByEmail(e.target.value);
});

function updateSearchResults(users) {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = ''; 

    users.forEach(user => {
        // Crea un form per ogni utente
        const form = document.createElement('form');
        form.action = '../common/impostaAmicoSessione.php'; 
        form.method = 'POST';

        // Aggiungi un campo nascosto per l'email dell'utente
        const hiddenEmailInput = document.createElement('input');
        hiddenEmailInput.type = 'hidden';
        hiddenEmailInput.name = 'emailAmico';
        hiddenEmailInput.value = user.email;
        
        // Crea il contenuto cliccabile, come un pulsante o un div
        const userElement = document.createElement('div');
        userElement.className = 'list-group-item rounded bg-light py-2 my-1 clickable';
        userElement.innerHTML = `<div class="row">
                                    <div class="col text-primary align-self-center fw-bold text-decoration-none">${user.name}</div>
                                 </div>`;

        // Quando il div viene cliccato, il form viene inviato
        userElement.addEventListener('click', function() {
            form.submit();
        });

        form.appendChild(hiddenEmailInput);
        form.appendChild(userElement);
        resultsContainer.appendChild(form);
    });
}