<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Ricordella
    </title>
    <link rel="icon" href="logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="./css/register/style.css">
</head>
<body>
    
    <div class="container">
        <h2>REGISTRATI</h2>

        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p style="color: red;">Email già usata.</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 2): ?>
            <p style="color: red;">Le Password non combaciano.</p>
        <?php elseif (isset($_GET['error']) && $_GET['error'] == 2): ?>
            <p style="color: red;">Le Email non esiste.</p>
        <?php endif; ?>

        <form action="register-check.php" method="post">
            <label for="email" class="label">Email</label>
            <input class="email-box" type="text" id="email" name="email" placeholder="Inserisci la tua Email ">

            <label for="password" class="label">Password</label>
            <input class="password-box" type="password" id="password" name="password" placeholder="********">

            <label for="conferma" class="label">Conferma</label>
            <input class="password-box" type="password" id="conferma" name="conferma" placeholder="********">

            <div class="button-container">
                <button class="confirm-button" type="submit">CONFERMA</button>
                <button class="access-button" type="button" onclick="window.location.href='login.php'">ACCEDI</button>
            </div>
        </form>
    </div>
</body>
</html>

