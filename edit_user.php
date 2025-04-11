<?php
require 'db_connection.php';
$conn = openConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, Role=? WHERE id=?");
    $stmt->bind_param("sssi", $first_name, $last_name, $role, $id);
    $stmt->execute();
    
    header("Location: user_list.php?success=1");
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $result->fetch_assoc();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticazione</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/edit_user/style.css">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap" rel="stylesheet">
</head>

<h2>Modifica Utente</h2>
<form method="post">
    <!-- ID visibile ma non modificabile -->
    ID: <input type="text" value="<?= $user['id'] ?>" disabled><br>
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <!-- Email visibile ma non modificabile -->
    Email: <input type="email" value="<?= $user['email'] ?>" disabled><br>

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
