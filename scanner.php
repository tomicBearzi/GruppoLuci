<?php
session_start();
include 'db_connection.php';

// Verifica se l'utente è autenticato e ha il ruolo 'Totem'
if (!isset($_SESSION['authenticated']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$conn = openConnection();
$stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['user_email']);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($role !== 'Totem') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR Code</title>
    <link rel="stylesheet" href="./css/scanner/style.css">
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Scanner QR Code</h1>
        <p>Scansiona un QR code per confermare una visita.</p>

        <div id="qr-reader" style="width: 500px; margin: auto;"></div>
        <div id="qr-reader-results" style="margin-top: 20px; text-align: center;"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Controlla se il browser supporta l'accesso alla fotocamera
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                document.getElementById('qr-reader-results').innerHTML = `
                    <p style="color: red;">Il tuo browser non supporta l'accesso alla fotocamera. Prova con un browser moderno come Google Chrome o Mozilla Firefox.</p>
                `;
                return;
            }

            // Richiedi i permessi per la fotocamera
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(() => {
                    // Avvia lo scanner QR code
                    const html5QrCode = new Html5Qrcode("qr-reader");
                    const config = { fps: 10, qrbox: 250 };

                    html5QrCode.start(
                        { facingMode: "environment" }, // Usa la fotocamera posteriore
                        config,
                        onScanSuccess,
                        onScanError
                    ).catch(err => {
                        document.getElementById('qr-reader-results').innerHTML = `
                            <p style="color: red;">Errore durante l'avvio dello scanner: ${err}</p>
                        `;
                        console.error(`Errore durante l'avvio dello scanner: ${err}`);
                    });
                })
                .catch(err => {
                    document.getElementById('qr-reader-results').innerHTML = `
                        <p style="color: red;">Accesso alla fotocamera negato. Concedi i permessi per continuare.</p>
                    `;
                    console.error(`Errore nell'accesso alla fotocamera: ${err}`);
                });
        });

        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('qr-reader-results').innerHTML = `
                <p>QR Code Scansionato: ${decodedText}</p>
                <p>Conferma in corso...</p>
            `;

            fetch('register_access.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ token: decodedText }),
            })
            .then(response => {
                console.log('Stato HTTP:', response.status); // Mostra lo stato HTTP
                return response.json();
            })
            .then(data => {
                console.log('Risposta dal server:', data); // Mostra la risposta completa del server
                if (data.success) {
                    document.getElementById('qr-reader-results').innerHTML = `
                        <p style="color: green;">Ingresso registrato con successo!</p>
                    `;
                } else {
                    document.getElementById('qr-reader-results').innerHTML = `
                        <p style="color: red;">Errore: ${data.message}</p>
                    `;
                }
            })
            .catch(error => {
                console.error('Errore durante la richiesta:', error); // Mostra l'errore completo
                document.getElementById('qr-reader-results').innerHTML = `
                    <p style="color: red;">Errore durante la registrazione dell'ingresso: ${error.message}</p>
                `;
            });
        }

        function onScanError(errorMessage) {
            console.error(`Errore di scansione: ${errorMessage}`);
        }
    </script>
</body>
</html>