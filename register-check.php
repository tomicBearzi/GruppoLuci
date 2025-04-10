<?php
session_start();
include 'db_connection.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable('/home/tomic.bearzi.info/');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['conferma'];

    // Verifica se le password combaciano
    if ($password !== $confirm_password) {
        header("Location: register.php?error=2");
        exit();
    }

    // Verifica se l'email è valida
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=3");
        exit();
    }

    $conn = openConnection();

    // Verifica se l'email è già in uso
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        header("Location: register.php?error=1");
        exit();
    }
    $stmt->close();

    // Hash della password e generazione del token di conferma
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $confirmation_token = bin2hex(random_bytes(16));

    // Inserimento dei dati nel database
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, confirmation_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $hashed_password, $confirmation_token);
    $stmt->execute();
    $stmt->close();

    // Invio dell'email di conferma
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->SMTPAuth = false;
        $mail->Port = '25';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPDebug = 2;

        $mail->setFrom('no-reply@bearzi.info', 'LUCI');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Conferma Email Sito';
        $mail->Body    = "Ciao $first_name $last_name,<br><br>";
        $mail->Body   .= "Perfavore clicca il link sottostante per verificare il tuo account. Se non sei stato tu a richiederlo, ti raccomandiamo di non aprire il link:<br>";
        $mail->Body   .= "<a href='http://tomic.bearzi.info/luci/confirm.php?token=$confirmation_token'>Conferma Email</a>";

        $mail->send();
        header("Location: login.php?success=1");
    } catch (Exception $e) {
        echo "Messaggio non inviato. Mailer Error: {$mail->ErrorInfo}";
    }
    exit();

} else {
    echo "Metodo di richiesta non valido.";
}
?>