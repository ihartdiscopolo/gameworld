function validateForm() {
    const title = document.querySelector('input[name="title"]').value.trim();
    const post = document.querySelector('textarea[name="post"]').value.trim();
    const category = document.querySelector('select[name="category"]').value;

    if (!title || !post || !category) {
        alert("Please fill in all fields.");
        return false;
    }

    if (title.length > 100) {
        alert("Title is too long (max 100 characters).");
        return false;
    }

    return true;
}

function validateCommentForm() {
    const comment = document.querySelector('textarea[name="comment"]').value.trim();

    if (!comment) {
        alert("Please enter a comment.");
        return false;
    }

    if (comment.length > 500) {
        alert("Comment is too long (max 500 characters).");
        return false;
    }

    return true;
}