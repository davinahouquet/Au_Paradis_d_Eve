// import 'bootstrap-datepicker';
// // Initialisation du datepicker
// $('.datepicker').datepicker();

// Menu burger
function toggleBurgerMenu() {
    var menu = document.querySelector('nav ul');
    var icon = document.getElementById('burger-icon');

    menu.classList.toggle('active');

    // Change l'icône en fonction de l'état du menu
    // if (menu.classList.contains('active')) {
    //     icon.innerHTML = '<span class="material-symbols-outlined">close</span>';
    // } else {
    //     icon.innerHTML = '<span class="material-symbols-outlined">menu</span>';
    // }
}

// Menu user
function toggleUserMenu() {
    var menu = document.querySelector('.user-menu');
    menu.classList.toggle('active');
}

document.addEventListener("DOMContentLoaded", function () {
    var scrollToTopBtn = document.getElementById("scrollToTopBtn");

    // Show or hide the button based on scroll position
    window.onscroll = function () {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollToTopBtn.style.display = "block";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    };

    // Scroll to the top when the button is clicked
    scrollToTopBtn.addEventListener("click", function () {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    });
});  

document.addEventListener('DOMContentLoaded', function() {
    var masquerLiens = document.querySelectorAll('.masquer-evaluation');
    var reAfficherBouton = document.getElementById('reafficher-evaluations');

    masquerLiens.forEach(function(lien) {
        lien.addEventListener('click', function(e) {
            e.preventDefault();
            var evaluationId = this.getAttribute('data-evaluation-id');
            var evaluationDiv = document.getElementById('evaluation_' + evaluationId);

            // Masquer l'évaluation
            if (evaluationDiv) {
                evaluationDiv.classList.add('evaluation-masquee');
            }
        });
    });

    reAfficherBouton.addEventListener('click', function() {
        var evaluationsMasquees = document.querySelectorAll('.evaluation-masquee');
        
        evaluationsMasquees.forEach(function(evaluation) {
            evaluation.classList.remove('evaluation-masquee');
        });
    });
});
// $(document).ready(function() {
//     // you may need to change this code if you are not using Bootstrap Datepicker
//     $('.js-datepicker').datepicker({
//         format: 'yyyy-mm-dd'
//     });
// });

// les messages flashes : durée
$(document).ready(function() {
    // millisecondes
    var duration = 5000;

    $('.flashes .flash-message').delay(duration).fadeOut('slow', function() {
        $(this).remove();
    });
});
