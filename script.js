document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('loginModal');
    const abrirModalBtns = document.querySelectorAll('.abrirModalLogin');
    const closeBtn = document.querySelector('.close');

    abrirModalBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            modal.style.display = 'block';
        });
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});
