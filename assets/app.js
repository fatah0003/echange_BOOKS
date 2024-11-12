import './bootstrap.js';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/app.css';
// import './styles/form.css';
// import './styles/card.css';
// import './styles/book.css';
// import './styles/notification.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener("DOMContentLoaded", function() {
    // Gestionnaire de filtre des annonces sur les listings
    const filterSvgIcon = document.querySelector(".svg-icon svg");
    const filterListings = document.querySelector(".filter-listings");

    if (filterSvgIcon && filterListings) {
        filterSvgIcon.addEventListener("click", function(event) {
            event.stopPropagation(); // Empêche la propagation du clic vers les autres éléments
            filterListings.classList.toggle("active");
            console.log("SVG de filtrage cliqué, options de filtrage basculées");
        });
    } else {
        if (!filterSvgIcon) {
            console.log("SVG de filtrage introuvable");
        }
        if (!filterListings) {
            console.log(".filter-listings introuvable");
        }
    }

    // Gestionnaire des options du profil utilisateur
    const profilSvgIcon = document.querySelector(".svg-icon-option svg");
    const profilOptions = document.querySelector(".profil-options");

    if (profilSvgIcon && profilOptions) {
        profilSvgIcon.addEventListener("click", function(event) {
            event.stopPropagation(); // Empêche la propagation du clic vers les autres éléments
            profilOptions.classList.toggle("active");
            console.log("SVG de profil cliqué, menu de profil basculé");
        });
    } else {
        if (!profilSvgIcon) {
            console.log("SVG de profil introuvable");
        }
        if (!profilOptions) {
            console.log(".profil-options introuvable");
        }
    }

// Gestionnaire de la bannière de cookies
const cookieBanner = document.getElementById('cookie-banner');
const acceptButton = document.getElementById('accept-cookies');
const rejectButton = document.getElementById('reject-cookies');

// Afficher la bannière si aucun choix n'a été fait
if (!localStorage.getItem('cookies-accepted') && !localStorage.getItem('cookies-rejected')) {
    cookieBanner.style.display = 'block';
}

// Gestion du clic sur le bouton Accepter
acceptButton.addEventListener('click', function () {
    localStorage.setItem('cookies-accepted', 'true');
    cookieBanner.style.display = 'none';
    enableCookies(); // Activer les cookies non essentiels
});

// Gestion du clic sur le bouton Refuser
rejectButton.addEventListener('click', function () {
    localStorage.setItem('cookies-rejected', 'true');
    cookieBanner.style.display = 'none';
    disableCookies(); // Désactiver les cookies non essentiels si nécessaires
});

// Fonction pour activer les cookies non essentiels
function enableCookies() {
    
}

// Fonction pour désactiver les cookies non essentiels
function disableCookies() {
    
}


});
