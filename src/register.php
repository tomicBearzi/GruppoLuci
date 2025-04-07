<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <link rel="icon" href="logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/register/style.css">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="top-bar">
        <img class="logo" src="logo.svg">
    </div>
    <div class="container">

        <h2>Registrazione</h2>

        <div class="registration-container">
            <!-- Messaggi di errore -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <p style="color: #E24343; margin-bottom: 20px;">E-mail già in uso.</p>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 2): ?>
                <p style="color: #E24343; margin-bottom: 20px;">Le password non combaciano.</p>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 3): ?>
                <p style="color: #E24343; margin-bottom: 20px;">E-mail non valida.</p>
            <?php endif; ?>

            <!-- Form di registrazione -->
            <form action="register-check.php" method="POST">
                <input type="text" id="first-name" name="first-name" placeholder="Nome" required>
                <input type="text" id="last-name" name="last-name" placeholder="Cognome" required>
                <input type="email" id="email" name="email" placeholder="E-mail" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <input type="password" id="conferma" name="conferma" placeholder="Conferma Password" required>
                <button type="submit" class="btn">Registrati</button>
            </form>
            <p>Hai un account? <a href="login.php">Accedi</a></p>
        </div>
    </div>
</body>

</html>