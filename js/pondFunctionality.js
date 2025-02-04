$(document).ready(() => {
    fetchFriendsPosts();
    setupIntersectionObserver();
});

async function fetchFriendsPosts() {
    console.log('Loading friends posts');
    try {
        const response = await fetch('backend/getPosts.php?term=Friends');
        if (!response.ok) throw new Error('Network response was not ok');

        const data = await response.json();
        const resultsDiv = document.getElementById('LetTheEggsSwim');

        // Fetch templates once
        const [templateHead, templateBody] = await Promise.all([
            fetchTemplate('frontend/items/egg-head.html'),
            fetchTemplate('frontend/items/egg-body.html'),
        ]);

        // Display all posts
        await Promise.all(
            data.map(post => displayPost(post, resultsDiv, templateHead, templateBody))
        );
    } catch (error) {
        console.error('Error fetching posts:', error);
    }
}

async function fetchPhotosForPost(IdPost) {
    try {
        const response = await fetch(
            `backend/getPostFoto.php?IdPost=${encodeURIComponent(IdPost)}`
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
        const formattedTimestamp = formatTimestamp(post.TimestampPubblicazione);

        console.log('Displaying post:', post.IdPost);
        console.log('Description:', post.Descrizione);


        // Replace placeholders in the header template
        const postHeader = templateHead.replace(/{{Username}}/g, post.Username)
                                       .replace(/{{fotoprofilo}}/g, post.fotoprofilo)
                                       .replace(/{{citta}}/g, post.PostCity)
                                       .replace(/{{provincia}}/g, post.PostState)
                                       .replace(/{{stato}}/g, post.PostCountry)
                                       .replace(/{{Timestamp}}/g, formattedTimestamp)
                                       .replace(/{{IdPost}}/g, post.IdPost)
                                       .replace(/{{Descrizione}}/g, post.Descrizione)

        // Create a container for the post
        const postContainer = document.createElement('div');
        postContainer.className = 'post-container';
        postContainer.setAttribute('id', post.IdPost)
        postContainer.innerHTML = postHeader;

        // Locate the 'egg-body' class container
        const eggBody = postContainer.querySelector('.egg-body');
        if (!eggBody) {
            console.error("Could not find 'egg-body' in the template.");
            return;
        }

        // Fetch and process photos for the post
        const photos = await fetchPhotosForPost(post.IdPost);
        const carouselHtml = populateCarousel(templateBody, photos);

        // Add the carousel inside the egg-body container
        eggBody.innerHTML += carouselHtml;

        // Append the complete post to the results container
        container.appendChild(postContainer);
    } catch (error) {
        console.error('Error displaying post:', post, error);
    }
}

async function showComments(element) {
    try {
        const container = document.getElementById('LeftContainer');
        const template = await fetchTemplate('frontend/comments.html');


        container.innerHTML = '';
        const postHeader = template.replace(/{{IdPost}}/g, element);

        const postContainer = document.createElement('div');
        postContainer.className = 'comment-container';
        postContainer.setAttribute('id', element)
        postContainer.innerHTML = postHeader;

        container.appendChild(postContainer);
    } catch (error) {
        console.error('Error displaying post:', error);
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

function addRemoveLike(IdPost) {
    if (!IdPost.length) return;

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

function likeAction(event) {
    const button = event.target;
    const action = button.getAttribute('data-post');
    addRemoveFriend(username, action)
}


function setupIntersectionObserver() {
    const container = document.getElementById("LetTheEggsSwim");
    if (!container) {
        console.error("'LetTheEggsSwim' container not found");
        return;
    }

    const options = {
        root: container,
        rootMargin: "0px",
        threshold: 1,
    };

    console.log("Observer Root:", container.getBoundingClientRect());

    const callback = (entries) => {
        entries.forEach((entry) => {
            const element = entry.target.getAttribute("id");
            if (entry.isIntersecting == true && !element.includes("carousel")) {
                showComments(element);
                fetchPostComments(element);
            }
        });
    };

    observer = new IntersectionObserver(callback, options);

    const mutationObserver = new MutationObserver(() => {
        observeNewPosts(container);
    });

    mutationObserver.observe(container, { childList: true, subtree: true });

    observeNewPosts(container);
}

function observeNewPosts(container) {
    const newPosts = container.querySelectorAll("*[id]:not([data-observed])");
    newPosts.forEach((post) => {
        observer.observe(post);
        post.setAttribute("data-observed", "true");
    });
}