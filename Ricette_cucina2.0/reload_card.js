document.addEventListener('DOMContentLoaded', function () {
  console.log('Document ready');  // Verifica che il documento sia pronto

  // Gestisce il click sul bottone "Nuove Ricette"
  document.querySelector('#reload-cards-button').addEventListener('click', function () {
    console.log('Reload button clicked');
    fetch('ricetta_random.php', {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
      })
      .then(data => {
        console.log('Data received:', data);
        const cardDeck = document.querySelector('.card-deck');
        cardDeck.innerHTML = ''; 

        // Verifica che ci siano almeno 3 ricette e mostralo
        if (data.recipes && data.recipes.length >= 3) {
          data.recipes.slice(0, 3).forEach(ricetta => {  // Mostra al massimo 3 ricette
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
              <img class="card-img-top" src="${ricetta.image_url}" alt="Card image cap">
              <div class="card-body">
                <h5 class="card-title">${ricetta.nome}</h5>
                <p class="card-text">${ricetta.descrizione}</p>
                <p class="card-text"><small class="text-muted">Tempo di preparazione: ${ricetta.tempo_di_preparazione} minuti</small></p>
              </div>
            `;
            cardDeck.appendChild(card);
          });
        } else {
          console.error('Errore: meno di 3 ricette trovate.');
        }
      })
      .catch(error => console.error('Errore nel caricamento delle ricette:', error));
  });
});
