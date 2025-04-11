<?php
require 'db_connection.php';
$conn = openConnection();

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Utenti</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/user_list/style.css">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top-bar">
        <img class="logo" src="img/logo.svg">
    </div>
    
    <div class="container">
        <div class="action-buttons">
            <form action="logout.php" method="post">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
            <form action="in_progress_visits.php" method="get">
                <button type="submit" class="visits-btn">Visite in corso</button>
            </form>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-message">
                <p>Utente aggiornato correttamente</p>
            </div>
        <?php endif; ?>

        <h2>Lista Utenti</h2>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Nome</th>
                        <th>Cognome</th>
                        <th>Ruolo</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['first_name'] ?></td>
                        <td><?= $user['last_name'] ?></td>
                        <td><?= $user['Role'] ?></td>
                        <td><a href="edit_user.php?id=<?= $user['id'] ?>" class="edit-link">Modifica</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>