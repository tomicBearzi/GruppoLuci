<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = trim($_POST['input1']) . trim($_POST['input2']) . trim($_POST['input3']) . trim($_POST['input4']) . trim($_POST['input5']);

    echo "Codice inviato: $code<br>";
    echo "Codice sessione: " . $_SESSION['2fa_code'] . "<br>";

    if (!isset($_SESSION['2fa_code']) || $code !== $_SESSION['2fa_code']) {
        echo "Codice non valido.";
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