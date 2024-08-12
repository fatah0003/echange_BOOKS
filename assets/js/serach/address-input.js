document.addEventListener('DOMContentLoaded', () => {
    let cityInput = document.querySelector('[data-action=address-input]');
    let showlist = document.querySelector('#showlist');
    let showresults = document.querySelector('#showresults');
    let locationContainer = document.querySelector('#locationContainer');

    let noResult = document.createElement('li');
    noResult.className = "bbb";
    noResult.innerText = "Aucun résultats";

    cityInput.addEventListener('keyup', debounce(function (e) {
        let value = e.target.value;
        if (value.length < 3 || value.length > 200) {
            alert('Between 3 and 200 characters !');
        } else {
            fetch(`https://api-adresse.data.gouv.fr/search/?q=${value}&type=municipality`, {method: 'GET'})
            .then((response) => {
                if (response.status !== 200) {
                    throw new Error('API Error, please try again');
                }
                return response.json();
            })
            .then((body) => {
                let features = body.features;
                showresults.innerHTML = '';
                if (features && features.length > 0) {
                    features.map((feature) => {
                        let li = document.createElement('li');
                        li.className = "aaa";
                        li.innerText = `${feature.properties.name} - ${feature.properties.postcode}`;

                        li.addEventListener('click', () => {
                            cityInput.value = li.innerText;
                            showlist.style.display = "none";
                            showresults.innerHTML = '';
                        });

                        showresults.appendChild(li);
                    });
                    showlist.style.display = "block"; // Afficher la liste si des résultats sont disponibles
                } else {
                    showresults.innerHTML = ''; // Effacer les anciens résultats
                    showresults.appendChild(noResult);
                    showlist.style.display = "block";
                }
            })
            .catch((error) => {
                alert(error.message);
            });
        }
    }, 800));

    document.addEventListener('click', function (event) {
        if (!locationContainer.contains(event.target)) {
            showlist.style.display = "none";
            showresults.innerHTML = '';
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
