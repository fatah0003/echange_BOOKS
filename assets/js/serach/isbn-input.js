document.addEventListener("DOMContentLoaded", () => {
  // Sélection des champs du formulaire par leurs attributs data-action
  let isbnInput = document.querySelector("[data-action=isbn-input]");
  let titleInput = document.querySelector("[data-action=title-input]");
  let authorInput = document.querySelector("[data-action=author-input]");
  let pagesInput = document.querySelector("[data-action=pages-input]");
  let editionInput = document.querySelector("[data-action=edition-input]");

  // Sélection des éléments pour afficher les résultats de recherche
  let showListIsbn = document.querySelector("#showlist-isbn");
  let showResultsIsbn = document.querySelector("#showlist-isbn"); // Même élément que showListIsbn
  let isbnContainer = document.querySelector("#isbnContainer");

  // Crée un élément <li> pour afficher "aucun résultat"
  let noResultIsbn = document.createElement("li");
  noResultIsbn.className = "bbb"; // Ajoute une classe au <li>
  noResultIsbn.innerText = "ISBN ne correspond à aucun livre dans notre base de données";

  // Ajout d'un écouteur d'événement sur le champ ISBN pour détecter les frappes au clavier
  isbnInput.addEventListener(
    "keyup",
    debounce(function (e) {
      let value = e.target.value; // Récupère la valeur entrée par l'utilisateur

      // Vérifie si la longueur de l'ISBN est entre 10 et 13 caractères
      if (value.length < 10 || value.length > 13) {
        alert("Entre 10 et 13 caractères");
      } else {
        // Appel à l'API OpenLibrary pour rechercher le livre par l'ISBN
        fetch(`https://openlibrary.org/search.json?q=${value}`, {
          method: "GET",
        })
          .then((response) => {
            if (response.status !== 200) {
              alert("Erreur API, réessayez"); // Si l'API retourne une erreur
              return null;
            }
            return response.json(); // Convertit la réponse en JSON
          })
          .then((body) => {
            // Si aucun résultat n'est trouvé, affiche un message
            if (!body || !body.docs || body.docs.length === 0) {
              showResultsIsbn.innerHTML = ""; // Efface les résultats précédents
              showResultsIsbn.appendChild(noResultIsbn); // Affiche le message "aucun résultat"
              showListIsbn.style.display = "block"; // Affiche la liste des résultats
              return;
            }

            // Récupère le premier résultat du tableau
            let results = body.docs[0];
            let title = results.title;
            let author = results.author_name ? results.author_name[0] : "Inconnu"; // Si l'auteur est absent
            let pages = results.number_of_pages_median || "Inconnu"; // Si le nombre de pages est absent
            let edition = results.first_publish_year || "Inconnue"; // Si l'année d'édition est absente

            // Crée un élément <li> pour afficher le résultat
            let li = document.createElement("li");
            li.className = "aaa"; // Ajoute une classe au <li>
            li.innerText = `${title} de ${author}`; // Texte affiché dans la liste

            // Lorsqu'un livre est cliqué, remplit les champs du formulaire
            li.addEventListener("click", () => {
              titleInput.value = title; // Remplit le champ "titre"
              authorInput.value = author; // Remplit le champ "auteur"
              pagesInput.value = pages; // Remplit le champ "pages"
              editionInput.value = edition; // Remplit le champ "édition"
              showListIsbn.style.display = "none"; // Cache la liste après sélection
              showResultsIsbn.innerHTML = ""; // Vide la liste des résultats
            });

            // Ajoute le <li> à la liste des résultats
            showResultsIsbn.appendChild(li);
            showListIsbn.style.display = "block"; // Affiche la liste des résultats
          })
          .catch((error) => {
            alert(error.message); // Affiche une alerte si une erreur survient
          });
      }
    }, 1000) // Délai de 1 seconde (1000 ms) avant l'appel à l'API
  );

  // Gère le clic à l'extérieur de la liste pour la cacher
  document.addEventListener("click", function (event) {
    if (!isbnContainer.contains(event.target)) {
      showListIsbn.style.display = "none"; // Cache la liste si clic à l'extérieur
      showResultsIsbn.innerHTML = ""; // Vide la liste des résultats
    }
  });
});

// Fonction de "debounce" pour éviter les appels API trop fréquents
function debounce(callback, delay) {
  let timer;
  return function () {
    let args = arguments; // Arguments de la fonction
    let context = this; // Contexte de la fonction
    clearTimeout(timer); // Réinitialise le timer
    timer = setTimeout(function () {
      callback.apply(context, args); // Exécute la fonction après le délai
    }, delay); // Délai en millisecondes
  };
}
