document.addEventListener("DOMContentLoaded", () => {
  let isbnInput = document.querySelector("[data-action=isbn-input]");
  let titleInput = document.querySelector("[data-action=title-input]");
  let authorInput = document.querySelector("[data-action=author-input]");
  let pagesInput = document.querySelector("[data-action=pages-input]");
  let editionInput = document.querySelector("[data-action=edition-input]");

  // let categoryInput = document.querySelector('[data-action=category-input]');
  let showListIsbn = document.querySelector("#showlist-isbn");
  let showResultsIsbn = document.querySelector("#showlist-isbn");
  let isbnContainer = document.querySelector("#isbnContainer");

  let noResultIsbn = document.createElement("li");
  noResultIsbn.className = "bbb";
  noResultIsbn.innerText = "ISBN ne correspend à aucun livre sur notre base de données";

  isbnInput.addEventListener(
    "keyup",
    debounce(function (e) {
      let value = e.target.value;
      if (value.length < 10 || value.length > 13) {
        alert("Le numéro ISBN doit comporter 10 ou 13 caractères.");

      } else {
        fetch(`https://openlibrary.org/search.json?q=${value}`, {
          method: "GET",
        })
          .then((response) => {
            let status = response.status;
            if (status !== 200) {
              alert("Error API, try again");
            }

            return response.json();
          })
          .then((body) => {
            let results = body.docs[0];
            if (results || body.docs.length > 0) {
              let title = results.title;
              let author = results.author_name[0];
              let pages = results.number_of_pages_median;
              let edition = results.first_publish_year;
              // let category = results.subject[0];

              showResultsIsbn.innerHTML = "";
              showListIsbn.style.display = "block";

              let li = document.createElement("li");
              li.className = "aaa";
              li.innerText = `${title} de ${author}`;
              li.addEventListener("click", () => {
                titleInput.value = title;
                authorInput.value = author;
                pagesInput.value = pages;
                editionInput.value = edition;
                // categoryInput.value = category;
                showListIsbn.style.display = "none";
                showResultsIsbn.innerHTML = "";
              });
              showResultsIsbn.appendChild(li);
            }
          })
          .catch(() => {
            showResultsIsbn.innerHTML = "";
            showResultsIsbn.appendChild(noResultIsbn);
            showListIsbn.style.display = "block";
          });
      }
    }, 1000)
  );

  document.addEventListener("click", function (event) {
    if (!isbnContainer.contains(event.target)) {
      showListIsbn.style.display = "none";
      showResultsIsbn.innerHTML = "";
    }
  });
});

function debounce(callback, delay) {
  let timer;
  return function () {
    let args = arguments;
    let context = this;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, delay);
  };
}
