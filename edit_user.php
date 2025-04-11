<?php
require 'db_connection.php';
$conn = openConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET email=?, first_name=?, last_name=?, Role=? WHERE id=?");
    $stmt->bind_param("ssssi", $email, $first_name, $last_name, $role, $id);
    $stmt->execute();

    echo "Utente aggiornato! <a href='user_list.php'>Torna alla lista</a>";
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();
?>

<h2>Modifica Utente</h2>
<form method="post">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    Email: <input type="email" name="email" value="<?= $user['email'] ?>"><br>
    Nome: <input type="text" name="first_name" value="<?= $user['first_name'] ?>"><br>
    Cognome: <input type="text" name="last_name" value="<?= $user['last_name'] ?>"><br>
    Ruolo: 
    <select name="role">
        <?php foreach (['Visitor', 'Secretary', 'Administrator', 'Totem'] as $r): ?>
            <option value="<?= $r ?>" <?= $user['Role'] == $r ? 'selected' : '' ?>><?= $r ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Salva</button>
</form>
