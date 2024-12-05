// Function to fetch and return the HTML template as a string
async function fetchTemplate(url) {
    const response = await fetch(url);
    return response.text();
}

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

function getProfileData() {
        fetch('backend/searchUsers.php?term=CurrentUser')
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
                displayProfileResults(data);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    
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
            resultsDiv.appendChild(userElement.firstChild);
        });
        
    } else if (document.getElementById('search-input').value.trim().length >= 4) {
        resultsDiv.textContent = 'No users found.';
        console.log('No users found'); // Log no users found case
    }
}


async function displayProfileResults(data) {
    const resultsDiv = document.getElementById('profile-header');
    resultsDiv.innerHTML = '<img src="{{profilepicture}}" class="rounded-circle mr-3" alt="Profile Picture" style="width: 100px; height: 100px;"><div><h2 class="h5 mb-1"><span>{{Username}}</span></h2><p class="mb-1">{{Gender}}</p></div>'; // Clear previous results
    console.log('Displaying results:', data); // Log the data being displayed

    resultsDiv.replace('{{profilepicture}}', user.fotoprofilo)
                .replace('{{Username}}', user)
                            
        
        // Convert the populated template to a DOM element
        const postElement = document.createElement('div');
        postElement.innerHTML = postHtml;
        resultsDiv.appendChild(postElement.firstChild);

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

// Add event listeners
document.getElementById('search-input').addEventListener('input', searchUsers);
document.getElementById('UserID').addEventListener('click', getProfileData);
document.getElementById('SeachCollapse').addEventListener('hidden.bs.collapse', function () {
    document.getElementById('search-input').value = '';
    document.getElementById('search-results').innerHTML = '';
});
