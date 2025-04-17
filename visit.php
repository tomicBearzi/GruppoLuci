<?php
session_start();
include 'db_connection.php';

// Verifica se l'utente è autenticato
if (!isset($_SESSION['authenticated']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];
$conn = openConnection();

// Cancella una visita se viene richiesto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_code'])) {
    $delete_code = $_POST['delete_code'];
    $stmt = $conn->prepare("DELETE FROM visit WHERE code = ? AND email = ?");
    $stmt->bind_param("ss", $delete_code, $user_email);
    $stmt->execute();
    $stmt->close();
}

// Recupera le visite associate all'utente autenticato
$stmt = $conn->prepare("SELECT code, date, start_time, end_time, visitor_count, visit_status, description 
                        FROM visit 
                        WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

$visits = [];
while ($row = $result->fetch_assoc()) {
    $visits[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Tue Visite</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/visit/style.css">
    <link
        href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap"
        rel="stylesheet">

</head>

<body>
    <div class="top-bar">
        <img class="logo" src="img/logo.svg">
        <?php if (isset($_SESSION['authenticated'])) { ?>
            <a class="logout-a" href="logout.php">Logout</a>
        <?php } ?>
    </div>
    <div class="container">
        <h2>Le Tue Visite</h2>

        <?php if (empty($visits)): ?>
            <p>Non hai visite programmate.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Data</th>
                        <th>Ora Inizio</th>
                        <th>Ora Fine</th>
                        <th>Numero Visitatori</th>
                        <th>Descrizione</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visits as $visit): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($visit['code']); ?></td>
                            <td><?php echo htmlspecialchars($visit['date']); ?></td>
                            <td><?php echo htmlspecialchars($visit['start_time']); ?></td>
                            <td><?php echo htmlspecialchars($visit['end_time'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($visit['visitor_count']); ?></td>
                            <td><?php echo htmlspecialchars($visit['description']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="delete_code"
                                        value="<?php echo htmlspecialchars($visit['code']); ?>">
                                    <button type="submit" class="btn-delete">Cancella</button>
                                </form>
                                <form method="POST" action="generate_badge.php">
                                    <input type="hidden" name="visit_code"
                                        value="<?php echo htmlspecialchars($visit['code']); ?>">
                                    <button type="submit" class="btn-download">Scarica Badge</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>