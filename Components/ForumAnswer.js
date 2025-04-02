document.querySelectorAll('.toggle-arrow').forEach(arrow => {
    arrow.addEventListener('click', () => {
        const answer = arrow.parentElement.querySelector('.answer'); 
        if (answer.style.display === 'none' || answer.style.display === '') {
            answer.style.display = 'block'; 
            arrow.innerHTML = '<i class="fas fa-chevron-up"></i>';
        } else {
            answer.style.display = 'none'; 
            arrow.innerHTML = '<i class="fas fa-chevron-down"></i>'; 
        }
    });
});
