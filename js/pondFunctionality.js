(function (global) {
  let postsLoaded = false;

  
  async function initPond() {
    const container = document.getElementById("LetTheEggsSwim");
    if (!container) return;
    if (!postsLoaded) {
      await fetchFriendsPosts();
      postsLoaded = true;
    }
    setupIntersectionObserver();
  }

  
  global.initPond = initPond;
})(window);

async function fetchFriendsPosts() {
  try {
    const response = await fetch("backend/getPosts.php?term=Friends");
    if (!response.ok) throw new Error("Network response was not ok");

    const data = await response.json();
    if (!Array.isArray(data)) {
      
      console.warn("fetchFriendsPosts: risposta non valida", data);
      return;
    }

    const resultsDiv = document.getElementById("LetTheEggsSwim");

   
    const [templateHead, templateBody] = await Promise.all([
      fetchTemplate("frontend/items/egg-head.html"),
      fetchTemplate("frontend/items/egg-body.html"),
    ]);

    // Display all posts
    await Promise.all(
      data.map((post) =>
        displayPost(post, resultsDiv, templateHead, templateBody)
      )
    );
  } catch (error) {
    console.error("Error fetching posts:", error);
  }
}

async function fetchPhotosForPost(IdPost) {
  try {
    const response = await fetch(
      `backend/getPostFoto.php?IdPost=${encodeURIComponent(IdPost)}`
    );
    if (!response.ok) throw new Error("Network response was not ok");
    return await response.json();
  } catch (error) {
    console.error("Error fetching photos for post:", error);
    return [];
  }
}

async function checkLikes(IdPost) {
  return fetch(`backend/checkLikes.php?IdPost=${encodeURIComponent(IdPost)}`)
    .then((response) => {
      if (!response.ok) {
        console.error(`Failed response for IdPost ${IdPost}:`, response);
        throw new Error(`Network response for IdPost ${IdPost} was not ok`);
      }
      return response.json();
    })
    .then((data) => {
      console.log(`Fetched LikeStatus data for IdPost ${IdPost}:`, data);
      if (data[0].LikeStatus == "true") {
        return true;
      }
      return false;
    })
    .catch((error) => {
      console.error(`Error fetching LikeStatus for IdPost ${IdPost}:`, error);
    });
}
window.formatLocation = function(Citta, Provincia, Regione) {
    console.log("controllo variabili", Citta, Provincia, Regione);
    return `${Citta} - ${Provincia} - ${Regione}`;
  };

async function displayPost(post, container, templateHead, templateBody) {
  try {
    const formattedTimestamp = formatTimestamp(post.TimestampPubblicazione);
    const locationString = formatLocation(
      post.PostCitta,
      post.PostProvincia,
      post.PostRegione
    );

    console.log("Displaying post:", post.IdPost);
    console.log("Displaying location", locationString);
    console.log("Description:", post.testo);

    const LikeStatus = await checkLikes(post.IdPost);

    const parser = new DOMParser();
    const PostHead = parser.parseFromString(templateHead, "text/html");

    const usernameElement = PostHead.getElementById("PostUsername");
    if (usernameElement) {
      usernameElement.innerText = post.Username;
    }

    const profileImage = PostHead.getElementById("PostProfileImage");
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
      unlikeButton.setAttribute("data-idPost", post.IdPost);
      if (LikeStatus == "true") {
        unlikeButton.style.display = "inline";
        likeButton.style.display = "none";
      }
      unlikeButton.style.display = LikeStatus ? "inline" : "none";
    }

    const likeButton = PostHead.getElementById("like-button");
    if (likeButton) {
      likeButton.setAttribute("data-idPost", post.IdPost);
      if (LikeStatus == "false") {
        unlikeButton.style.display = "inline";
        likeButton.style.display = "none";
      }
      likeButton.style.display = LikeStatus ? "none" : "inline";
    }

    const postTextElement = PostHead.getElementById("PostTesto");
    if (postTextElement) {
      postTextElement.innerText = post.testo;
    }

    // Create a container for the post
    const postContainer = document.createElement("div");
    postContainer.className = "post-container m-2";
    postContainer.setAttribute("data-PID", post.IdPost);

    // Create comment section
    const commentsSection = PostHead.querySelector(".comments-section");
    if (commentsSection) {
      commentsSection.setAttribute("data-post-id", post.IdPost);
    }

    // Locate the 'egg-body' class container
    const eggbody = PostHead.getElementById("egg-body"); 
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

    await showCommentsForPost(post.IdPost);
  } catch (error) {
    console.error("Error displaying post:", post, error);
  }
}

