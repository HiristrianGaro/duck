console.log('Loading profilePage.js');

$(document).ready(function () {
    const params = new URLSearchParams(window.location.search);
    const username = sanitizeInput(params.get('username'));
    if (username) {
        initializeProfilePage(username);
    }
    console.log('Saved Username:', localStorage.getItem('savedUsername'));
});


function initializeProfilePage(username) {
    showLoadingIndicator('profile-container');

    loadProfilePage(username);
    checkFriendship(username);
    loadProfilePosts(username);
}


function loadProfilePage(username) {
    fetch(`backend/searchUsers.php?term=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(userData => {
            console.log('Received profile data:', userData);
            displayProfileResults(userData);
            localStorage.setItem('savedUsername', username);
        })
        .catch(error => {
            console.error('Error fetching profile data:', error);
            displayErrorMessage('profile-header', 'Failed to load profile. Please try again later.');
        });
}


function loadProfilePosts(username) {
    fetch(`backend/getPosts.php?user=${encodeURIComponent(username)}&term=User`)
        .then(response => response.json())
        .then(postData => {
            console.log('Received post data:', postData);
            displayPostResults(postData);
        })
        .catch(error => {
            console.error('Error fetching post data:', error);
            displayErrorMessage('grid-item', 'Failed to load posts. Please try again later.');
        });
}


function checkFriendship(username) {
    fetch(`backend/checkFriendship.php?username=${encodeURIComponent(username)}`)
        .then(response => response.json())
        .then(followData => {
            displayFollowResults(followData);
        })
        .catch(error => {
            console.error('Error fetching follow data:', error);
            displayErrorMessage('follow-btn', 'Unable to fetch follow status.');
        });
}


async function displayProfileResults(data) {
    const resultsDiv = document.getElementById('profile-header');
    resultsDiv.innerHTML = ''; // Clear previous results

    if (data.length > 0) {
        const template = await fetchTemplate('frontend/items/profileHead.html');
        const user = data[0]; // Assuming only one user profile is fetched

        const userHtml = template
            .replace(/{{profilepicture}}/g, sanitizeInput(user.PosizioneFileSystemFotoProf))
            .replace(/{{Username}}/g, sanitizeInput(user.username))
            .replace(/{{Gender}}/g, sanitizeInput(user.gender));

        resultsDiv.innerHTML = userHtml;
        console.log('Profile header populated.');
    } else {
        resultsDiv.textContent = 'No user found.';
        console.log('No user found.');
    }
}


async function displayPostResults(data) {
    const resultsDiv = document.getElementById('grid-item');
    resultsDiv.innerHTML = ''; // Clear previous results

    if (data.length > 0) {
        const template = await fetchTemplate('frontend/items/postGridItem.html');
        data.forEach(post => {
            const postHtml = template.replace(/{{postlocation}}/g, sanitizeInput(post.PosizioneFile));
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


function displayFollowResults(data) {
    const followBtn = document.getElementById('follow-btn');
    followBtn.innerHTML = '';

    if (data.length > 0) {
        const status = sanitizeInput(data[0].Accettazione);

        if (status === 'Accettato' || status === 'In Attesa') {
            followBtn.textContent = 'Unfollow';
            followBtn.className = 'btn btn-danger';
            followBtn.setAttribute('data-action', 'Remove')
        } else if (status === 'Self') {
            followBtn.textContent = 'Edit Profile';
            followBtn.className = 'btn btn-primary';
            followBtn.removeAttribute('data-action')
        }
    } else {
        followBtn.textContent = 'Follow';
        followBtn.className = 'btn btn-primary';
        followBtn.setAttribute('data-action', 'Add')
    }
    console.log('Follow button updated.');
}

function addRemoveFriend(username, action) {
    if (!username.length) return;

    fetch(`backend/addRemoveFriend.php?ricevente=${encodeURIComponent(username)}&action=${encodeURIComponent(action)}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            checkFriendship(username);
        
        })
        .catch(error => console.error('Error adding/removing friend:', error));
}

function followAction(event) {
    const button = event.target;
    const params = new URLSearchParams(window.location.search);
    const username = sanitizeInput(params.get('username'));
    const action = button.getAttribute('data-action');
    if (action === null) {
        targetFile = 'frontend/edit-profile.html';
        history.pushState({ targetFile }, '', `?page=${targetFile}&username=${username}`);
        loadPage(targetFile);
    }
    console.log(`${action} friend:`, username);
    addRemoveFriend(username, action)
}


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



function displayErrorMessage(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = `<p class='text-center text-danger'>${message}</p>`;
    }
}


function sanitizeInput(input) {
    const div = document.createElement('div');
    div.textContent = input;
    return div.innerHTML;
}
