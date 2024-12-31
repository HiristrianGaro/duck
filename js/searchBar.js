document.getElementById('search-input').addEventListener('input', searchUsers);
document.getElementById('SeachCollapse').addEventListener('hidden.bs.collapse', clearSearch);

const MIN_SEARCH_CHARS = 3;

function searchUsers() {
    const searchTerm = document.getElementById('search-input').value.trim();
    console.log('Search term:', searchTerm);

    if (searchTerm.length >= MIN_SEARCH_CHARS) {
        fetch(`backend/searchUsers.php?term=${encodeURIComponent(searchTerm)}`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                displayUserResults(data);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    } else {
        console.log(`Search term must be at least ${MIN_SEARCH_CHARS} characters.`);
        displayUserResults([]);
    }
}

async function displayUserResults(data) {
    const resultsDiv = document.getElementById('search-results');
    resultsDiv.innerHTML = '';

    if (data.length > 0) {
        const template = await fetchTemplate('frontend/items/searchItem.html');
        const usersHtml = data.map(user => {
            const userHtml = template
                .replace(/{{Username}}/g, user.username)
                .replace(/{{profilepicture}}/g, user.fotoprofilo);

            const userElement = document.createElement('div');
            userElement.innerHTML = userHtml;
            const userResult = userElement.querySelector('.user-result');

            if (userResult) {
                userResult.addEventListener('click', event => {
                    event.preventDefault();
                    navigateToUserProfile(userResult.getAttribute('data-username'));
                });
            }

            return userElement.firstChild;
        });


        usersHtml.forEach(userEl => resultsDiv.appendChild(userEl));
    } else if (document.getElementById('search-input').value.trim().length >= MIN_SEARCH_CHARS) {
        resultsDiv.textContent = 'No users found.';
        console.log('No users found');
    }
}

function navigateToUserProfile(username) {
    const targetFile = 'frontend/profilepage.php';
    console.log('Navigating to user profile:', username, targetFile);
    history.pushState({ targetFile }, '', `?page=${targetFile}&username=${username}`);
    loadPage(targetFile);
    hideSearchBar();
}

function clearSearch() {
    document.getElementById('search-input').value = '';
    document.getElementById('search-results').innerHTML = '';
}
