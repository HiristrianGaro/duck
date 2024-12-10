

// Function to fetch and return the HTML template as a string
async function fetchTemplate(url) {
    const response = await fetch(url);
    return response.text();
}

function hideSearchBar() { $('#SeachCollapse').collapse('hide'); }

$(document).on('click', function (event) { 
    if (!$(event.target).closest('.searchCollapse, #search-input').length) { 
        hideSearchBar(); 
    } 
});





// SEARCH FUNCTIONALITY

// JavaScript function to handle the dynamic search for users
function searchUsers() {
    const searchTerm = document.getElementById('search-input').value.trim();
    console.log('Search term:', searchTerm); // Log the search term

    // Set minimum number of characters for the search term
    const minChars = 4;

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
            userHtml = userHtml.replace('{{Username}}', user.username)
                               .replace('{{profilepicture}}', user.fotoprofilo);

            // Log the final HTML
            console.log('Final HTML:', userHtml);

            const userElement = document.createElement('div');
            userElement.innerHTML = userHtml;

            // Attach click event to the user element
            userElement.querySelector('.user-result').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                hideSearchBar();
                loadProfilePage(user.username);

            });

            resultsDiv.appendChild(userElement.firstChild);
        });
    } else if (document.getElementById('search-input').value.trim().length >= 4) {
        resultsDiv.textContent = 'No users found.';
        console.log('No users found'); // Log no users found case
    }
}








// PROFILE FUNCTIONALITY

// JavaScript function to handle the dynamic search for posts
function getPosts() {
    const searchTerm = document.getElementById('UserID').value;
    console.log('Search term:', searchTerm); // Log the search term

    fetch('backend/getPosts.php?term=' + encodeURIComponent(searchTerm))
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
            displayPostResults(data);
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
        });
}

        
async function displayProfileResults(data) {
    const resultsDiv = document.getElementById('profile-header');
    resultsDiv.innerHTML = ' '; // Clear previous results
    console.log('Displaying results:', data); // Log the data being displayed

    // Fetch the template
    const template = await fetchTemplate('common/profileHeader.html');

    if (data.length > 0) {
        data.forEach(user => {
            // Log the username being replaced
            console.log('Replacing {{Username}} with:', user.username);
        
            let userHtml = template;
            userHtml = userHtml.replace('{{profilepicture}}', user.fotoprofilo)
                               .replace('{{Username}}', user.username);
            
            // Log the final HTML
            console.log('Final HTML:', userHtml);
        
            const userElement = document.createElement('div');
            userElement.innerHTML = userHtml;
            resultsDiv.appendChild(userElement.firstChild);
        });
        
    } else if (document.getElementById('profile-header').textContent.trim().length >= 4) {
        resultsDiv.textContent = 'No users found.';
        console.log('No users found'); // Log no users found case
    }

}

// Function to display search results for posts using a template
async function displayPostResults(data) {
    const resultsDiv = document.getElementById('grid-item');
    resultsDiv.innerHTML = ''; // Clear previous results
    console.log('Displaying results:', data); // Log the data being displayed

    // Fetch the template
    const template = await fetchTemplate('common/postGridItem.html');

    if (data.length > 0) {
        data.forEach(post => {
            // Populate the template with post data
            let postHtml = template;
            postHtml = postHtml.replace('{{postlocation}}', post.PosizioneFile);
                                
            
            // Convert the populated template to a DOM element
            const postElement = document.createElement('div');
            postElement.innerHTML = postHtml;
            resultsDiv.appendChild(postElement.firstChild);
        });
    } else {
        resultsDiv.textContent = 'No posts from this user';
        console.log('No posts found'); // Log no posts found case
    }
}

function loadProfilePage(username) {
    console.log('Loading profile page for:', username); // Log the username being loaded
    const targetFile = 'frontend/profilePage.html';
    
    // Use the existing loadPage function to load the profile page content

    // Once the page is loaded, fetch and display the profile data for the clicked user
    fetch('backend/searchUsers.php?term=' + encodeURIComponent(username))
        .then(response => response.json())
        .then(userData => {
            console.log('Received profile data:', userData); // Log the received data
            displayProfileResults(userData);
            localStorage.setItem('savedUsername', username);
        })
        .catch(error => {
            console.error('Error fetching profile data:', error);
        });

        loadPage(targetFile);
}




// EVENT LISTENERS
document.getElementById('search-input').addEventListener('input', searchUsers);
document.getElementById('SeachCollapse').addEventListener('hidden.bs.collapse', function () {
    document.getElementById('search-input').value = '';
    document.getElementById('search-results').innerHTML = '';
});

document.getElementById('user-profile').addEventListener('click', function() {
    const username = this.getAttribute('data-username');
    loadProfilePage(username);
});