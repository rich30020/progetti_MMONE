<?php

namespace App\Views;

session_start();

use App\Controllers\BeeGameController;
use App\Models\Game;
use App\Models\Bee;

require_once dirname(__DIR__) . '\models\Game.php';
require_once dirname(__DIR__) . '\models\Bee.php';
require_once dirname(__DIR__) . '\controllers\BeeGameController.php';

$controller = new BeeGameController();
$game = new Game();
$gameState = null;
$bees = array();
$beesjson = [];

if (!empty($_POST['game_id'])) {
    $_SESSION['current_game_id'] = $_POST['game_id'];
    $game->read($_SESSION['current_game_id']);
    $gameState = $controller->loadGame($game);
    foreach ($gameState['bees'] as $beeId) {
        $bee = new Bee();
        $bee->read($beeId['id']);
        if ($bee->getCurrentHealth() > 0) {
            $_SESSION['bees'][$beeId['id']] = $bee->getCurrentHealth();
            $bees[] = $bee;
            $beesjson[] = $bee->toArray();
        }
    }
}

$round = $game->getRound();
$lives = $game->getLives();
$hiveDiff = $game->getHive()->getId();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/phaser@3.86.0/dist/phaser.js"></script>
    <script src="//cdn.jsdelivr.net/npm/phaser@3.86.0/dist/phaser.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../config/css/index.css" rel="stylesheet">
    <title>Game Test</title>
</head>

