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
    <link rel="stylesheet" href="./css/visit/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 16px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .status {
            padding: 4px 8px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
        }

        .status-accepted {
            background-color: #4CAF50;
        }

        .status-in-progress {
            background-color: #FFA500;
        }

        .status-finished, .status-cancelled {
            background-color: #E24343;
        }

        .btn-delete {
            background-color: #E24343;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #C0392B;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Le Tue Visite</h1>

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
                        <th>Stato</th>
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
                            <td>
                                <?php if ($visit['visit_status'] == 'Accepted'): ?>
                                    <span class="status status-accepted">Accettata</span>
                                <?php elseif ($visit['visit_status'] == 'In_Progress'): ?>
                                    <span class="status status-in-progress">In Svolgimento</span>
                                <?php elseif ($visit['visit_status'] == 'Finished'): ?>
                                    <span class="status status-finished">Svolta</span>
                                <?php elseif ($visit['visit_status'] == 'Cancelled'): ?>
                                    <span class="status status-cancelled">Cancellata</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($visit['description']); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="delete_code" value="<?php echo htmlspecialchars($visit['code']); ?>">
                                    <button type="submit" class="btn-delete">Cancella</button>
                                </form>
                                <form method="POST" action="generate_badge.php">
                                    <input type="hidden" name="visit_code" value="<?php echo htmlspecialchars($visit['code']); ?>">
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