// Function to populate the carousel with dynamic photos
function populateCarousel(templateBody, photos) {
  const carouselId = `carousel-${Math.random().toString(36).substring(2, 15)}`;
  const parser = new DOMParser();
  const carouselDocument = parser.parseFromString(templateBody, "text/html");
  const carouselElement = carouselDocument.body.firstElementChild;

  // Update carousel ID for uniqueness
  carouselElement.id = carouselId;

  // Populate indicators
  const indicators = carouselElement.querySelector(".carousel-indicators");
  indicators.innerHTML = photos
    .map(
      (_, index) =>
        `<button type="button" data-bs-target="#${carouselId}" data-bs-slide-to="${index}" class="${
          index === 0 ? "active" : ""
        }" aria-label="Slide ${index + 1}"></button>`
    )
    .join("");

  // Populate carousel-inner with images
  const carouselInner = carouselElement.querySelector(".carousel-inner");
  carouselInner.innerHTML = photos
    .map(
      (photo, index) =>
        `<div class="carousel-item ${index === 0 ? "active" : ""}">
                <img src="${
                  photo.PosizioneFileSystem
                }" class="d-block w-100 rounded" alt="Slide ${index + 1}">
            </div>`
    )
    .join("");

  // Update the navigation buttons to use the unique carouselId
  const prevButton = carouselElement.querySelector(".carousel-control-prev");
  const nextButton = carouselElement.querySelector(".carousel-control-next");
  prevButton.setAttribute("data-bs-target", `#${carouselId}`);
  nextButton.setAttribute("data-bs-target", `#${carouselId}`);

  if (photos.length === 1) {
    // If only one photo, hide indicators and controls
    const indicators = carouselElement.querySelector(".carousel-indicators");
    const prevButton = carouselElement.querySelector(".carousel-control-prev");
    const nextButton = carouselElement.querySelector(".carousel-control-next");

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
    const action = $(event).data("action");
    const IdPost = $(event).data("idpost");

    console.log(this);

    console.log("Adding/removing like:", IdPost, action);

    const response = await fetch(
      `backend/addRemoveLike.php?IdPost=${encodeURIComponent(
        IdPost
      )}&action=${encodeURIComponent(action)}`
    );

    if (!response.ok) throw new Error("Network response was not ok");

    const data = await response.json();
    console.log("Like added/removed:", data);
    event.style.display = "none";
    if ((data.status = "success")) {
      if (action == "AddLike") {
        event.nextElementSibling.style.display = "inline";
      } else {
        event.previousElementSibling.style.display = "inline";
      }
    }
  } catch (error) {
    console.error("Error adding/removing like:", error);
  }
}

