$(document).ready(() => {
    fetchFriendsData('SuggestedFriends', displaySuggestedFriends);
    fetchFriendsData('CurrentFriends', displayCurrentFriends);
});

function fetchFriendsData(type, displayFunction) {
    console.time(`fetch${type}`);
    fetch(`backend/getFriends.php?term=${encodeURIComponent(type)}`)
        .then(response => {
            console.timeEnd(`fetch${type}`);
            if (!response.ok) throw new Error(`Network response for ${type} was not ok`);
            return response.json();
        })
        .then(data => displayFunction(data))
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
            const friendData = await getUserData(user.SuggestedEmail || user.username);
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

function addFriend(friendName) {
    if (!friendName.length) return;

    fetch(`backend/addFriend.php?friend=${encodeURIComponent(friendName)}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                fetchFriendsData('SuggestedFriends', displaySuggestedFriends);
                fetchFriendsData('CurrentFriends', displayCurrentFriends);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error adding friend:', error));
}

function friendAction(event) {
    const button = event.target;
    const friendName = button.getAttribute('data-username');
    const action = button.getAttribute('data-action');
    console.log(`${action} friend:`, friendName);
}
