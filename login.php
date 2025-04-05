<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="./css/login/style.css">
</head>
<body>
    <div>
        <div class="login-container">
            <h2>Accedi</h2>

            <!-- Messaggi di errore o successo -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <p style="color: red;">Email o password invalidi.</p>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 3): ?>
                <p style="color: red;">Conferma la tua email prima di accedere.</p>
            <?php elseif (isset($_GET['confirmed']) && $_GET['confirmed'] == 1): ?>
                <p style="color: green;">Email confermata. Ora puoi accedere.</p>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == 1): ?>
                <p style="color: green;">Registrazione effettuata con successo. Controlla la tua casella di posta per la conferma.</p>
            <?php endif; ?>

            <!-- Form di login -->
            <form action="login-check.php" method="POST">
                <div class="input-field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Inserisci la tua Email" required>
                </div>
                <div class="input-field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="********" required>
                </div>
                <button type="submit" class="btn">Accedi</button>
            </form>
        </div>
        <div class="link-btn">
            <p>Non hai un account? <a href="register.php">Registrati</a></p>
        </div>
    </div>
</body>
</html>