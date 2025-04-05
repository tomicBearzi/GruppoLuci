<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="stylesheet" href="./css/register/style.css">
</head>
<body>
    <div>
        <div class="registration-container">
            <h2>Registrati</h2>

            <!-- Messaggi di errore -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <p style="color: red;">Email già usata.</p>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 2): ?>
                <p style="color: red;">Le Password non combaciano.</p>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 3): ?>
                <p style="color: red;">Email non valida.</p>
            <?php endif; ?>

            <!-- Form di registrazione -->
            <form action="register-check.php" method="POST">
                <div class="input-field">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Inserisci la tua Email" required>
                </div>
                <div class="input-field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="********" required>
                </div>
                <div class="input-field">
                    <label for="conferma">Conferma Password:</label>
                    <input type="password" id="conferma" name="conferma" placeholder="********" required>
                </div>
                <button type="submit" class="btn">Registrati</button>
            </form>
        </div>
        <div class="link-btn">
            <p>Hai un account? <a href="login.php">Accedi</a></p>
        </div>
    </div>
</body>
</html>