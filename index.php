<?php
session_start();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/index/style.css">
    <link
        href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="top-bar">
        <img class="logo" src="img/logo.svg">
        <?  if (!isset($_SESSION['authenticated'])) { ?>
                <a class="logout-a" href="logout.php">Logout</a>
        <?  } ?>
    </div>
    <div class="container">

        <!-- Sezione Informazioni -->
        <div class="info-section">
            <h2>Home</h2>
            <p>Benvenuto sulla nostra piattaforma web! Qui puoi trovare i dettagli delle visite, informazioni sulle
                aziende e
                tanto altro.</p>
        </div>

        <!-- Card Dettagli Visita -->
        <div class="visit-div">
            <h3 class="card-title">Dettagli Visita</h3>
            <? if (!isset($_SESSION['authenticated'])) {
                header('Location: login.php');
                exit();
            }
            ?>
            <a href="visit.php">Visualizza le informazioni o prenota la tua prossima visita!</a>
        </div>

        <!-- Elenco Aziende -->
        <h3 class="companies-text">Le Aziende</h3>
        <div class="companies-div">
            <div class="company-item">
                <a href="https://www.gesteco.it"><img class="gesteco-icon" src="img/gesteco.svg"></a>
                <a href="https://www.labiotest.it"><img class="labiotest-icon" src="img/labiotest.svg"></a>
                <a href="https://www.lodsrl.it"><img class="lod-icon" src="img/lod.png"></a>
            </div>
            <div class="company-item">
                <a href="https://www.metaplas.it"><img class="metaplas-icon" src="img/metaplas.png"></a>
                <a href="https://www.gruppoluci.it/it/le-aziende/lbit"><img class="lbit-icon" src="img/lbit.png"></a>
                <a href="https://www.ecofarmsrl.it"><img class="ecofarm-icon" src="img/ecofarm.png"></a>
            </div>
        </div>
    </div>

    <!-- Sezione Contatti -->
    <div class="bottom-section">
        <div class="contacts-p">
            <p>Gruppo Luci</p>
            <p>Via Pramollo, 6</p>
            <p>Grions del Torre</p>
            <p>33040 Povoletto (Ud) Italy</p>
        </div>
        <div class="contacts-p">
            <p>+39 0432 634411</p>
            <p>info@gruppoluci.it</p>
        </div>
        <div class="contacts-p">
            <p>ELLE PARTECIPAZIONI SRL</p>
            <p>C.F. e P.I. 01489590305</p>
            <p>Nr. Iscr. Reg. Imp. Udine 01489590305</p>
            <p>Cap. Soc. € 103.000,00 i.v.</p>
        </div>
    </div>
</body>

</html>