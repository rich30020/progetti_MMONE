<?php
    $risposta = '';
    if(isset($_POST['invia']) && !empty($_POST['prezzo']) && !empty($_POST['scatole'])) {
        $prezzo = $_POST["prezzo"];
        $scatole = $_POST["scatole"];
        $spesa = $prezzo * $scatole;
        $iva = $spesa * 0.22;
        $totale = $spesa + $iva;
        $risposta = "In tutto si ha speso:</br>Imponibile: $spesa &euro;</br>I.V.A; $iva &euro;</br>Totale: $totale &euro;";
        $colore = "darkgreen";
    }
    else {
        $risposta = "Mancano dei valori...";
        $colore = "crimson";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muro di Mattoni</title>
</head>


<body>
<form action="" method="post">
			<label for="prezzo">Prezzo di una scatola</label>
			<input type="number" name="prezzo" id="prezzo" step="0.01" /><br/>
			<label for="scatole">Numero delle scatole acquistate</label>
			<input type="number" name="scatole" id="scatole" step="0.01" /><br>
			<div id="pulsanti">
				<input type="submit" name="invia" value="Invia valori">
				<input type="reset" name="reset" value="Cancella valori">
			</div>
</form>
	<div id="risposta"><?php echo $risposta; ?></div>
</body>
</html>