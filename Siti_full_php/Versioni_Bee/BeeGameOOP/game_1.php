<?php
session_start();

class Ape {
    public $nome;
    public $vita;
    public $danno;

    public function __construct($nome, $vita, $danno) {
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
        $this->api = [
            new Ape("Ape Regina", 100, 8),
            new Ape("Ape Operaia", 75, 10),
            new Ape("Seconda Ape Operaia", 75, 10),
            new Ape("Terza Ape Operaia", 75, 10),
            new Ape("Quarta Ape Operaia", 75, 10),
            new Ape("Quinta Ape Operaia", 75, 10),
            new Ape("Ape Drone", 50, 12),
            new Ape("Seconda Ape Drone", 50, 12),
            new Ape("Terza Ape Drone", 50, 12),
            new Ape("Quarta Ape Drone", 50, 12),
            new Ape("Quinta Ape Drone", 50, 12),
            new Ape("Sesta Ape Drone", 50, 12),
            new Ape("Settima Ape Drone", 50, 12),
            new Ape("Ottava Ape Drone", 50, 12),
        ];
        $this->salvaApiInSessione();
    }

    private function caricaApiDaSessione() {
        $this->api = array_map(function($ape) {
            return new Ape($ape['nome'], $ape['vita'], $ape['danno']);
        }, $_SESSION['api']);
    }

    private function salvaApiInSessione() {
        $_SESSION['api'] = array_map(function($ape) {
            return ['nome' => $ape->nome, 'vita' => $ape->vita, 'danno' => $ape->danno];
        }, $this->api);
    }

    public function colpisciApeCasuale() {
        $apiVive = array_filter($this->api, function($ape) {
            return $ape->eViva();
        });

        if (empty($apiVive)) {
            return "Tutte le api sono morte! Il gioco è finito.";
        }

        $apeCasuale = $apiVive[array_rand($apiVive)];
        $vitaPrecedente = $apeCasuale->vita;
        $apeCasuale->subisciDanno();
        $this->salvaApiInSessione();

        $messaggio = "Hai colpito {$apeCasuale->nome} che aveva $vitaPrecedente punti vita. Ora ne ha {$apeCasuale->vita}.";
        if (!$apeCasuale->eViva()) {
            $messaggio .= " {$apeCasuale->nome} è morta!";
        }
        return $messaggio;
    }
}

$gioco = new BeeGame();
$messaggio = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        $gioco->resetGame();
    } else {
        $messaggio = $gioco->colpisciApeCasuale();
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco delle Api</title>
</head>
<body>
    <h1>Gioco delle Api</h1>
    <p>Premi il bottone per colpire un'ape a caso! Ogni tipo di ape ha un danno diverso.</p>
    
    <?php if ($messaggio): ?>
        <p><strong><?php echo $messaggio; ?></strong></p>
    <?php endif; ?>
    
    <form method="post">
        <button type="submit" style="width: 150px; height: 50px">Colpisci un'ape!</button>
    </form>

    <p>Premi il bottone per ripristinare tutte le vite delle api.</p>
    <form method="post">
        <button type="submit" name="reset" style="width: 150px; height: 50px">Resetta il gioco</button>
    </form>

    <h3>Stato delle api:</h3>
    <ul>
        <?php foreach ($gioco->api as $ape): ?>
            <li><?php echo "{$ape->nome}: {$ape->vita} HP"; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>