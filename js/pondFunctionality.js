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

function checkLikes(IdPost) {
    fetch(`backend/checkLikes.php?IdPost=${encodeURIComponent(IdPost)}`)
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
        return data.LikeStatus
    })
    .catch(error => console.error(`Error fetching ${type.toLowerCase()}:`, error));
}

async function displayPost(post, container, templateHead, templateBody) {
    try {
        const formattedTimestamp = formatTimestamp(post.TimestampPubblicazione);
        const locationString = formatLocation(post.Citta, post.Provincia, post.Regione);

        console.log('Displaying post:', post.IdPost);
        console.log('Description:', post.testo);

        const LikeStatus = checkLikes(post.IdPost);

        const parser = new DOMParser();
        const PostHead = parser.parseFromString(templateHead, 'text/html');

        // if (LikeStatus == true) {
        //     PostHead.getElementById('unlike-button').style.display = 'none';
        // } else {
        //     PostHead.getElementById('like-button').style.display = 'none';
        // }
        
        const usernameElement = PostHead.getElementById("PostUsername");
        if (usernameElement) {
            usernameElement.innerText = post.Username;
        }

        const profileImage = PostHead.getElementsByClassName("PostProfileImage")[0];
        if (profileImage) {
            profileImage.src = post.PosizioneFileSystemFotoProf;
        }

        const locationElement = PostHead.getElementById("PostLocation");
        if (locationElement) {
            locationElement.innerText = locationString;
        }

        const timestampElement = PostHead.getElementById("PostTimestamp");
        if (timestampElement) {
            timestampElement.innerText = formattedTimestamp;
        }

        const unlikeButton = PostHead.getElementById("unlike-button");
        if (unlikeButton) {
            unlikeButton.setAttribute('data-idPost', post.IdPost);
            unlikeButton.style.display = LikeStatus ? 'inline' : 'none';
        }

        const likeButton = PostHead.getElementById("like-button");
        if (likeButton) {
            likeButton.setAttribute('data-idPost', post.IdPost);
            likeButton.style.display = LikeStatus ? 'none' : 'inline';
        }

        const postTextElement = PostHead.getElementById("PostTesto");
        if (postTextElement) {
            postTextElement.innerText = post.testo;
        }

        // var image = document.getElementsByClassName("PostProfileImage");
        // image.src = post.PosizioneFileSystem;
        // PostHead.getElementById("PostLocation").innerText = locationString;
        // PostHead.getElementById("PostTimestamp").innerText = formattedTimestamp;
        // PostHead.getElementById("unlike-button").setAttribute('data-idPost', post.IdPost);
        // PostHead.getElementById("like-button").setAttribute('data-idPost', post.IdPost);
        // PostHead.getElementById("PostTesto").innerText = post.testo;

        // Create a container for the post
        const postContainer = document.createElement('div');
        postContainer.className = 'post-container m-2';
        postContainer.setAttribute('id', post.IdPost)
        postContainer.innerHTML = PostHead;

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

function formatLocation(Citta, Provincia, Regione) {
    var locationString = "";
    if (Citta != null) {
        locationString = locationString + Citta + ",";
    }

    if (Citta != null) {
        locationString = locationString + Provincia + ",";
    }

    if (Citta != null) {
        locationString = locationString + Regione;
    }

    return locationString;
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
                <img src="${photo.PosizioneFileSystem}" class="d-block w-100 rounded" alt="Slide ${index + 1}">
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

async function addRemoveLike(event) {
    try {
        const button = event.target;
        const action = $(event).data('action');
        const IdPost = $(event).data('idpost');

        console.log(this);

        console.log('Adding/removing like:', IdPost, action);

        const response = await fetch(`backend/addRemoveLike.php?IdPost=${encodeURIComponent(IdPost)}&action=${encodeURIComponent(action)}`);
        
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        console.log('Like added/removed:', data);
        event.style.display = 'none';
        if (data.status = 'success') {
           if (action == 'AddLike') {
                event.nextElementSibling.style.display = 'inline';
              } else {  
                event.previousElementSibling.style.display= 'inline';
              }
        }
    } catch (error) {
        console.error('Error adding/removing like:', error);
    }
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