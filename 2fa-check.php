<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];

    if (!isset($_SESSION['2fa_code']) || $code !== $_SESSION['2fa_code']) {
        header("Location: 2fa.php?error=1");
        exit();
    }

    unset($_SESSION['2fa_code']);
    $_SESSION['authenticated'] = true;

    header("Location: index.php");
    exit();
} else {
    echo "Metodo di richiesta non valido.";
}
?>