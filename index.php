<!-- filepath: c:\Users\Administrator\Desktop\GruppoLuci\index.php -->
<?php
session_start();

// Controlla se l'utente è autenticato
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Pagina Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Mobile-first CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 16px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
        }

        .info-section {
            text-align: center;
            margin-bottom: 16px;
        }

        .info-section h2 {
            margin-bottom: 8px;
        }

        .card,
        .company-item {
            background-color: #fff;
            margin-bottom: 16px;
            padding: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .card:hover,
        .company-item:hover {
            background-color: #e9e9e9;
        }

        .card-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .companies-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .bottom-section {
            text-align: center;
        }

        .bottom-section h3 {
            margin-bottom: 8px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Sezione Informazioni -->
        <div class="info-section">
            <h2>Informazioni</h2>
            <p>
                Benvenuto nella nostra app! Qui puoi trovare i dettagli delle visite,
                informazioni sulle aziende e tanto altro.
            </p>
        </div>

        <!-- Card Dettagli Visita -->
        <div class="card" onclick="window.location.href='visit.php'">
            <div class="card-title">Dettagli Visita</div>
            <p>Visualizza le informazioni sulla tua prossima visita.</p>
        </div>

        <!-- Elenco Aziende -->
        <h3>Le Aziende</h3>
        <div class="companies-list">
            <div class="company-item" onclick="window.location.href='companyInfo.php?azienda=Geseco'">
                Geseco
            </div>
            <div class="company-item" onclick="window.location.href='companyInfo.php?azienda=Metaipes'">
                Metaipes
            </div>
            <div class="company-item" onclick="window.location.href='companyInfo.php?azienda=Ecoland'">
                Ecoland
            </div>
            <div class="company-item" onclick="window.location.href='companyInfo.php?azienda=EdFarm'">
                EdFarm
            </div>
        </div>

        <!-- Sezione Contatti -->
        <div class="bottom-section">
            <h3>Contattaci & Prenota Appuntamento</h3>
            <p>Clicca qui per maggiori informazioni</p>
            <a href="companyInfo.php">
                <div class="card">
                    <p>Contatti e Prenotazioni</p>
                </div>
            </a>
        </div>
    </div>
</body>

</html>