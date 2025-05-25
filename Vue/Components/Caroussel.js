document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour initialiser UN carrousel spécifique
    function initActivityCarousel(carouselElement) {
        const inner = carouselElement.querySelector('.activity-carousel-inner');
        const items = carouselElement.querySelectorAll('.activity-carousel-item');
        const prevBtn = carouselElement.querySelector('.activity-prev');
        const nextBtn = carouselElement.querySelector('.activity-next');
        let currentIndex = 0;

        if (!inner || items.length <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
            if (items.length === 1 && items[0]) items[0].style.display = 'block';
            return;
        }
        
        if (prevBtn) prevBtn.style.display = 'block';
        if (nextBtn) nextBtn.style.display = 'block';


        function showSlide(index) {

            items.forEach(item => item.classList.remove('active')); 
            items[index].classList.add('active'); 
            currentIndex = index;
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                let newIndex = currentIndex - 1;
                if (newIndex < 0) {
                    newIndex = items.length - 1;
                }
                showSlide(newIndex);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                let newIndex = currentIndex + 1;
                if (newIndex >= items.length) {
                    newIndex = 0;
                }
                showSlide(newIndex);
            });
        }
        showSlide(0); 
    }

    
    const activityCarousels = document.querySelectorAll('.activity-carousel');
    activityCarousels.forEach(carousel => {
        initActivityCarousel(carousel);
    });

    // Si votre contenu est chargé via AJAX (comme avec chargerTable),
    // vous devrez ré-appeler l'initialisation des carrousels après que
    // le nouveau HTML soit inséré dans le DOM.
    // Par exemple, dans le .then(html => { ... }) de votre fonction chargerTable :
    //    containerElement.innerHTML = html;
    //    const newCarousels = containerElement.querySelectorAll('.activity-carousel');
    //    newCarousels.forEach(carousel => initActivityCarousel(carousel));
});