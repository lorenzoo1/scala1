<?php
// File SQLite
$dbFile = 'db.sqlite';

try {
    // Connessione al database SQLite
    $db = new PDO("sqlite:$dbFile");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Creazione della tabella se non esiste
    $createTableSQL = "
        CREATE TABLE IF NOT EXISTS calculations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            operation TEXT NOT NULL,
            result REAL NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ";
    $db->exec($createTableSQL);

    // Variabili iniziali
    $result = '';
    $operation = '';

    // Se è stato inviato il form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recupero i numeri e l'operazione dal form
        $number1 = (float)$_POST['number1'];
        $number2 = (float)$_POST['number2'];
        $selectedOperation = $_POST['operation'];

        // Calcolo del risultato
        switch ($selectedOperation) {
            case '+':
                $result = $number1 + $number2;
                break;
            case '-':
                $result = $number1 - $number2;
                break;
            case '*':
                $result = $number1 * $number2;
                break;
            case '/':
                $result = ($number2 != 0) ? $number1 / $number2 : 'Errore: Divisione per zero';
                break;
            default:
                $result = 'Operazione non valida';
        }

        // Salvataggio dell'operazione nel database se il risultato è numerico
        if (is_numeric($result)) {
            $operation = "$number1 $selectedOperation $number2";
            $insertSQL = "INSERT INTO calculations (operation, result) VALUES (:operation, :result)";
            $stmt = $db->prepare($insertSQL);
            $stmt->execute([':operation' => $operation, ':result' => $result]);
        }
    }

    // Recupero delle ultime 10 operazioni dal database
    $history = $db->query("SELECT * FROM calculations ORDER BY created_at DESC LIMIT 10");

} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcolatrice con Storico</title>
</head>
<body>
    <h1>Calcolatrice</h1>
    <form method="POST">
        <label for="number1">Primo numero:</label>
        <input type="number" id="number1" name="number1" step="any" required>

        <label for="operation">Operazione:</label>
        <select id="operation" name="operation" required>
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
        </select>

        <label for="number2">Secondo numero:</label>
        <input type="number" id="number2" name="number2" step="any" required>

        <button type="submit">Calcola</button>
    </form>

    <?php if ($result !== ''): ?>
        <h2>Risultato: <?= htmlspecialchars($result) ?></h2>
    <?php endif; ?>

    <h2>Storico delle Operazioni</h2>
    <ul>
        <?php foreach ($history as $row): ?>
            <li><?= htmlspecialchars($row['operation']) ?> = <?= htmlspecialchars($row['result']) ?> (<?= $row['created_at'] ?>)</li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
