<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Ricordella
    </title>
    <link rel="icon" href="logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="./css/login/style.css">
</head>
<body>
    <div class="container">
        <h2>AUTENTICAZIONE</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p style="color: red;">Codice Inserito Non Valido.</p>
        <?php endif; ?>

        <form action="2fa-check.php" method="post">
            <label for="code" class="label">Inserisci le cifre</label>
            <input class="password-box" type="text" id="code" name="code" placeholder="__ __ __ __ __" maxlength="5" required>

            <button class="access-button" type="submit">Conferma</button>
        </form>
    </div>
</body>
</html>
