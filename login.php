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
        <h2>BENVENUTO!</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p style="color: red;">Email o password invalidi.</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 3): ?>
            <p style="color: red;">Conferma la tua email prima di accedere.</p>
        <?php elseif (isset($_GET['confirmed']) && $_GET['confirmed'] == 1): ?>
            <p style="color: green;">Email confermata. Ora puoi accedere.</p>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p style="color: green;">Registrazione effettuata con successo. Controlla la tua casella di posta per la conferma.</p>
        <?php endif; ?>

        <form action="login-check.php" method="post">
            <label for="email" class="label">Email</label>
            <input class="email-box" type="text" id="email" name="email" placeholder="Inserisci la tua Email">

            <label for="password" class="label">Password</label>
            <input class="password-box" type="password" id="password" name="password" placeholder="********">

            <div class="button-container">
                <button class="access-button" type="submit">ACCEDI</button>
                <button class="register-button" type="button" onclick="window.location.href='register.php'">REGISTRATI</button>                    
            </div>
        </form>
    </div>
</body>
</html>
