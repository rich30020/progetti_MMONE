<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esercizio 1</title>
</head>
<body>
<br>
    <h3>Numero 1</h3>
    <form method="post" action="">
        <input type="number" name="num1" required /></br>
        <br>
        <h3>Numero 2</h3>
        <input type="number" name="num2" required />
        <input type="submit" name="submit" value="Calcola Somma"/>
    </form>


    <?php 
        if(isset($_POST['submit'])) {
            $num1 = $_POST["num1"];
            $num2 = $_POST["num2"];
            $sum = $num1 + $num2;

            echo "<h3>la somma di $num1 e di $num2 è $sum </h3>";
        }
    ?>
</body>
</html>