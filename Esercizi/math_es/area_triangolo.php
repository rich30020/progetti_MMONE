<?php
    $risposta = '';
    if(isset($_POST['invia']) && !empty($_POST['maggiore']) && !empty($_POST['minore'])) {
        $maggiore = $_POST["maggiore"];
        $minore = $_POST["minore"];
        $ipotenusa = sqrt(pow($maggiore,2) + pow($minore,2));
        $perimetro = $ipotenusa + $maggiore + $minore;
        $area = $maggiore * $minore / 2;
        $risposta = "La misura del cateto maggiore del triangolo è: $maggiore</br>La misura del cateto minore del triangolo è: $minore</br>La misura dell'ipotenusa è: $ipotenusa</br>La misura del perimetro è: $perimetro</br>La Misura dell'area è: $area";
    }
    else {
        $risposta = "Mancano dei valori";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area del triangolo</title>
</head>
<body>
<form action="" method="post">
			<label for="maggiore">Cateto maggiore in cm</label>
			<input type="maggiore" name="maggiore" id="maggiore" step="0.01" /><br/>
			<label for="minore">Cateto minore in cm</label>
			<input type="minore" name="minore" id="minore" step="0.01" /><br>
			<div id="pulsanti">
				<input type="submit" name="invia" value="Invia valori">
				<input type="reset" name="reset" value="Cancella valori">
			</div>
</form>
	<div id="risposta"><?php echo $risposta; ?></div>  
</body>
</html>