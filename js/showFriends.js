$(document).ready(function () {
    getSuggestedFriends();
    getCurrentFriends();
    
});

function getSuggestedFriends() {
    console.time('getSuggestedFriends');
    fetch('backend/getFriends.php?term=' + encodeURIComponent('SuggestedFriends'))
        .then(response => {
            console.timeEnd('getSuggestedFriends');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => displaySuggestedFriends(data))
        .catch(error => console.error('Error fetching suggested friends:', error));
}

function getCurrentFriends() {
    console.time('getCurrentFriends');
    fetch('backend/getFriends.php?term=' + encodeURIComponent('CurrentFriends'))
        .then(response => {
            console.timeEnd('getCurrentFriends');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => displayCurrentFriends(data))
        .catch(error => console.error('Error fetching current friends:', error));
}

function getUserData(searchTerm) {
    if (searchTerm.length > 0) {
        return fetch('backend/searchUsers.php?term=' + encodeURIComponent(searchTerm))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => data)
            .catch(error => {
                console.error('Error fetching user data:', error);
                return [];
            });
    } else {
        return Promise.resolve([]);
    }
}

async function displaySuggestedFriends(data) {
    const SuggestedFriendsContainer = document.getElementById('SuggestedFriendsListId');
    SuggestedFriendsContainer.innerHTML = ''; // Clear the existing content
    console.time('FetchSuggestedTemplate');
    const template = await fetchTemplate('common/friendListItem.html');
    console.timeEnd('FetchSuggestedTemplate');
    console.time('displaySuggestedFriends');
    if (data.length > 0) {
        // Use Promise.all to fetch user data concurrently
        const friendsHtml = await Promise.all(data.map(async (SuggestedFriend) => {
            const user = SuggestedFriend.SuggestedEmail;
            const SuggestedFriendData = await getUserData(user);

            if (SuggestedFriendData && SuggestedFriendData.length > 0) {
                let userHtml = template;
                const friendData = SuggestedFriendData[0]; // Assuming getUserData returns an array
                
                userHtml = userHtml.replace('{{fotoprofilo}}', friendData.fotoprofilo)
                                   .replace(/{{Username}}/g, friendData.username)
                                   .replace('{{buttonType}}', 'btn-success')
                                   .replace(/{{Status}}/g, 'Add');
                return userHtml;
            }
            return '';
        }));

        SuggestedFriendsContainer.innerHTML = friendsHtml.join('');
    } else {
        SuggestedFriendsContainer.textContent = 'No suggested Friends.';
    }
    console.timeEnd('displaySuggestedFriends');
}

async function displayCurrentFriends(data) {
    const resultsDiv = document.getElementById('CurrentFriendsListId');
    resultsDiv.innerHTML = ''; // Clear previous results
    console.time('FetchCurrentTemplate');
    const template = await fetchTemplate('common/friendListItem.html');
    console.timeEnd('FetchCurrentTemplate');
    console.time('displayCurrentFriends');
    if (data.length > 0) {
        const friendsHtml = data.map(user => {
            let userHtml = template;
            userHtml = userHtml.replace('{{fotoprofilo}}', user.fotoprofilo)
                               .replace(/{{Username}}/g, user.username)
                               .replace('{{buttonType}}', 'btn-danger')
                               .replace(/{{Status}}/g, 'Remove');
            return userHtml;
        });
    console.timeEnd('displayCurrentFriends');

        resultsDiv.innerHTML = friendsHtml.join('');
    } else {
        resultsDiv.textContent = 'No users found.';
    }
}

function addFriend() {
    if (friendName.length > 0) {
        fetch('backend/addFriend.php?friend=' + encodeURIComponent(friendName))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    getCurrentFriends();
                    getSuggestedFriends();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error adding friend:', error));
    }
}

function friendAction(event) {
    const button = event.target;
    const friendName = button.getAttribute('data-username');
    const action = button.getAttribute('data-action');
    if (action === 'Add') {
        console.log('Add friend:', friendName);
    } else if (action === 'Remove') {
        console.log('Remove friend:', friendName);
    }
}

function removeFriend(user) {
    
}

