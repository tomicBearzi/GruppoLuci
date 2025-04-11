<?php
require 'db_connection.php';
$conn = openConnection();

$result = $conn->query("SELECT * FROM users");
?>

<form action="logout.php" method="post" style="margin-bottom: 10px;">
    <button type="submit">Logout</button>
</form>

<form action="in_progress_visits.php" method="get" style="margin-bottom: 20px;">
    <button type="submit">Vai alle visite in corso</button>
</form>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <p style="color: #4BC047; margin-bottom: 20px;">Utente Aggiornato Correttamente.</p>
<?php endif; ?>

<h2>Lista Utenti</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Nome</th>
        <th>Cognome</th>
        <th>Ruolo</th>
        <th>Azioni</th>
    </tr>
    <?php while ($user = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= $user['email'] ?></td>
        <td><?= $user['first_name'] ?></td>
        <td><?= $user['last_name'] ?></td>
        <td><?= $user['Role'] ?></td>
        <td><a href="edit_user.php?id=<?= $user['id'] ?>">Modifica</a></td>
    </tr>
    <?php endwhile; ?>
</table>
