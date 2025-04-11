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

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Utente</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/edit_user/style.css">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top-bar">
        <img class="logo" src="img/logo.svg">
    </div>
    <div class="container">
        <h2>Modifica Utente</h2>
        
        <div class="edit-container">
            <form method="post">
                <!-- ID Field -->
                <div class="form-group">
                    <label for="id-display">ID</label>
                    <input type="text" id="id-display" value="<?= $user['id'] ?>" disabled>
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="<?= $user['email'] ?>" disabled>
                </div>

                <!-- First Name Field -->
                <div class="form-group">
                    <label for="first_name">Nome</label>
                    <input type="text" id="first_name" name="first_name" value="<?= $user['first_name'] ?>">
                </div>

                <!-- Last Name Field -->
                <div class="form-group">
                    <label for="last_name">Cognome</label>
                    <input type="text" id="last_name" name="last_name" value="<?= $user['last_name'] ?>">
                </div>

                <!-- Role Field -->
                <div class="form-group">
                    <label for="role">Ruolo</label>
                    <select id="role" name="role">
                        <?php foreach (['Visitor', 'Secretary', 'Administrator', 'Totem'] as $r): ?>
                            <option value="<?= $r ?>" <?= $user['Role'] == $r ? 'selected' : '' ?>><?= $r ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit">Salva Modifiche</button>
            </form>
        </div>
    </div>
</body>
</html>