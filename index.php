<?php
// Avvia la sessione per memorizzare i calcoli
session_start();

// Funzione per eseguire il calcolo
function calcola($num1, $num2, $operatore) {
    switch ($operatore) {
        case '+':
            return $num1 + $num2;
        case '-':
            return $num1 - $num2;
        case '*':
            return $num1 * $num2;
        case '/':
            if ($num2 == 0) {
                return "Errore: divisione per zero!";
            }
            return $num1 / $num2;
        default:
            return "Operatore non valido!";
    }
}

// Controlla se sono stati inviati dati dal modulo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni i numeri e l'operatore
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $operatore = $_POST['operatore'];

    // Calcola il risultato
    $risultato = calcola($num1, $num2, $operatore);

    // Aggiungi il risultato alla sessione
    $_SESSION['calcoli'][] = "$num1 $operatore $num2 = $risultato";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatrice PHP</title>
</head>
<body>
    <h1>Calcolatrice PHP</h1>
    <form method="post" action="">
        <input type="number" name="num1" placeholder="Primo numero" required>
        <input type="number" name="num2" placeholder="Secondo numero" required>
        <select name="operatore" required>
            <option value="+">Somma</option>
            <option value="-">Sottrazione</option>
            <option value="*">Moltiplicazione</option>
            <option value="/">Divisione</option>
        </select>
        <button type="submit">Calcola</button>
    </form>

    <?php if (isset($risultato)): ?>
        <h2>Risultato: <?= $risultato ?></h2>
    <?php endif; ?>

    <h3>Calcoli precedenti:</h3>
    <?php
    // Mostra tutti i calcoli precedenti
    if (isset($_SESSION['calcoli'])) {
        foreach ($_SESSION['calcoli'] as $calcolo) {
            echo "<p>$calcolo</p>";
        }
    }
    ?>

    <a href="">Fai un altro calcolo</a>
</body>
</html>