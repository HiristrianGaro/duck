function getFriendsPosts() {
    fetch('backend/getFriendsPosts.php')
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
            displayFriendsPostResults(data);
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
        });
}

function displayFriendsPostResults(data) {
    const postsContainer = document.getElementById('LetTheEggsSwim');
    postsContainer.innerHTML = ''; // Clear the existing content
    data.forEach(post => {
        const postElement = document.createElement('div');
        postElement.classList.add('post');
        postElement.innerHTML = `
            <div class="postHeader">
                <img src="images/profile.png" alt="Profile Picture" class="profilePicture">
                <div class="postInfo">
                    <h3>${post.username}</h3>
                    <p>${post.date}</p>
                </div>
            </div>
            <p>${post.content}</p>
        `;
        postsContainer.appendChild(postElement);
    });
}