# The Bee Game

## Index

- [Starting Up](#starting-up)
  - [Create The Database Server](#create-the-database-server)
  - [Setup The Tables](#setup-the-tables)
  - [Placing The Application](#placing-the-application)
  - [All Done](#all-done)
- [Gameplay](#gameplay)
  - [Movement](#movement)
  - [Shooting](#shooting)
  - [Closing The Game](#closing-the-game)
  - [Enemies](#enemies)
    - [Queen Bee](#queen-bee)
    - [Worker Bee](#worker-bee)
    - [Drone Bee](#drone-bee)
- [App Structure](#app-structure)
  - [Folder Structure](#folder-structure)
  - [Game](#game)
  - [Style](#style)

## Starting Up

- ### Create The Database Server

  First of all to run the application you need to setup a mySql server. To start a mySql server on your localhost you can simply start the mySql service in the XAMPP control panel. Every database connection parameter is located in the `config/DBConfig.php` file. The XAMPP server connection is ready by default.

- ### Setup The Tables

  After creating the database server you need to setup the database and tables needed to run the program by running this script on the server.

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

- ### Placing The Application

  The application folder must run in a HTTP Web Server. Place the root folder of the application (`Beegame/`) in the `htdocs/` folder in XAMPP. Then start the Apache server from the XAMPP control panel.

- ### All Done

  Now the application is ready. To reach the game now you only need to go to "[<ins>http://localhost/Beegame/public/index.php</ins>](http://localhost/Beegame/public/index.php)" in your browser.

## Gameplay:

- ### Movement

  To move the player right and left you can use the left and right arrows.

- ### Shooting

  To make the player shoot you can press the spacebar.

- ### Closing The Game
  To quit you can simply click the button saying "Save & Quit" under the game screen.

- ### Enemies:

  - #### Queen Bee

    - The Queen Bee has a lifespan of 100 Hit Points.
    - When the Queen Bee is hit, 8 Hit Points are deducted from her lifespan.
    - If/When the Queen Bee has run out of Hit Points, **All remaining alive Bees** automatically run out of hit points.

  - #### Worker Bee

    - Worker Bees have a lifespan of 75 Hit Points.
    - When a Worker Bee is hit, 10 Hit Points are deducted from his lifespan.

  - #### Drone Bee

    - Drone Bees have a lifespan of 50 Hit Points.
    - When a Drone Bee is hit, 12 Hit Points are deducted from his lifespan.

## App Structure:

- ### Folder Structure

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

  `app/` contains the main files folders: `models/`, `views/`, `controllers/`.

  `models/` contains the models files.

  `views/` contains the views files and the images assets for the game inside `assets/`.

  `controllers/` contains the controllers files.

  `config/` contains the DBConfig file that contains all the database connection parameters and the stylesheets folder `css/`.

  `public/` contains the index of the application.

- ### Game

  The main game is written using [<ins>PhaserJS</ins>](https://phaser.io/) with the Arcade API Physics.

- ### Style

  The application uses [<ins>Bootstap</ins>](https://getbootstrap.com/) to style the base of the views.
