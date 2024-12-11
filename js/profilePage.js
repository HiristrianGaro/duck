console.log('Loading profilePage.js');


$(document).ready(function () {
    const params = new URLSearchParams(window.location.search);
    const username = params.get('username');
    if (username) {
        loadProfilePage(username);
        loadProfilePosts(username);
    }
});
// PROFILE FUNCTIONALITY

// JavaScript function to handle the dynamic search for posts
// Function to fetch and return the HTML template as a string

function getProfileData() {
    console.log('Loading profile page for:', username); // Log the username being loaded
    const targetFile = 'frontend/profilepage.php';
    
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

}



function getPosts() {
    const searchTerm = document.getElementById('UserID').value;
    console.log('Search term:', searchTerm); // Log the search term

    fetch('backend/getUserPosts.php?term=' + encodeURIComponent(searchTerm))
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
    const template = await fetchTemplate('common/profileHead.html');
    if (data.length > 0) {
        data.forEach(user => {
            // Log the username being replaced
            console.log('Replacing {{Username}} with:', user.username);
        
            let userHtml = template;
            console.log('template:', userHtml);
            userHtml = userHtml.replace(/{{profilepicture}}/g, user.fotoprofilo)
                               .replace(/{{Username}}/g, user.username)
                               .replace(/{{Gender}}/g, user.gendere)
            
            // Log the final HTML
        
            const userElement = document.createElement('div');
            userElement.innerHTML = userHtml;
            resultsDiv.innerHTML = userElement.innerHTML;
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
}

function loadProfilePosts(username) {
    fetch('backend/getUserPosts.php?term=' + encodeURIComponent(username))
        .then(response => response.json())
        .then(postData => {
            console.log('Received post data:', postData); // Log the received data
            displayPostResults(postData);
        })
        .catch(error => {
            console.error('Error fetching post data:', error);
        });
}

