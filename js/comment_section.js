async function fetchPostComments(IdPost) {
    console.log("Loading comments for post:", IdPost);
    try {
        const response = await fetch(`backend/getComments.php?term=getComments&IdPost=${encodeURIComponent(IdPost)}`);
        if (!response.ok) throw new Error("Network response was not ok");

        const data = await response.json(); // Automatically parsed JSON
        console.log("Fetched comments:", data);

        const commentsSection = document.getElementById("comments-section");
        if (!commentsSection) {
            console.log("Comments section not found!");
            return;
        }

        renderComments(data, commentsSection);
    } catch (error) {
        console.log("Error fetching posts:", error);
    }
}

async function renderComments(comments, parentElement, IdParent = null, level = 0) {
    comments
        .filter(comment => comment.IdParent === IdParent)
        .forEach(comment => {
            const hasReplies = comments.some(c => c.IdParent === comment.IdCommento);
            const commentElement = createCommentElement(comment, hasReplies, level);

            parentElement.appendChild(commentElement);

            // Render replies
            const repliesContainer = commentElement.querySelector(`#replies-${comment.IdCommento}`);
            renderComments(comments, repliesContainer, comment.IdCommento, level + 1);
        });
}
function createCommentElement(comment, hasReplies, level) {
    const commentDiv = document.createElement("div");
    commentDiv.className = "comment";
    commentDiv.setAttribute("data-id", comment.IdCommento);

    commentDiv.innerHTML = `
        <div class='ml-${level}'>
        <div class="comment-header">
            <strong>${comment.Username}</strong> <span>${formatTimestamp(comment.TimestampPubblicazione)}</span>
        </div>
        <div class="comment-body">${comment.Testo}</div>
        <button class="reply-btn btn btn-sm" onClick="replyToComment(${comment.IdCommento})">Reply</button>
        ${hasReplies ? `<button class="toggle-replies-btn btn btn-sm" onClick="toggleReplies(${comment.IdCommento})">View Replies</button>` : ""}
        <div class="replies hidden" id="replies-${comment.IdCommento}"></div>
        </div>
    `;

    return commentDiv;
}


function submitComment() {
    console.log("Submitting comment...");
    const commentInput = document.getElementById("comment-input");
    const commentsSection = document.getElementById("comments-section");

    if (!commentInput || !commentsSection) {
        console.log("Required elements are missing!");
        return;
    }

    const content = commentInput.value.trim();
    if (!content) {
        console.log("Please write a comment before submitting.");
        return;
    }

    //Da implementare la chiamata al backend per l'inserimento del commento
}


function replyToComment(commentId) {
    console.log(`Replying to comment with ID: ${commentId}`);
    //Da implementare la chiamata al backend per l'inserimento del commento al commento
}

function toggleReplies(commentId) {
    const repliesContainer = document.getElementById(`replies-${commentId}`);
    if (repliesContainer) {
        repliesContainer.classList.toggle("hidden");
    }
}

// getElementById("submit-comment").addEventListener("click", submitComment);
