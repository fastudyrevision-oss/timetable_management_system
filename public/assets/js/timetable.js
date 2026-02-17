/* public/assets/js/timetable.js */
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.class-card');
    
    cards.forEach(card => {
        card.addEventListener('click', () => {
            const id = card.getAttribute('data-id');
            // Todo: Open modal to edit/rearrange
            alert('Edit slot: ' + id);
        });
    });
});
