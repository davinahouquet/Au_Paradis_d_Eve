// Menu burger
function toggleBurgerMenu() {
    var menu = document.querySelector('nav ul');
    var icon = document.getElementById('burger-icon');

    menu.classList.toggle('active');

    // Change l'icône en fonction de l'état du menu
    if (menu.classList.contains('active')) {
        icon.innerHTML = '<span class="material-symbols-outlined">close</span>';
    } else {
        icon.innerHTML = '<span class="material-symbols-outlined">menu</span>';
    }
}

// Fonction pour faire défiler vers le haut
function scrollToTop() {
    document.body.scrollTop = 0; // Pour les navigateurs Safari
    document.documentElement.scrollTop = 0; // Pour les autres navigateurs
}

// Afficher/masquer le bouton en fonction de la position de défilement
window.onscroll = function() { scrollFunction() };

function scrollFunction() {
    var scrollToTopBtn = document.getElementById("scrollToTopBtn");

    // Afficher le bouton lorsque la page est défilée de 100 pixels ou plus
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollToTopBtn.style.display = "block";
    } else {
        scrollToTopBtn.style.display = "none";
    }
}