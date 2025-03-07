document.addEventListener("DOMContentLoaded", function () {
    let navbar = document.getElementById("navbar");

    navbar.innerHTML = `
        <ul>
            <li><a href="">Activit&eacutes</a></li>
            <li><a href="">Cr&eacuteation</a></li>
            <li><a href="">Mon Compte</a></li>
            <li><a href="">FAQ</a></li>
            <li><input type="text"></li>
        </ul>
    `;
});