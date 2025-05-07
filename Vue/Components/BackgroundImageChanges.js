function changeBackground() {
    var backgrounds = ["../img/poterie.jpg", "../img/peinture.jpg", "../img/randonnee.jpg", "../img/bibliotheque.jpg",
         "../img/canoe.jpg"];
    var randomBackground = backgrounds[Math.floor(Math.random() * backgrounds.length)];
    document.body.style.backgroundImage = "url('" + randomBackground + "')";
}

setInterval(changeBackground, 10000);