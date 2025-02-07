<?php
session_start();

class Ape {
    public $tipo;
    public $nome;
    public $vita;
    public $danno;

    public function __construct($tipo, $nome, $vita, $danno) {
        $this->tipo = $tipo;
        $this->nome = $nome;
        $this->vita = $vita;
        $this->danno = $danno;
    }

    public function subisciDanno() {
        $this->vita = max(0, $this->vita - $this->danno);
    }

    public function eViva() {
        return $this->vita > 0;
    }
}

class BeeGame {
    public $api = [];

    public function __construct() {
        if (!isset($_SESSION['api'])) {
            $this->resetGame();
        } else {
            $this->caricaApiDaSessione();
        }
    }

    public function resetGame() {
        $this->api = [];
        $this->aggiungiApi("queen_bee", "Ape Regina", 100, 8);
        for ($i = 1; $i <= 5; $i++) {
            $this->aggiungiApi("worker_bee", "Ape Operaia $i", 75, 10);
        }
        for ($i = 1; $i <= 8; $i++) {
            $this->aggiungiApi("drone_bee", "Ape Drone $i", 50, 12);
        }
        $this->salvaApiInSessione();
    }

    private function aggiungiApi($tipo, $nome, $vita, $danno) {
        $this->api[] = new Ape($tipo, $nome, $vita, $danno);
    }

    private function caricaApiDaSessione() {
        $this->api = array_map(fn($ape) => new Ape($ape['tipo'], $ape['nome'], $ape['vita'], $ape['danno']), $_SESSION['api']);
    }

    private function salvaApiInSessione() {
        $_SESSION['api'] = array_map(fn($ape) => ['tipo' => $ape->tipo, 'nome' => $ape->nome, 'vita' => $ape->vita, 'danno' => $ape->danno], $this->api);
    }

    public function colpisciApe($indice) {
        if (!isset($this->api[$indice])) {
            return "Ape non trovata.";
        }

        $ape = $this->api[$indice];
        $ape->subisciDanno();
        $this->salvaApiInSessione();

        if ($ape->tipo === "queen_bee" && !$ape->eViva()) {
            return "Hai colpito {$ape->nome}. Ora è morta! Il gioco è finito.";
        }

        return "Hai colpito {$ape->nome}. Ora ha {$ape->vita} punti vita.";
    }
}

$gioco = new BeeGame();
$messaggio = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        $gioco->resetGame();
    } elseif (isset($_POST['ape'])) {
        $messaggio = $gioco->colpisciApe($_POST['ape']);
    }
}
$api = $gioco->api;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco delle Api</title>
    <style>
        button:hover { background-color: #2980b9; }
        body { background-color: #95a5a6; }
        select { font-family: cursive; }
        .bee-container { display: flex; flex-wrap: wrap; }
        .bee { margin: 30px; text-align: center; }
    </style>
</head>
<body>
    <h1 style="color: #f1c40f; font-family: cursive">Gioco delle Api</h1>
    <p style="font-family: cursive;">Seleziona l'ape da colpire e premi il bottone!</p>

    <?php if ($messaggio): ?>
        <p><strong><?php echo $messaggio; ?></strong></p>
    <?php endif; ?>
    
    <form method="post">
        <select name="ape" id="ape" style="width: 200px; height: 30px; font-family: cursive;">
            <?php foreach ($api as $index => $ape): ?>
                <option value="<?php echo $index; ?>">
                    <?php echo $ape->nome . " (" . $ape->vita . " HP)"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button id="bottone1" type="submit" style="width: 150px; height: 50px; border-radius: 30px; font-family: cursive">Colpisci un'ape!</button>
    </form>

    <p style="font-family: cursive;">Premi il bottone per ripristinare tutte le vite delle api.</p>
    <form method="post">
        <button id="bottone2" type="submit" name="reset" style="width: 150px; height: 50px; border-radius: 30px; font-family: cursive">Resetta il gioco</button>
    </form>

    <h3 style="color: #f1c40f; font-family: cursive">Stato delle api:</h3>
    <div class="bee-container">
        <?php foreach ($api as $ape): ?>
            <div class="bee">
                <img src='images/gif_ape.gif' width='100px' height='100px'><br>
                <?php echo "{$ape->nome}: {$ape->vita} HP"; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
