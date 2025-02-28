# Il Gioco delle Api

## Indice

- [Avvio](#avvio)
  - [Creare il Server del Database](#creare-il-server-del-database)
  - [Impostare le Tabelle](#impostare-le-tabelle)
  - [Collocazione dell'Applicazione](#collocazione-dellapplicazione)
  - [Tutto Pronto](#tutto-pronto)
- [Gameplay](#gameplay)
  - [Movimento](#movimento)
  - [Sparare](#sparare)
  - [Chiusura del Gioco](#chiusura-del-gioco)
  - [Nemici](#nemici)
    - [Ape Regina](#ape-regina)
    - [Ape Operaia](#ape-operaia)
    - [Ape Drone](#ape-drone)
- [Struttura dell'App](#struttura-dellapp)
  - [Struttura delle Cartelle](#struttura-delle-cartelle)
  - [Gioco](#gioco)
  - [Stile](#stile)

## Avvio

- ### Creare il Server del Database

  Prima di tutto, per eseguire l'applicazione è necessario impostare un server mySql. Per avviare un server mySql sull'host locale è sufficiente avviare il servizio mySql nel pannello di controllo di XAMPP. Ogni parametro di connessione al database si trova nel file `config/DBConfig.php`. La connessione al server di XAMPP è predefinita.

- ### Impostare le Tabelle

  Dopo aver creato il server di database, è necessario impostare il database e le tabelle necessarie per eseguire il programma eseguendo questo script sul server.

  ```sql
  CREATE DATABASE IF NOT EXISTS bee_game;

  USE bee_game;

  CREATE TABLE IF NOT EXISTS hives(
    id INT NOT NULL AUTO_INCREMENT,
    difficulty VARCHAR(10),
    PRIMARY KEY (id)
  );

  CREATE TABLE IF NOT EXISTS users(
    id INT NOT NULL AUTO_INCREMENT,
    user_name VARCHAR(255),
    PRIMARY KEY (id)
  );

  CREATE TABLE IF NOT EXISTS games(
    id INT NOT NULL AUTO_INCREMENT,
    round INT,
    lives INT,
    hive_id INT,
    user_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (hive_id) REFERENCES hives(id)
  );

  CREATE TABLE IF NOT EXISTS bee_types(
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255),
    max_health INT,
    damage INT,
    PRIMARY KEY (id)
  );

  CREATE TABLE IF NOT EXISTS bees(
    id INT NOT NULL AUTO_INCREMENT,
    current_health INT,
    bee_type_id INT,
    game_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (bee_type_id) REFERENCES bee_types(id),
    FOREIGN KEY (game_id) REFERENCES games(id)
  );

  CREATE TABLE IF NOT EXISTS hive_bees(
    id INT NOT NULL AUTO_INCREMENT,
    hive_id INT,
    bee_type_id INT,
    bee_id INT,
    PRIMARY KEY (id),
    FOREIGN KEY (hive_id) REFERENCES hives(id),
    FOREIGN KEY (bee_type_id) REFERENCES bee_types(id),
    FOREIGN KEY (bee_id) REFERENCES bees(id)
  );

  CREATE TABLE IF NOT EXISTS hive_layouts(
    id INT NOT NULL AUTO_INCREMENT,
    hive_id INT,
    bee_type_id INT,
    bees_count INT,
    PRIMARY KEY (id),
    FOREIGN KEY (hive_id) REFERENCES hives(id),
    FOREIGN KEY (bee_type_id) REFERENCES bee_types(id)
  );

  INSERT INTO hives(id, difficulty)
  VALUES (1, 'Easy'), (2, 'Medium'), (3, 'Hard'), (4, 'Hardcore');

  INSERT INTO bee_types(id, name, max_health, damage)
  VALUES (1, 'QueenBee', 100, 8),
  (2, 'WorkerBee', 75, 10),
  (3, 'DroneBee', 50, 12);

  INSERT INTO hive_layouts(id, hive_id, bee_type_id, bees_count)
  VALUES (1, 1, 1, 1), (2, 1, 2, 5), (3, 1, 3, 8), (4, 2, 1, 1),
  (5, 2, 2, 8), (6, 2, 2, 5), (7, 3, 1, 2), (8, 3, 2, 7),
  (9, 3, 3, 5), (10, 4, 1, 5), (11, 4, 2, 9);
  ```

- ### Collocazione dell'Applicazione

  La cartella dell'applicazione deve essere eseguita in un server Web HTTP. Posizionare la cartella principale dell'applicazione (`Beegame/`) nella cartella `htdocs/` di XAMPP. Quindi avviare il server Apache dal pannello di controllo di XAMPP.

- ### Tutto Pronto

  Ora l'applicazione è pronta. Per raggiungere il gioco ora è sufficiente andare su “[<ins>http://localhost/Beegame/public/index.php</ins>](http://localhost/Beegame/public/index.php)” nel browser.

## Gameplay:

- ### Movimento

  Per muovere il giocatore a destra e a sinistra si possono usare le frecce destra e sinistra.

- ### Sparare

  Per far sparare il giocatore si può premere la barra spaziatrice.

- ### Chiusura del Gioco

  Per uscire dal gioco è sufficiente cliccare sul pulsante “Salva ed esci” sotto la schermata di gioco.

- ### Nemici:

  - #### Ape Regina

    - L'Ape Regina ha una vita di 100 punti ferita.
    - Quando l'Ape Regina viene colpita, 8 punti ferita vengono sottratti alla sua vita.
    - Se/quando l'Ape Regina ha esaurito i punti ferita, **tutte le Api vive rimanenti** esauriscono automaticamente i punti ferita.

  - #### Ape Operaia

    - L'Ape Operaia ha una vita di 75 punti ferita.
    - Quando un'Ape Operaia viene colpita, 10 punti ferita vengono sottratti alla sua vita.

  - #### Ape Drone

    - L'Ape Drone ha una vita di 50 punti Ferita.
    - Quando un'Ape Drone viene colpita, 12 Punti Ferita vengono sottratti alla sua vita.

## Struttura dell'App:

- ### Struttura delle Cartelle

  ```
    Beegame/
     ├── app/
     │    ├── controllers/
     │    ├── models/
     │    └── views/
     │         └── assets/
     ├── config/
     │    └── css/
     └── public/
  ```

  `app/` contiene le cartelle dei file principali: `models/`, `views/`, `controllers/`.

  `models/` contiene i file dei modelli.

  `views/` contiene i file delle viste e le immagini del gioco all'interno di `assets/`.

  `controllers/` contiene i file dei controllori.

  `config/` contiene il file DBConfig che contiene tutti i parametri di connessione al database e la cartella dei fogli di stile `css/`.

  `public/` contiene l'indice dell'applicazione.

- ### Gioco

  Il gioco principale è scritto utilizzando [<ins>PhaserJS</ins>](https://phaser.io/) con le API Arcade Physics.

- ### Stile

  L'applicazione usa [<ins>Bootstap</ins>](https://getbootstrap.com/) come stile di base delle pagine.