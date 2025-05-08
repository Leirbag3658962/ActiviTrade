function toggleReplyForm(replyId) {
    const form = document.getElementById('reply-form-' + replyId);
    if (form) {
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    } else {
        console.error("L'élément avec l'ID reply-form-" + replyId + " n'a pas été trouvé");
    }
}
