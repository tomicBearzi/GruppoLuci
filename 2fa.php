<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica a 2 Fattori</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .verification-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        .input-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .input-group input {
            width: 40px;
            height: 40px;
            font-size: 20px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="verification-container">
        <h2>Verifica a 2 Fattori</h2>
        
        <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
            <p style="color: red;">Codice Inserito Non Valido.</p>
        <?php endif; ?>

        <form id="verificationForm" action="2fa-check.php" method="POST">
            <div class="input-group">
                <input type="text" id="input1" name="input1" maxlength="1" oninput="moveFocus(1)" onkeydown="handleBackspace(event, 1)">
                <input type="text" id="input2" name="input2" maxlength="1" oninput="moveFocus(2)" onkeydown="handleBackspace(event, 2)">
                <input type="text" id="input3" name="input3" maxlength="1" oninput="moveFocus(3)" onkeydown="handleBackspace(event, 3)">
                <input type="text" id="input4" name="input4" maxlength="1" oninput="moveFocus(4)" onkeydown="handleBackspace(event, 4)">
                <input type="text" id="input5" name="input5" maxlength="1" oninput="submitForm()" onkeydown="handleBackspace(event, 5)">
            </div>
            <button type="submit" class="btn" style="display: none;">Invia</button>
        </form>
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
