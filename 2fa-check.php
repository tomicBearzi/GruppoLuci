<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['2fa_attempts'])) {
        $_SESSION['2fa_attempts'] = 0;
    }

    $_SESSION['2fa_attempts']++;

    if ($_SESSION['2fa_attempts'] > 3) {
        // Distruggi la sessione dopo 3 tentativi falliti
        session_unset();
        session_destroy();
        header("Location: login.php?error=too_many_attempts");
        exit();
    }

    $code = trim($_POST['input1']) . trim($_POST['input2']) . trim($_POST['input3']) . trim($_POST['input4']) . trim($_POST['input5']);

    if (!isset($_SESSION['2fa_code']) || $code !== $_SESSION['2fa_code']) {
        unset($_SESSION['2fa_code']); // Invalida il codice 2FA
        header("Location: 2fa.php?error=1"); // Reindirizza con un messaggio di errore
        exit();
    }

    unset($_SESSION['2fa_code']); // Invalida il codice 2FA dopo un tentativo riuscito
    $_SESSION['authenticated'] = true;

    header("Location: index.php");
    exit();
} else {
    header("Location: login.php"); // Reindirizza se il metodo non è POST
    exit();
}
?>