function setupIntersectionObserver() {
  const container = document.getElementById("LetTheEggsSwim");
  if (!container) {
    console.error("'LetTheEggsSwim' container not found");
    return;
  }

  const options = {
    root: null, // Osserva rispetto alla viewport
    rootMargin: "0px 0px 200px 0px", // Carica i commenti prima che il post sia completamente visibile
    threshold: 0.2, // Attiva quando almeno il 20% è visibile
  };

  console.log("Observer Root:", container.getBoundingClientRect());

  const callback = (entries) => {
    entries.forEach((entry) => {
      const element = entry.target.getAttribute("data-PID");
      console.log(
        `Osservando elemento con PID: ${element}, intersezione: ${entry.isIntersecting}`
      );

      if (entry.isIntersecting && element && !element.includes("carousel")) {
        console.log(`Caricamento commenti per post ${element}`);
        showCommentsForPost(element);
        observer.unobserve(entry.target); // Evita chiamate multiple
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
  if (postsLoaded) {
    console.log("Post già caricati, osservando solo per i commenti");
    const newPosts = container.querySelectorAll(
      ".post-container:not(.observed)"
    );

    newPosts.forEach((post) => {
      observer.observe(post);
      post.classList.add("observed");
    });
  }
}

// Funzione per verificare se l'utente ha messo like a un commento
async function checkCommentsLike(IdCommento) {
  try {
    const response = await fetch(
      `backend/checkCommentLikes.php?IdCommento=${encodeURIComponent(
        IdCommento
      )}`
    );

    if (!response.ok) {
      console.error(`Failed response for IdCommento ${IdCommento}:`, response);
      throw new Error(
        `Network response for IdCommento ${IdCommento} was not ok`
      );
    }

    const data = await response.json();
    console.log(
      `Fetched CommentLikeStatus for IdCommento ${IdCommento}:`,
      data
    );

    if (data[0] && data[0].LikeStatus === "true") {
      return true;
    }
    return false;
  } catch (error) {
    console.error(
      `Error fetching comment like status for IdCommento ${IdCommento}:`,
      error
    );
    return false;
  }
}

// Funzione per visualizzare i commenti
async function displayComments(comments, containerElement) {
  if (!comments || !comments.length) {
    console.log("No comments to display");
    containerElement.innerHTML =
      '<p class="text-muted">Nessun commento ancora. Sii il primo a commentare!</p>';
    return;
  }

  try {
    // Carica il template una sola volta
    const commentTemplate = await fetchTemplate("frontend/items/comments.html");

    // Svuota il container dei commenti
    containerElement.innerHTML = "";

    // Elabora ogni commento ed aggiungilo al container
    for (const comment of comments) {
      // Verifica se l'utente ha messo like a questo commento
      const hasLiked = await checkCommentsLike(comment.IdCommento);

      // Formatta il timestamp
      const formattedTimestamp = formatTimestamp(comment.TimestampCommento);

      // Sostituisci i segnaposto nel template con i dati reali del commento
      let commentHtml = commentTemplate
        .replace(/{{comment_id}}/g, comment.IdCommento)
        .replace(
          /{{comment_profile_image_url}}/g,
          comment.PosizioneFileSystemFotoProf
        )
        .replace(/{{comment_username}}/g, comment.Username)
        .replace(/{{comment_timestamp}}/g, formattedTimestamp)
        .replace(/{{comment_text}}/g, comment.Testo)
        .replace(/{{comment_likes_count}}/g, comment.comment_likes_count || 0);

      // Crea un elemento temporaneo per manipolare il DOM del commento
      const tempElement = document.createElement("div");
      tempElement.innerHTML = commentHtml;

      // Gestisci lo stato del pulsante like in base alla risposta di checkCommentsLike
      const likeButton = tempElement.querySelector(".comment-like-button");
      if (likeButton) {
        const likeIcon = likeButton.querySelector("i");
        if (hasLiked) {
          likeIcon.className = "bi bi-heart-fill text-danger";
          likeButton.setAttribute("data-action", "RemoveLike");
        } else {
          likeIcon.className = "bi bi-heart";
          likeButton.setAttribute("data-action", "AddLike");
        }
      }

      // Aggiungi il commento al container
      containerElement.appendChild(tempElement.firstElementChild);
    }
  } catch (error) {
    console.error("Error displaying comments:", error);
    containerElement.innerHTML =
      '<p class="text-danger">Errore nel caricamento dei commenti.</p>';
  }
}

async function showCommentsForPost(IdPost) {
  const commentsContainer = document.querySelector(
    `[data-PID="${IdPost}"] .comments-list`
  );

  if (!commentsContainer) {
    console.error(`Comments container not found for post ${IdPost}`);
    return;
  }

  commentsContainer.innerHTML =
    '<p class="text-center">Caricamento commenti...</p>';

  try {
    const response = await fetch(
      `backend/getComments.php?IdPost=${encodeURIComponent(IdPost)}`
    );
    if (!response.ok) throw new Error("Network response was not ok");

    const comments = await response.json();
    await displayComments(comments, commentsContainer);
  } catch (error) {
    console.error(`Error fetching comments for post ${IdPost}:`, error);
    commentsContainer.innerHTML =
      '<p class="text-danger">Errore nel caricamento dei commenti.</p>';
  }
}

// Gestione invio commenti con struttura HTML attuale
$(document).on("click", ".send-comment-button", async function () {
  const button = $(this);
  const commentInput = button.closest(".input-group").find(".comment-input");
  const commentText = commentInput.val().trim();
  const postContainer = button.closest(".post-container");
  const IdPost = postContainer.attr("data-PID");

  if (!commentText) return;

  button.prop("disabled", true);
  button.html(
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
  );

  try {
    const response = await fetch("backend/addComment.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        IdPost: IdPost,
        commentText: commentText,
      }),
    });

    if (!response.ok) throw new Error("Errore nella risposta del server");

    const result = await response.json();

    if (result.status === "success") {
      commentInput.val("");
      await showCommentsForPost(IdPost);
      console.log("Commento aggiunto con successo!");
    } else {
      console.error("Errore:", result.message);
    }
  } catch (error) {
    console.error("Errore nell'invio del commento:", error);
  } finally {
    button.prop("disabled", false);
    button.text("Invia");
  }
});
