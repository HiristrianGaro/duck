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

async function checkLikes(IdPost) {
    return fetch(`backend/checkLikes.php?IdPost=${encodeURIComponent(IdPost)}`)
        .then(response => {
            if (!response.ok) {
                console.error(`Failed response for IdPost ${IdPost}:`, response);
                throw new Error(`Network response for IdPost ${IdPost} was not ok`);
            }
            return response.json();
        })
        .then(data => {
            console.log(`Fetched LikeStatus data for IdPost ${IdPost}:`, data);
            if (data[0].LikeStatus == 'true') {
                return true;
            }
            return false;
        })
        .catch(error => {
            console.error(`Error fetching LikeStatus for IdPost ${IdPost}:`, error);
        });
}



async function displayPost(post, container, templateHead, templateBody) {
    try {
        const formattedTimestamp = formatTimestamp(post.TimestampPubblicazione);
        const locationString = formatLocation(post.Citta, post.Provincia, post.Regione);

        console.log('Displaying post:', post.IdPost);
        console.log('Description:', post.testo);

        const LikeStatus = await checkLikes(post.IdPost);

        const parser = new DOMParser();
        const PostHead = parser.parseFromString(templateHead, 'text/html');

        
        const usernameElement = PostHead.getElementById("PostUsername");
        if (usernameElement) {
            usernameElement.innerText = post.Username;
            usernameElement.addEventListener('click', event => {
                event.preventDefault();
                navigateToUserProfile(post.Username);
            });
        }

        const profileImage = PostHead.getElementById("PostProfileImage");
        if (profileImage) {
            profileImage.src = post.PosizioneFileSystemFotoProf;
            profileImage.addEventListener('click', event => {
                event.preventDefault();
                navigateToUserProfile(post.Username);
            });
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
            if (LikeStatus == 'true') {
                unlikeButton.style.display = 'inline';
                likeButton.style.display = 'none';
            }
            unlikeButton.style.display = LikeStatus ? 'inline' : 'none';
        }

        const likeButton = PostHead.getElementById("like-button");
        if (likeButton) {
            likeButton.setAttribute('data-idPost', post.IdPost);
            if (LikeStatus == 'false') {
                unlikeButton.style.display = 'inline';
                likeButton.style.display = 'none';
            }
            likeButton.style.display = LikeStatus ? 'none' : 'inline';
        }

        const commentButton = PostHead.getElementById("comment-button");

        if (commentButton) {
            commentButton.setAttribute('data-idPost', post.IdPost);
        }

        const postTextElement = PostHead.getElementById("PostTesto");
        if (postTextElement) {
            postTextElement.innerText = post.testo;
        }

        
        if (checkAdmin()) {
            const blockbuttonhtml = '<button type="button" class="post-btn"><i class="bi bi-ban" style="color: red;"></i></i></button>';
            const blockbtn = PostHead.getElementById("blockbtn");
            blockbtn.innerHTML = blockbuttonhtml;
        }


        // Create a container for the post
        const postContainer = document.createElement('div');
        postContainer.className = 'post-container m-2';
        postContainer.setAttribute('data-PID', post.IdPost);


        // Locate the 'egg-body' class container
        const eggbody = PostHead.getElementById('egg-body'); // Assuming 'egg-body' is the ID
        if (!eggbody) {
            console.error("Could not find 'egg-body' in the template.");
            return;
        }

        // Fetch and process photos for the post
        const photos = await fetchPhotosForPost(post.IdPost);
        console.log(JSON.stringify(photos));
        const carouselHtml = populateCarousel(templateBody, photos);


        // Add the carousel inside the egg-body container
        eggbody.innerHTML += carouselHtml;

        postContainer.innerHTML = PostHead.body.innerHTML;

        // Append the complete post to the results container
        container.appendChild(postContainer);
    } catch (error) {
        console.error('Error displaying post:', post, error);
    }
}

async function checkAdmin() {
    try {
        const response = await fetch('backend/checkAdmin.php');
        if (!response.ok) throw new Error('Network response was not ok');

        const data = await response.json();
        console.log('hello!', data);
        if (data[0].IsAdmin == '1') {
            return true;
        }
        return false;

    } catch (error) {
        console.error('Error checking admin status:', error);
    }
}

async function showComments(element) {
    try {
        const modal = document.createElement('div');
        modal.classList.add('modal');
        modal.id = 'modal';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('idPost', element);
        modal.hidden = true;
        const template = await fetchTemplate('frontend/comments.html');


        modal.innerHTML = '';

        const postContainer = document.createElement('div');
        postContainer.className = 'comment-container';
        postContainer.setAttribute('id', element)
        postContainer.innerHTML = modal;

        modal.appendChild(postContainer);
        modal.querySelector('.close').addEventListener('click', () => { modal.hidden = true; });
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


function openComments(element) {
    const postId = element.getAttribute('data-idPost');
    console.log('Opening comments for post:', postId);
    showComments(postId);
    fetchPostComments(postId)
}





// function setupIntersectionObserver() {
//     const container = document.getElementById("LetTheEggsSwim");
//     if (!container) {
//         console.error("'LetTheEggsSwim' container not found");
//         return;
//     }

//     const options = {
//         root: container,
//         rootMargin: "0px",
//         threshold: 1,
//     };

//     console.log("Observer Root:", container.getBoundingClientRect());

//     const callback = (entries) => {
//         entries.forEach((entry) => {
//             const element = entry.target.getAttribute("data-PID");
//             if (entry.isIntersecting && element && !element.includes("carousel")) {
//                 console.log('siamo dentro');
                // showComments(element);
                // fetchPostComments(element);
//             }
//         });
//     };

//     observer = new IntersectionObserver(callback, options);

//     const mutationObserver = new MutationObserver(() => {
//         observeNewPosts(container);
//     });

//     mutationObserver.observe(container, { childList: true, subtree: true });

//     observeNewPosts(container);
// }

// function observeNewPosts(container) {
//     const newPosts = container.querySelectorAll("*[id]:not([data-observed])");
//     newPosts.forEach((post) => {
//         observer.observe(post);
//         post.setAttribute("data-observed", "true");
//     });
// }