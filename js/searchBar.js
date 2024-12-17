function searchUsers() {
    const searchTerm = document.getElementById('search-input').value.trim();
    console.log('Search term:', searchTerm); // Log the search term

    // Set minimum number of characters for the search term
    const minChars = 3;

    if (searchTerm.length >= minChars) {
        fetch('backend/searchUsers.php?term=' + encodeURIComponent(searchTerm))
            .then(response => {
                console.log('Response status:', response.status); // Log the response status
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data); // Log the received data
                // Display the received data
                displayUserResults(data);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    } else {
        // Clear results if search term is too short
        console.log(`Search term must be at least ${minChars} characters long.`);
        displayUserResults([]);
    }
}

// Function to display search results for users using a template
async function displayUserResults(data) {
    const resultsDiv = document.getElementById('search-results');
    resultsDiv.innerHTML = ''; // Clear previous results
    console.log('Displaying results:', data); // Log the data being displayed

    // Fetch the template
    const template = await fetchTemplate('common/searchItem.html');

    if (data.length > 0) {
        data.forEach(user => {
            // Log the username being replaced
            console.log('Replacing {{Username}} with:', user.username);

            let userHtml = template;
            userHtml = userHtml.replace(/{{Username}}/g, user.username)
                               .replace(/{{profilepicture}}/g, user.fotoprofilo);

            // Log the final HTML
            console.log('Final Search Item HTML:', userHtml);

            const userElement = document.createElement('div');
            userElement.innerHTML = userHtml;

            // Attach click event to the user element
            //Need to modify this to load the profile page
            userElement.querySelector('.user-result').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                hideSearchBar();
                targetFile = 'frontend/profilepage.php';
                username = this.getAttribute('data-username');
                console.log('Trying to add param:', username, targetFile);
                history.pushState({ targetFile: targetFile }, '', `?page=${targetFile}&username=${username}`);
                loadPage(targetFile);

            });

            resultsDiv.appendChild(userElement.firstChild);
        });
    } else if (document.getElementById('search-input').value.trim().length >= 4) {
        resultsDiv.textContent = 'No users found.';
        console.log('No users found'); // Log no users found case
    }
}



document.getElementById('search-input').addEventListener('input', searchUsers);
document.getElementById('SeachCollapse').addEventListener('hidden.bs.collapse', function () {
    document.getElementById('search-input').value = '';
    document.getElementById('search-results').innerHTML = '';
});