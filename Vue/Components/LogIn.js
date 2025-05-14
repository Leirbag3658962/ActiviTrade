const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("showHide");

    toggleIcon.addEventListener("click", function () {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        toggleIcon.src = type === "password" ? "../img/Hide.svg" : "../img/Show.svg";
    });

