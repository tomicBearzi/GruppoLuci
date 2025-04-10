<?php
session_start();
include 'db_connection.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $conn = openConnection();

    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $stmt->close();
        header("Location: login.php?error=1");
        exit();
    }

    $stmt->bind_result($user_id, $hashed_password, $role);
    $stmt->fetch();

    if (!password_verify($password, $hashed_password)) {
        $stmt->close();
        header("Location: login.php?error=1");
        exit();
    }

    $stmt->close();

    // Se il ruolo è 'Totem', accedi automaticamente
    if ($role === 'Totem') {
        $_SESSION['authenticated'] = true;
        $_SESSION['user_email'] = $email;
        header("Location: scanner.php");
        exit();
    }

    // Genera il codice 2FA per altri ruoli
    $two_fa_code = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
    $_SESSION['2fa_code'] = $two_fa_code;
    $_SESSION['user_email'] = $email; // Salva l'email nella sessione

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
        $mail->Subject = 'Codice di Autenticazione a Due Fattori';
        $mail->Body    = "Il tuo codice di autenticazione è: <strong>$two_fa_code</strong><br>";
        $mail->Body   .= "Inserisci questo codice nella pagina di autenticazione per accedere.";

        $mail->send();
        header("Location: 2fa.php");
    } catch (Exception $e) {
        echo "Messaggio non inviato. Mailer Error: {$mail->ErrorInfo}";
    }
    exit();
} else {
    echo "Metodo di richiesta non valido.";
}
?>