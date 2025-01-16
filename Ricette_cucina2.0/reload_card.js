document.addEventListener('DOMContentLoaded', function () {
  console.log('Document ready');  // Verifica che il documento sia pronto
  document.querySelector('#reload-cards-button').addEventListener('click', function () {
    console.log('Reload button clicked');  // Verifica che il bottone venga cliccato
    fetch('ricetta_random.php')
      .then(response => {
        console.log('Response received:', response);  // Verifica la risposta
        return response.json();
      })
      .then(data => {
        console.log('Data received:', data);  // Verifica i dati ricevuti
        const cardDeck = document.querySelector('.card-deck');
        cardDeck.innerHTML = ''; // Pulisce il contenuto attuale delle card
        data.randomRecipes.forEach(ricetta => {
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
      })
      .catch(error => console.error('Errore nel caricamento delle ricette:', error)); // Gestisce eventuali errori
  });
});
