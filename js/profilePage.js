console.log('Loading profilePage.js');

$(document).ready(function () {
    const params = new URLSearchParams(window.location.search);
    const username = params.get('username');
    if (username) {
        initializeProfilePage(username);
    }
    console.log('Saved Username:', localStorage.getItem('savedUsername'));
});

// Initialize the profile page
function initializeProfilePage(username) {
    loadProfilePage(username);
    checkFriendship(username);
    loadProfilePosts(username);
}

// Fetch and display the user's profile data
function loadProfilePage(username) {
    fetch(`backend/searchUsers.php?term=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(userData => {
            console.log('Received profile data:', userData);
            displayProfileResults(userData);
            localStorage.setItem('savedUsername', username);
        })
        .catch(error => console.error('Error fetching profile data:', error));
}

// Fetch and display the user's posts
function loadProfilePosts(username) {
    fetch(`backend/getUserPosts.php?term=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(postData => {
            console.log('Received post data:', postData);
            displayPostResults(postData);
        })
        .catch(error => console.error('Error fetching post data:', error));
}

// Check and display the friendship status
function checkFriendship(username) {
    fetch(`backend/checkFriendship.php?username=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(followData => {
            console.log('Received follow data:', followData);
            displayFollowResults(followData);
        })
        .catch(error => console.error('Error fetching follow data:', error));
}

// Display the user's profile data
async function displayProfileResults(data) {
    const resultsDiv = document.getElementById('profile-header');
    resultsDiv.innerHTML = ''; // Clear previous results

    if (data.length > 0) {
        const template = await fetchTemplate('common/profileHead.html');
        const user = data[0]; // Assuming only one user profile is fetched

        const userHtml = template
            .replace(/{{profilepicture}}/g, user.fotoprofilo)
            .replace(/{{Username}}/g, user.username)
            .replace(/{{Gender}}/g, user.gender);

        resultsDiv.innerHTML = userHtml;
        console.log('Profile header populated.');
    } else {
        resultsDiv.textContent = 'No user found.';
        console.log('No user found.');
    }
}

// Display the user's posts
async function displayPostResults(data) {
    const resultsDiv = document.getElementById('grid-item');
    resultsDiv.innerHTML = ''; // Clear previous results

    if (data.length > 0) {
        const template = await fetchTemplate('common/postGridItem.html');
        data.forEach(post => {
            const postHtml = template.replace(/{{postlocation}}/g, post.PosizioneFile);
            const postElement = document.createElement('div');
            postElement.innerHTML = postHtml;
            resultsDiv.appendChild(postElement.firstChild);
        });
        console.log('Posts grid populated.');
    } else {
        resultsDiv.innerHTML = "<p class='text-center m-0 p-0'>No posts found.</p>";
        console.log('No posts found.');
    }
}

// Display friendship status
function displayFollowResults(data) {
    const followBtn = document.getElementById('follow-btn');
    followBtn.innerHTML = ''; // Clear previous content

    if (data.length > 0) {
        const status = data[0].Accettazione;

        if (status === 'Accettato' || status === 'Rifiutato') {
            followBtn.textContent = 'Follow';
            followBtn.className = 'btn btn-primary';
        } else if (status === 'In Attesa') {
            followBtn.textContent = 'Requested';
            followBtn.className = 'btn btn-primary-darker';
        } else if (status === 'Self') {
            followBtn.textContent = 'Edit Profile';
            followBtn.className = 'btn btn-primary';
        } else {
            followBtn.textContent = 'Follow';
            followBtn.className = 'btn btn-primary';
        }
    } else {
        followBtn.textContent = 'Follow';
        followBtn.className = 'btn btn-primary';
    }
    console.log('Follow button updated.');
}

// Utility function to fetch an HTML template
async function fetchTemplate(templatePath) {
    try {
        const response = await fetch(templatePath);
        if (!response.ok) throw new Error('Failed to fetch template');
        return await response.text();
    } catch (error) {
        console.error('Error fetching template:', error);
        return '';
    }
}
