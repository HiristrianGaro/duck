console.log('Pond Functionality JS Loaded');

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

async function displayFriendsPostResults(data) {
    const postsContainer = document.getElementById('LetTheEggsSwim');
    postsContainer.innerHTML = ''; // Clear the existing content
    const template = await fetchTemplate('common/egg.html');
    if (data.length > 0) {
        data.forEach(post, index => {
            // Log the postname being replaced
            console.log('Replacing {{Username}} with:', post.username);
        
            let postHtml = template;
            console.log('template:', postHtml);
            postHtml = postHtml.replace(/{{profilepicture}}/g, post.fotoprofilo)
                               .replace(/{{postname}}/g, post.postname)
                               .replace(/{{Gender}}/g, post.gendere)
            
            // Log the final HTML
        
            const postElement = document.createElement('div');
            postElement.innerHTML = postHtml;
            resultsDiv.innerHTML = postElement.innerHTML;
        });
        
    } else if (document.getElementById('profile-header').textContent.trim().length >= 4) {
        resultsDiv.textContent = 'No posts found.';
        console.log('No posts found'); // Log no posts found case
    }
}