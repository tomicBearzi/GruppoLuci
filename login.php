<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/login/style.css">
    <link
        href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="top-bar">
        <img class="logo" src="img/logo.svg">
    </div>
    <div class="container">

        <h2>Accedi</h2>

        <div class="login-container">
            <!-- Messaggi di errore o successo -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <p style="color: #E24343; margin-bottom: 20px;">E-mail o password invalidi.</p>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 3): ?>
                <p style="color: #E24343; margin-bottom: 20px;">Conferma la tua E-mail prima di accedere.</p>
            <?php elseif (isset($_GET['confirmed']) && $_GET['confirmed'] == 1): ?>
                <p style="color: #4BC047; margin-bottom: 20px;">E-mail confermata. Ora puoi accedere.</p>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <p style="color: #4BC047; margin-bottom: 20px;">Registrazione effettuata con successo. Controlla la tua
                    casella di posta per la
                    conferma.</p>
            <?php endif; ?>

            <!-- Form di login -->
            <form action="login-check.php" method="POST">
                <input type="email" id="email" name="email" placeholder="E-mail" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn">Accedi</button>
            </form>
            <p>Non hai un account? <a href="register.php">Registrati</a></p>
        </div>
</body>

</html>