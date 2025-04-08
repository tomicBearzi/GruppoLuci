<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="icon" href="logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/home/style.css">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top-bar">
        <img class="logo" src="logo.svg">
    </div>
    <div class="container">
        
        <!-- Sezione Informazioni -->
        <div class="info-section">
            <h2>Home</h2>
            <p>Benvenuto nella nostra app! Qui puoi trovare i dettagli delle visite, informazioni sulle aziende e tanto altro.</p>
        </div>

        <!-- Card Dettagli Visita -->
        <div class="card" onclick="window.location.href='visit.html'">
            <div class="card-title">Dettagli Visita</div>
            <p>Visualizza le informazioni sulla tua prossima visita</p>
        </div>

        <!-- Elenco Aziende -->
        <div class="companies-list">
            <h3>Le Aziende</h3>
            <div class="companies-div">
                <div class="company-item">
                    <a href="https://www.gesteco.it"><img class="gesteco-icon" src="gesteco.svg"></a>
                    <a href="https://www.labiotest.it"><img class="labiotest-icon" src="labiotest.svg"></a>
                    <a href="https://www.lodsrl.it"><img class="lod-icon" src="lod.png"></a>
                </div>
                <div class="company-item2">
                    <a href="https://www.metaplas.it"><img class="metaplas-icon" src="metaplas.png"></a>
                    <a href="https://www.gruppoluci.it/it/le-aziende/lbit"><img class="lbit-icon" src="lbit.png"></a>
                    <a href="https://www.ecofarmsrl.it"><img class="ecofarm-icon" src="ecofarm.png"></a>
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
    </div>
</body>

</html>