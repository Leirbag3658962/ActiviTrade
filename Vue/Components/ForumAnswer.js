function toggleReplyForm(formId) {
    var form = document.getElementById("reply-form-" + formId);
    if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block";  
    } else {
        form.style.display = "none";  
    }
}
