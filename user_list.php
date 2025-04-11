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
        <?  if (!isset($_SESSION['authenticated'])) { ?>
                <a class="logout-btn" href="logout.php">Logout</a>
        <?  } ?>
        <form action="in_progress_visits.php" method="get">
            <button type="submit" class="visits-btn">Visite in corso</button>
        </form>
    </div>
    
    <div class="container">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
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
                    <td data-label="ID"><?= $user['id'] ?></td>
                    <td data-label="Email"><?= $user['email'] ?></td>
                    <td data-label="Nome"><?= $user['first_name'] ?></td>
                    <td data-label="Cognome"><?= $user['last_name'] ?></td>
                    <td data-label="Ruolo" data-role="<?= $user['Role'] ?>"><?= $user['Role'] ?></td>
                    <td data-label="Azioni"><a href="edit_user.php?id=<?= $user['id'] ?>" class="edit-link">Modifica</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>