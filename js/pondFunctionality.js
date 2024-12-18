console.log('Pond Functionality JS Loaded');

$(document).ready(() => {
    fetchFriendsPosts();
});

async function fetchFriendsPosts() {
    console.log('Loading friends posts');
    try {
        const response = await fetch('backend/getFriendsPosts.php');
        if (!response.ok) throw new Error('Network response was not ok');

        const data = await response.json();
        const resultsDiv = document.getElementById('LetTheEggsSwim');

        // Fetch templates once
        const [templateHead, templateBody] = await Promise.all([
            fetchTemplate('common/egg-head.html'),
            fetchTemplate('common/egg-body.html'),
        ]);

        // Display all posts
        await Promise.all(
            data.map(post => displayPost(post, resultsDiv, templateHead, templateBody))
        );
    } catch (error) {
        console.error('Error fetching posts:', error);
    }
}

async function fetchPhotosForPost(timestamp, email) {
    try {
        const response = await fetch(
            `backend/getFotoforPost.php?Timestamp=${encodeURIComponent(timestamp)}&IndirizzoAutore=${encodeURIComponent(email)}`
        );
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
    } catch (error) {
        console.error('Error fetching photos for post:', error);
        return [];
    }
}

async function displayPost(post, container, templateHead, templateBody) {
    try {
        // Format the timestamp
        const formattedTimestamp = formatTimestamp(post.TimestampPubblicazione);

        // Replace placeholders in the header template
        const postHeader = templateHead.replace(/{{Username}}/g, post.Username)
                                       .replace(/{{fotoprofilo}}/g, post.fotoprofilo)
                                       .replace(/{{citta}}/g, post.NomeCitta)
                                       .replace(/{{provincia}}/g, post.ProvinciaCitta)
                                       .replace(/{{stato}}/g, post.StatoCitta)
                                       .replace(/{{Timestamp}}/g, formattedTimestamp);

        // Create a container for the post
        const postContainer = document.createElement('div');
        postContainer.className = 'post-container';
        postContainer.innerHTML = postHeader;

        // Locate the 'egg-body' class container
        const eggBody = postContainer.querySelector('.egg-body');
        if (!eggBody) {
            console.error("Could not find 'egg-body' in the template.");
            return;
        }

        // Fetch and process photos for the post
        const photos = await fetchPhotosForPost(post.TimestampPubblicazione, post.IndirizzoEmail);
        const carouselHtml = populateCarousel(templateBody, photos);

        // Add the carousel inside the egg-body container
        eggBody.innerHTML += carouselHtml;

        // Append the complete post to the results container
        container.appendChild(postContainer);
        console.log('Post appended:', postContainer.innerHTML);
    } catch (error) {
        console.error('Error displaying post:', post, error);
    }
}

// Function to populate the carousel with dynamic photos
function populateCarousel(templateBody, photos) {
    const carouselId = `carousel-${Math.random().toString(36).substring(2, 15)}`;
    const parser = new DOMParser();
    const carouselDocument = parser.parseFromString(templateBody, 'text/html');
    const carouselElement = carouselDocument.body.firstElementChild;

    // Update carousel ID for uniqueness
    carouselElement.id = carouselId;

    // Populate indicators
    const indicators = carouselElement.querySelector('.carousel-indicators');
    indicators.innerHTML = photos
        .map((_, index) => 
            `<button type="button" data-bs-target="#${carouselId}" data-bs-slide-to="${index}" class="${index === 0 ? 'active' : ''}" aria-label="Slide ${index + 1}"></button>`)
        .join('');

    // Populate carousel-inner with images
    const carouselInner = carouselElement.querySelector('.carousel-inner');
    carouselInner.innerHTML = photos
        .map((photo, index) => 
            `<div class="carousel-item ${index === 0 ? 'active' : ''}">
                <img src="${photo.PosizioneFile}" class="d-block w-100 rounded" alt="Slide ${index + 1}">
            </div>`)
        .join('');

    // Update the navigation buttons to use the unique carouselId
    const prevButton = carouselElement.querySelector('.carousel-control-prev');
    const nextButton = carouselElement.querySelector('.carousel-control-next');
    prevButton.setAttribute('data-bs-target', `#${carouselId}`);
    nextButton.setAttribute('data-bs-target', `#${carouselId}`);

    if (photos.length === 1) {
        // If only one photo, hide indicators and controls
        const indicators = carouselElement.querySelector('.carousel-indicators');
        const prevButton = carouselElement.querySelector('.carousel-control-prev');
        const nextButton = carouselElement.querySelector('.carousel-control-next');

        if (indicators) indicators.remove();
        if (prevButton) prevButton.remove();
        if (nextButton) nextButton.remove();
    }

    // Return the modified carousel HTML as a string
    return carouselElement.outerHTML;
}



function formatTimestamp(timestamp) {
    const now = new Date();
    const postTime = new Date(timestamp);
    const diffInSeconds = Math.floor((now - postTime) / 1000);

    if (diffInSeconds < 60) {
        return `${diffInSeconds} seconds ago`;
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours} hour${hours > 1 ? 's' : ''} ago`;
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days} day${days > 1 ? 's' : ''} ago`;
    }
}
