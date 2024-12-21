$(document).ready(() => {
    fetchFriendsData('SuggestedFriends', displaySuggestedFriends);
    fetchFriendsData('CurrentFriends', displayCurrentFriends);
    fetchFriendsData('FriendRequests', displayFriendRequests);
});

function fetchFriendsData(type, displayFunction) {
    console.time(`fetch${type}`);
    fetch(`backend/getFriends.php?term=${encodeURIComponent(type)}`)
    .then(response => {
        console.timeEnd(`fetch${type}`);
        if (!response.ok) {
            console.error(`Failed response for ${type}:`, response);
            throw new Error(`Network response for ${type} was not ok`);
        }
        return response.json();
    })
    .then(data => {
        console.log(`Fetched ${type} data:`, data);
        displayFunction(data);
    })
    .catch(error => console.error(`Error fetching ${type.toLowerCase()}:`, error));

}

function getUserData(searchTerm) {
    if (!searchTerm.length) return Promise.resolve([]);
    return fetch(`backend/searchUsers.php?term=${encodeURIComponent(searchTerm)}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .catch(error => {
            console.error('Error fetching user data:', error);
            return [];
        });
}

async function displayFriends(data, containerId, buttonType, buttonStatus) {
    console.log(data);
    const container = document.getElementById(containerId);
    container.innerHTML = ''; // Clear existing content

    if (!data.length) {
        container.textContent = 'No friends found.';
        return;
    }

    console.time(`FetchTemplateFor${containerId}`);
    const template = await fetchTemplate('common/friendListItem.html');
    console.timeEnd(`FetchTemplateFor${containerId}`);

    const friendsHtml = await Promise.all(data.map(async user => {
        try {
            const friendData = await getUserData(user.Email || user.Username);
            if (friendData && friendData.length > 0) {
                const userDetails = friendData[0];
                return template
                    .replace('{{fotoprofilo}}', userDetails.fotoprofilo)
                    .replace(/{{Username}}/g, userDetails.username)
                    .replace('{{buttonType}}', buttonType)
                    .replace(/{{Status}}/g, buttonStatus);
            }

            return '';
        } catch (error) {
            console.error('Error processing friend data:', user, error);
            return '';
        }
    }));

    container.innerHTML = friendsHtml.join('');
}

function displaySuggestedFriends(data) {
    displayFriends(data, 'SuggestedFriendsListId', 'btn-success', 'Add');
}
function displayCurrentFriends(data) {
    displayFriends(data, 'CurrentFriendsListId', 'btn-danger', 'Remove');
}
function displayFriendRequests(data) {
    displayFriends(data, 'FriendRequestsListId', 'btn-success', 'Accept');
}

function navigateToUserProfile(username) {
    const targetFile = 'frontend/profilepage.php';
    console.log('Navigating to user profile:', username, targetFile);
    history.pushState({ targetFile }, '', `?page=${targetFile}&username=${username}`);
    loadPage(targetFile);
    hideSearchBar();
}

function addRemoveFriend(friendName, action) {
    if (!friendName.length) return;

    fetch(`backend/addRemoveFriend.php?ricevente=${encodeURIComponent(friendName)}&action=${encodeURIComponent(action)}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data != null)
            if (action == 'Add') {
                fetchFriendsData('SuggestedFriends', displaySuggestedFriends);
            } else if (action == 'Remove') {
                fetchFriendsData('CurrentFriends', displayCurrentFriends);
            } else if (action == 'Accept') {
                fetchFriendsData('FriendRequests', displayFriendRequests);
                fetchFriendsData('CurrentFriends', displayCurrentFriends);
            }
        })
        .catch(error => console.error('Error adding/removing friend:', error));
}


function friendAction(event) {
    const button = event.target;
    const friendName = button.getAttribute('data-username');
    const action = button.getAttribute('data-action');
    console.log(`${action} friend:`, friendName);
    addRemoveFriend(friendName, action)
}
