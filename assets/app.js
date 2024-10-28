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
    // Gestionnaire de filtre des annonces sur linstings
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

    // Gestionnaire des option du profil utisateur
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
});



