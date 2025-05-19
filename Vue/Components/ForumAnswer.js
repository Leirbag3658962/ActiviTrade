document.addEventListener("DOMContentLoaded", function () {
    const replyButton = document.querySelector(".reply-button");
    const replyForm = document.getElementById("reply-form-main");

    if (replyButton) {
        replyButton.addEventListener("click", function () {
            if (window.isLoggedIn) {
                if (replyForm.style.display === "none") {
                    replyForm.style.display = "block";
                } else {
                    replyForm.style.display = "none";
                }
            } else {
                window.location.href = "LogIn.php";
            }
        });
    }
});
