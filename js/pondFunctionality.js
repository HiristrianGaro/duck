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