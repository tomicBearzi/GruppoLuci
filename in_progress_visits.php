<?php
require 'db_connection.php';
$conn = openConnection();

$result = $conn->query("SELECT * FROM visit WHERE visit_status = 'In_Progress'");
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visite In Progresso</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/in_progress/style.css">
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
        <h2>Visite in corso</h2>

        <form action="user_list.php" method="get">
            <button type="submit">Vai alla lista utenti</button>
        </form>

        <table>
            <tr>
                <th>Codice</th>
                <th>Email</th>
                <th>Data</th>
                <th>Ora Inizio</th>
                <th>Visitatori</th>
                <th>Descrizione</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['code'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['start_time'] ?></td>
                    <td><?= $row['visitor_count'] ?></td>
                    <td><?= $row['description'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>