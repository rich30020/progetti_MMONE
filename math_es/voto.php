<?php
    $risposta = '';
	if(isset($_POST['data'])) {
		$app = $_POST['data'];
		$data = new datetime($app);
		$odierna = new datetime();
		$anni_obj = $odierna->diff(targetObject: $data);
		$anni = $anni_obj->format("%y");

		if($anni >= 21)	$risposta = "Ha $anni anni, puoi votare sia Camera che	Senato";

		elseif($anni < 21 && $anni >= 18)
			$risposta = "Ha $anni anni, puoi votare solo alla Camera";

		else	$risposta = "Ha $anni anni, non puoi votare";
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
			<label for="data">Inserire il proprio anno di nascita:</label>
			<input type="date" name="data" id="data" /><br>
			<menu>
				<input type="submit" name="invia" value="Invia">
				<input type="reset" name="reset" value="Cancella">
			</menu>
		</form>
		<div><?php echo $risposta; ?></div>
</body>
</html>