<body>
    <div class="container">
        <div class="game-container">
            <div id="mainGame">
            </div>
            <div class="container">
                <button id="saveGame" class="btn btn-custom">Save & Quit</button>
            </div>
        </div>
    </div>

    <script>
        function shuffle(array) {
            let currentIndex = array.length;

            // While there remain elements to shuffle...
            while (currentIndex != 0) {

                // Pick a remaining element...
                let randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex--;

                // And swap it with the current element.
                [array[currentIndex], array[randomIndex]] = [
                    array[randomIndex], array[currentIndex]
                ];
            }
        }

        const config = {
            type: Phaser.AUTO,
            width: 800,
            height: 600,
            backgroundColor: '#cf9519',
            parent: 'mainGame',
            pixelArt: true,
            physics: {
                default: 'arcade',
                arcade: {
                    debug: false,
                    gravity: {
                        y: 0
                    }
                }
            },
            scene: {
                preload: preload,
                create: create,
                update: update
            }
        };

        let difficulty = <?php echo $hiveDiff ?>;
        let bees;
        let player;
        let cursors;
        let bullets;
        let enemies;
        let stings;
        let fireButton;
        let gameOverText;
        let restartText;
        let livesText;
        let roundText;
        let gameWonText;
        let lives = 3;
        let round;
        let splats;
        let clicked = false;

        function preload() {
            this.load.setBaseURL('assets');
            this.load.spritesheet('bee-anim', "bee-sprite-animated.png", {
                frameWidth: 15,
                frameHeight: 15
            });
            this.load.spritesheet('bee-worker-anim', "bee-worker-sprite-animated.png", {
                frameWidth: 21,
                frameHeight: 20
            });
            this.load.spritesheet('bee-queen-anim', "bee-queen-sprite-animated.png", {
                frameWidth: 23,
                frameHeight: 21
            });
            this.load.image('bee', "bee-sprite.png");
            this.load.image('trump', "trump-sprite.png");
            this.load.image('bullet', "bullet.png");
            this.load.spritesheet('bullet-anim', "bullet-anim.png", {
                frameWidth: 8,
                frameHeight: 20
            });
            this.load.image('sting', "sting.png");
            this.load.image('splat', "splat.png");
        }

        function create(data) {
            if (data?.round !== undefined) {
                round = data.round;
            } else {
                round = <?php echo $round ?>;
            }

            if (data?.lives !== undefined) {
                lives = data.lives;
            } else {
                lives = <?php echo $lives ?>;
            }

            if (data?.bees !== undefined) {
                bees = data.bees;
            } else {
                bees = JSON.parse('<?php echo json_encode($beesjson); ?>');
            }

            clicked = false;
            shuffle(bees);

            this.graphics = this.add.graphics();


            // Creazione del giocatore
            player = this.physics.add.image(400, 550, 'trump').setOrigin(0.5, 0.5).setCollideWorldBounds(true);
            player.setScale(2);
            player.body.immovable = true;

            // Cursori
            cursors = this.input.keyboard.createCursorKeys();
            var spaceBar = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.SPACE);
            var spaceBar = this.input.keyboard.addKey(Phaser.Input.Keyboard.KeyCodes.SPACE);


            // Colpi
            stings = this.physics.add.group({
                defaultKey: 'sting',
                maxSize: 7 + round + difficulty,
            });

            //animazione Ape Drone
            if (!this.anims.exists('idleBee')) {
                const animConfigBee = {
                    key: 'idleBee',
                    frames: this.anims.generateFrameNumbers('bee-anim'),
                    frameRate: 20,
                    repeat: -1
                };
                this.anim = this.anims.create(animConfigBee);
            }

            //animazione Ape Worker
            if (!this.anims.exists('idleWorkerBee')) {
                const animConfigWorkerBee = {
                    key: 'idleWorkerBee',
                    frames: this.anims.generateFrameNumbers('bee-worker-anim'),
                    frameRate: 20,
                    repeat: -1
                };
                this.anim = this.anims.create(animConfigWorkerBee);
            }

            //animazione Ape Queen
            if (!this.anims.exists('idleQueenBee')) {
                const animConfigQueenBee = {
                    key: 'idleQueenBee',
                    frames: this.anims.generateFrameNumbers('bee-queen-anim'),
                    frameRate: 20,
                    repeat: -1
                };
                this.anim = this.anims.create(animConfigQueenBee);
            }

            //animazione Proiettile
            if (!this.anims.exists('idleBullet')) {
                const animConfigBullet = {
                    key: 'idleBullet',
                    frames: this.anims.generateFrameNumbers('bullet-anim'),
                    frameRate: 10,
                    repeat: -1
                };
                this.anim = this.anims.create(animConfigBullet);
            }

            // Nemici
            enemies = this.physics.add.group();

            bullets = this.physics.add.group();

            splats = this.physics.add.group();

            const beeInit = (type, enemies, x, y) => {
                let enemy;
                switch (type) {
                    case 1:
                        enemy = enemies.create(x, y, 'bee-queen-anim').setOrigin(0.5, 0.5);
                        enemy.setScale(2);
                        enemy.setCollideWorldBounds(true);
                        enemy.setBounce(0);
                        enemy.body.immovable = true;
                        enemy.anims.load('idleQueenBee');
                        enemy.anims.play('idleQueenBee');
                        break;
                    case 2:
                        enemy = enemies.create(x, y, 'bee-worker-anim').setOrigin(0.5, 0.5);
                        enemy.setScale(2);
                        enemy.setCollideWorldBounds(true);
                        enemy.setBounce(0);
                        enemy.body.immovable = true;
                        enemy.anims.load('idleWorkerBee');
                        enemy.anims.play('idleWorkerBee');
                        break;
                    case 3:
                        enemy = enemies.create(x, y, 'bee-anim').setOrigin(0.5, 0.5);
                        enemy.setScale(2);
                        enemy.setCollideWorldBounds(true);
                        enemy.setBounce(0);
                        enemy.body.immovable = true;
                        enemy.anims.load('idleBee');
                        enemy.anims.play('idleBee');
                        break;
                    default:
                        break;
                }

                // Create a path that forms a figure-eight
                enemy.path = new Phaser.Curves.Path(x, y);
                enemy.path.ellipseTo(50, 70, 0, 360, true, 0); // First loop
                enemy.path.ellipseTo(-50, 70, 0, 360, true, 0); // Second loop

                enemy.follower = {
                    t: 0,
                    vec: new Phaser.Math.Vector2()
                };

                this.tweens.add({
                    targets: enemy.follower,
                    t: 1,
                    ease: 'linear',
                    duration: ((Math.random() * 2) + 4) * 1000 - (150 * round * difficulty), // Speed of movement
                    yoyo: false,
                    repeat: -1
                });
                return enemy;
            }

            const grid = [];
            const row = bees.length / 2;
            for (let i = 0; i < 2; i++) {
                for (let j = 0; j < row; j++) {
                    grid.push({
                        x: j * 80 + 150,
                        y: i * 80 + 100
                    });
                }
            }

            bees.forEach((element, index) => {
                const y = grid[index].y;
                const x = grid[index].x;
                bees[index].target = beeInit(element.beeType, enemies, x, y);
            });

            const enemiesArray = enemies.children.getArray();

            // Scoring
            livesText = this.add.text(16, 16, `Lives: ${lives}`, {
                fontSize: '32px',
                fill: '#fff'
            });

            roundText = this.add.text(config.width - 180, 16, `Round: ${round}`, {
                fontSize: '32px',
                fill: '#fff'
            });

            gameOverText = this.add.text(400, 300, '', {
                fontSize: '64px',
                fill: '#ff0000'
            }).setOrigin(0.5);

            restartText = this.add.text(400, 350, '', {
                fontSize: '32px',
                fill: '#ffffff'
            }).setOrigin(0.5);

            gameWonText = this.add.text(400, 300, '', {
                fontSize: '64px',
                fill: '#00ff00'
            }).setOrigin(0.5);

            // Collisions
            this.physics.add.overlap(bullets, enemies, hitEnemy, null, this);
            this.physics.add.overlap(stings, player, hitPlayer, null, this);

        }


        function update() {
            const enemiesArray = enemies.children.getArray();

            if (cursors.left.isDown && player.body) {
                player.setVelocityX(-250);
                player.setAngle(-5);
            } else if (cursors.right.isDown && player.body) {
                player.setVelocityX(250);
                player.setAngle(5);
            } else if (player.body) {
                player.setVelocityX(0);
                player.setAngle(0);
            }

            // Sparare
            if (Phaser.Input.Keyboard.JustDown(cursors.space)) {
                shootBullet();
            }

            if (Math.random() > (0.98 - (0.01 * round + 0.01 * difficulty))) {
                let random = Phaser.Math.RND.integerInRange(0, enemies.children.size);
                let randomEnemy = enemiesArray[random];

                if (randomEnemy) {
                    let sting = stings.get(randomEnemy.body.x, randomEnemy.body.y + 20);
                    if (sting) {
                        sting.setActive(true);
                        sting.setVisible(true);
                        sting.setScale(1.5);
                        sting.setDepth(-50);
                        this.physics.moveToObject(sting, player, 220 + (0.5 * round * difficulty));
                        const angleRad = Math.atan2(sting.body.velocity.y, sting.body.velocity.x);
                        const angleDeg = Phaser.Math.RadToDeg(angleRad);
                        sting.setAngle(angleDeg - 90);
                    }
                }
            }
            const follower = this.follower;


            // Aggiornamento dei nemici
            enemies.children.iterate(function(enemy) {
                if (enemy.y > 600) {
                    enemy.y = 0;
                    enemy.x = Phaser.Math.Between(50, 750);
                }
                if (enemy.follower) {
                    enemy.path.getPoint(enemy.follower.t, enemy.follower.vec);
                    enemy.setPosition(enemy.follower.vec.x, enemy.follower.vec.y);
                }
            });


            // Verifica ogni proiettile per vedere se ha toccato i bordi
            bullets.getChildren().forEach(function(bullet) {
                // Se il proiettile è fuori dal canvas
                if (bullet.x < 0 || bullet.x > this.sys.game.config.width || bullet.y < 0 || bullet.y > this.sys.game.config.height) {
                    bullet.destroy(); // Distruggi il proiettile
                }
            }, this);

            stings.getChildren().forEach(function(sting) {
                // Se il proiettile è fuori dal canvas
                if (sting.x < 0 || sting.x > this.sys.game.config.width || sting.y < 0 || sting.y > this.sys.game.config.height) {
                    sting.destroy(); // Distruggi il proiettile
                }
            }, this);

            if (lives <= 0) {
                bullets.children.iterate(function(bullet) {
                    if (bullet) {
                        bullet.destroy();
                    }
                });
                stings.children.iterate(function(sting) {
                    if (sting) {
                        sting.destroy();
                    }
                });
                gameOver(this);
            }

            let victory = true;

            bees.forEach((element) => {
                if (element.beeType === 1 && element.target !== null) {
                    victory = false;
                }
            });

            if (victory) {
                bees.forEach((element) => {
                    if (element.target && element.target.scene !== undefined && Phaser.Math.RND.integerInRange(0, 100) > 98) {
                        element.currentHealth = 0;
                        let splat = splats.create(element.target.x, element.target.y, 'splat');
                        splat.setAngle(Phaser.Math.RND.integerInRange(0, 360));
                        splat.setScale(2);
                        splat.setDepth(-255);
                        element.target.destroy();
                        element.target = null;
                    }
                });
                bullets.children.iterate(function(bullet) {
                    if (bullet) {
                        bullet.destroy();
                    }
                });
                stings.children.iterate(function(sting) {
                    if (sting) {
                        sting.destroy();
                    }
                });
                if (bees.find((bee) => bee.target) === undefined) {
                    gameWon(this);
                }
            };
        }

        function shootBullet() {
            let bullet; 

            bullet = bullets.create(player.x, player.y - 20, 'bullet-anim').setOrigin(0.5, 0.5);
            bullet.setScale(1.5);
            bullet.setBounce(0);
            bullet.body.immovable = true;
            bullet.setVelocityY(-400);
            bullet.setDepth(-50);
            bullet.anims.load('idleBullet');
            bullet.anims.play('idleBullet');
        }

        function hitEnemy(bullet, enemy) {
            bullet.destroy();
            const enemiesArray = enemies.getChildren();
            const bee = bees.find((element) => element.target === enemy);
            bee.currentHealth -= bee.damage;
            if (bee.currentHealth <= 0) {
                let splat = splats.create(bee.target.x, bee.target.y, 'splat');
                splat.setAngle(Phaser.Math.RND.integerInRange(0, 360));
                splat.setScale(2);
                splat.setDepth(-255);
                bee.target.destroy();
                bee.target = null;
            }
        }

        function hitPlayer(player, sting) {
            if (!sting) return; // Ensure sting is defined
            lives -= 1;
            sting.destroy();
            livesText.text = `Lives: ${lives}`
        }

        const gameOver = (game) => {
            gameOverText.setText('GAME OVER');
            game.physics.pause();
            restartText.setText('RESTART');
            restartText.setInteractive();
            restartText.on('pointerup', async () => {
                if (!clicked) {
                    clicked = true
                    const gameState = await sendGameState();
                    game.scene.restart({
                        'round': 0,
                        'lives': 3,
                        'bees': gameState.bees,
                    })
                }
            })
        }

        const gameWon = (game) => {
            gameWonText.setText('GAME WON');
            game.physics.pause();
            restartText.setText('NEXT ROUND');
            restartText.setInteractive();
            restartText.on('pointerup', async () => {
                if (!clicked) {
                    clicked = true
                    const gameState = await sendGameState();
                    game.scene.restart({
                        'round': gameState.game.round,
                        'lives': gameState.game.lives,
                        'bees': gameState.bees,
                    })
                }
            });
        }

        const game = new Phaser.Game(config);

        const sendGameState = async () => {

            const game = {
                'id': <?php echo $game->getId(); ?>,
                'round': round,
                'lives': lives,
                'user_id': <?php echo $game->getUser()->getId(); ?>,
                'hive_id': <?php echo $game->getHive()->getId(); ?>,
            }

            bees.map((element) => {
                delete element.target;
                return element;
            });

            const response = await fetch('../controllers/GameRounter.php', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    'game': game,
                    'bees': bees,
                })
            })

            const data = await response.json();
            console.log(data);
            if (data.success === true) {
                return data;
            } else {
                return undefined;
            }
        }

        document.getElementById('saveGame').addEventListener('click', async () => {
            let result = await sendGameState();
            if (result) {
                window.location.href = 'select-game.php';
            }
        });
    </script>
</body>

</html>