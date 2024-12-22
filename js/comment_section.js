async function fetchPostComments(IdPost) {
    console.log("Loading comments for post:", IdPost);
    try {
        const response = await fetch(`backend/getComments.php?term=getComments&IdPost=${encodeURIComponent(IdPost)}`);
        if (!response.ok) throw new Error("Network response was not ok");

        const data = await response.json(); // Automatically parsed JSON
        console.log("Fetched comments:", data);

        const commentsSection = document.getElementById("comments-section");
        if (!commentsSection) {
            console.error("Comments section not found!");
            return;
        }

        renderComments(data, commentsSection);
    } catch (error) {
        console.error("Error fetching posts:", error);
    }
}

async function renderComments(comments, parentElement, IdParent = null, level = 0) {
    comments
        .filter(comment => comment.IdParent === IdParent) // Get comments with the current IdParent
        .forEach(comment => {
            const hasReplies = comments.some(c => c.IdParent === comment.IdCommento); // Check if comment has replies
            const commentElement = createCommentElement(comment, hasReplies, level); // Pass the level to the element

            parentElement.appendChild(commentElement);

            // Render replies
            const repliesContainer = commentElement.querySelector(`#replies-${comment.IdCommento}`);
            renderComments(comments, repliesContainer, comment.IdCommento, level + 1); // Increase the level for replies
        });
}
// Function to create a comment element
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

// Submit comment handler
function submitComment() {
    const commentInput = document.getElementById("comment-input");
    const commentsSection = document.getElementById("comments-section");

    if (!commentInput || !commentsSection) {
        console.error("Required elements are missing!");
        return;
    }

    const content = commentInput.value.trim();
    if (!content) {
        alert("Please write a comment before submitting.");
        return;
    }

    // Add new comment logic here
    console.log("New comment submitted:", content);
    commentInput.value = ""; // Clear input
}

// Reply to a comment
function replyToComment(commentId) {
    console.log(`Replying to comment with ID: ${commentId}`);
    // Implement reply logic here
}

// Toggle replies visibility
function toggleReplies(commentId) {
    const repliesContainer = document.getElementById(`replies-${commentId}`);
    if (repliesContainer) {
        repliesContainer.classList.toggle("hidden");
    }
}

function formatTimestamp(timestamp) {
    const now = new Date();
    const postTime = new Date(timestamp);
    const diffInSeconds = Math.floor((now - postTime) / 1000);

    if (diffInSeconds < 60) {
        return `${diffInSeconds}s`;
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes}m`;
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours}h`;
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days}d`;
    }
}