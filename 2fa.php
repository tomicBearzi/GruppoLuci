<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticazione</title>
    <link rel="icon" href="img/logo.svg" type="image/x-icon" />
    <link rel="stylesheet" href="./css/2fa/style.css">
    <link
        href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="top-bar">
        <img class="logo" src="img/logo.svg">
    </div>
    <div class="container">

        <h2>Autenticazione</h2>

        <div class="verification-container">
            <p>Inserisci il codice che ti abbiamo inviato nella tua casella di posta!</p>

            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <p style="color: #E24343; margin-bottom: 20px;">Codice inserito non valido.</p>
            <?php endif; ?>

            <form id="verificationForm" action="2fa-check.php" method="POST">
                <div class="input-group">
                    <input type="text" id="input1" name="input1" maxlength="1" oninput="moveFocus(1)"
                        onkeydown="handleBackspace(event, 1)">
                    <input type="text" id="input2" name="input2" maxlength="1" oninput="moveFocus(2)"
                        onkeydown="handleBackspace(event, 2)">
                    <input type="text" id="input3" name="input3" maxlength="1" oninput="moveFocus(3)"
                        onkeydown="handleBackspace(event, 3)">
                    <input type="text" id="input4" name="input4" maxlength="1" oninput="moveFocus(4)"
                        onkeydown="handleBackspace(event, 4)">
                    <input type="text" id="input5" name="input5" maxlength="1" oninput="submitForm()"
                        onkeydown="handleBackspace(event, 5)">
                </div>

                <button type="submit" class="btn">Invia</button>
            </form>
        </div>
    </div>

    <script>
        function moveFocus(current) {
            const nextInput = document.getElementById('input' + (current + 1));
            if (nextInput && document.getElementById('input' + current).value.length === 1) {
                nextInput.focus();
            }
        }

        function submitForm() {
            const form = document.getElementById('verificationForm');
            const inputs = document.querySelectorAll('input[type="text"]');
            let allFilled = true;

            inputs.forEach(input => {
                if (input.value.length !== 1) {
                    allFilled = false;
                }
            });

            if (allFilled) {
                form.submit();
            }
        }

        function handleBackspace(event, current) {
            if (event.key === "Backspace" && document.getElementById('input' + current).value === "") {
                const prevInput = document.getElementById('input' + (current - 1));
                if (prevInput) {
                    prevInput.focus();
                }
            }
        }
    </script>

</body>

</html>