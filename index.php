<?php
session_start();
include 'db_connection.php';

function logout() {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    logout();
}

if (!isset($_SESSION['logged']) && !isset($_SESSION['authenticated'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Ricordella
        </title>
        <link rel="icon" href="logo.png" type="image/x-icon" />
        <link rel="stylesheet" href="./css/index/style.css">
    </head>

    <body>
        <div class="container">
            <h2>Benvenuto</h2>

            <a href="index.php?action=logout" class="logout-button">LOGOUT</a>
        </div>
    
</html>