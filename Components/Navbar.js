//document.addEventListener("DOMContentLoaded", function () {
function Navbar() {

    return `
    <nav class="navbar">
        <a href="#" class="nav-icon" aria-label="homepage" aria-current="page">
            <img src="../img/logo.png" alt="chat icon" />
        </a>

        <div class="main-navlinks">
            <button type="button" class="hamburger"  aria-label="Toggle Navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="navlinks-container">
            <a href="#" aria-current="page">Home</a>
            <a href="#">Services</a>
            <a href="#">Pricing</a>
            <a href="#">Community</a>
            <a href="#">Contact</a>
            </div>
        </div>

        <div class="nav-authentication">
            <a href="#" class="user-toggler" aria-label="Sign in page">
            <img src="../img/user.svg" alt="user icon" />
            </a>
            <div class="sign-btns">
            <button type="button">Sign In</button>
            <button type="button">Sign Up</button>
            </div>
        </div>
    </nav>
    `;
};
