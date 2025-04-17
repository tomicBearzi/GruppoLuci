<?php
session_start();
include 'db_connection.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica se l'utente è autenticato e ha il ruolo 'Secretary'
if (!isset($_SESSION['authenticated']) || !isset($_SESSION['user_email'])) {
  header("Location: login.php");
  exit();
}

$conn = openConnection();
$stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
$stmt->bind_param("s", $_SESSION['user_email']);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

if ($role !== 'Secretary') {
  header("Location: index.php");
  exit();
}

$message = "";

// Mostra il messaggio salvato nella sessione, se presente
if (isset($_SESSION['message'])) {
  $message = $_SESSION['message'];
  unset($_SESSION['message']); // Rimuovi il messaggio dalla sessione dopo averlo mostrato
}

// Funzione per generare un codice visita unico
function generateVisitCode($length = 16)
{
  return strtoupper(substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_visit"])) {
  $code = $_POST["code"];
  $sql = "DELETE FROM visit WHERE code = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $code);

  if ($stmt->execute()) {
    $_SESSION['message'] = "Visita creata.";
  } else {
    $_SESSION['message'] = "Errore: " . $stmt->error;
  }

  header("Location: dashboard.php?tab=today");
  exit();
}

// Gestione della creazione di una nuova visita
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_visit"]) && !empty($_POST["email"])) {
  $code = generateVisitCode(); // Genera automaticamente il codice
  $email = $_POST["email"];

  // Verifica che l'email esista nella tabella `users`
  $sql_check_email = "SELECT COUNT(*) FROM users WHERE email = ?";
  $stmt_check_email = $conn->prepare($sql_check_email);
  $stmt_check_email->bind_param("s", $email);
  $stmt_check_email->execute();
  $stmt_check_email->bind_result($email_exists);
  $stmt_check_email->fetch();
  $stmt_check_email->close();

  // Se l'email non esiste, crea un utente con credenziali casuali
  if (!$email_exists) {
    $random_password = bin2hex(random_bytes(4)); // Genera una password casuale (8 caratteri)
    $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);
    $first_name = "Guest";
    $last_name = "User";
    $is_confirmed = 1;

    $stmt_create_user = $conn->prepare("INSERT INTO users (email, password, is_confirmed, Role, first_name, last_name) 
                                            VALUES (?, ?, ?, 'Visitor', ?, ?)");
    $stmt_create_user->bind_param("ssiss", $email, $hashed_password, $is_confirmed, $first_name, $last_name);
    $stmt_create_user->execute();
    $stmt_create_user->close();
  } else {
    // Recupera il nome e cognome dell'utente esistente
    $stmt_get_user = $conn->prepare("SELECT first_name, last_name FROM users WHERE email = ?");
    $stmt_get_user->bind_param("s", $email);
    $stmt_get_user->execute();
    $stmt_get_user->bind_result($first_name, $last_name);
    $stmt_get_user->fetch();
    $stmt_get_user->close();
  }

  // Invia un'email con il link di conferma della visita
  $mail = new PHPMailer(true);
  try {
    $mail->isSMTP();
    $mail->Host = 'localhost';
    $mail->SMTPAuth = false;
    $mail->Port = '25';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->setFrom('no-reply@bearzi.info', 'LUCI');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Conferma Visita';
    $mail->Body = "Ciao $first_name $last_name,<br><br>";
    $mail->Body .= "È stata creata una visita per il tuo indirizzo email. Ecco i dettagli della visita:<br>";
    $mail->Body .= "Codice Visita: $code<br>";
    $mail->Body .= "Data: {$_POST['date']}<br>";
    $mail->Body .= "Ora di inizio: {$_POST['start_time']}<br>";
    $mail->Body .= "Ora di fine: {$_POST['end_time']}<br>";
    $mail->Body .= "Numero di visitatori: {$_POST['visitor_count']}<br>";
    $mail->Body .= "Descrizione: {$_POST['description']}<br><br>";
    $mail->Body .= "Per confermare la visita, clicca sul seguente link:<br>";
    $mail->Body .= "<a href='http://tomic.bearzi.info/luci/confirm_visit.php?code=$code'>Conferma Visita</a>";

    $mail->send();
  } catch (Exception $e) {
    error_log("Errore nell'invio dell'email: {$mail->ErrorInfo}");
  }

  $date = $_POST["date"];
  $start_time = $_POST["start_time"];
  $end_time = !empty($_POST["end_time"]) ? $_POST["end_time"] : null; // Gestisce il valore NULL
  $visitor_count = $_POST["visitor_count"];

  // Validazione del valore di visit_status
  $valid_statuses = ['Pending', 'Accepted', 'In_Progress', 'Finished', 'Cancelled'];
  $visit_status = in_array($_POST["visit_status"], $valid_statuses) ? $_POST["visit_status"] : 'Pending';

  $description = $_POST["description"];

  $sql = "INSERT INTO visit (code, email, date, start_time, end_time, visitor_count, visit_status, description) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  // Usa "s" per stringhe e "i" per interi, NULL viene gestito automaticamente
  $stmt->bind_param("sssssiss", $code, $email, $date, $start_time, $end_time, $visitor_count, $visit_status, $description);

  if ($stmt->execute()) {
    $_SESSION['message'] = "Visita creata. Codice: $code";
    header("Location: dashboard.php?tab=today");
    exit();
  } else {
    $_SESSION['message'] = "Errore: " . $stmt->error;
    error_log("SQL Error: " . $stmt->error); // Log detailed error
    header("Location: dashboard.php?tab=create");
    exit();
  }
}

// Lista di tutte le visite
$sql_visits_list = "SELECT * FROM visit ORDER BY date DESC, start_time DESC";
$result_visits_list = $conn->query($sql_visits_list);

// Pending visits
$sql_pending = "SELECT * FROM visit WHERE visit_status = 'Pending'";
$result_pending = $conn->query($sql_pending);

// Closest upcoming visit (by date + time)
$sql_closest = "SELECT * FROM visit WHERE date >= CURDATE() ORDER BY date ASC, start_time ASC LIMIT 1";
$result_closest = $conn->query($sql_closest);
$closest_visit = $result_closest->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Secretary Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="img/logo.svg" type="image/x-icon" />
  <link rel="stylesheet" href="./css/dashboard/style.css">
  <link
    href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,900&f[]=rajdhani@300,400,500,600,700&display=swap"
    rel="stylesheet">

</head>

<body>
  <div class="top-bar">
    <img class="logo" src="img/logo.svg">
    <?php if (isset($_SESSION['authenticated'])) { ?>
      <a class="logout-a" href="logout.php">Logout</a>
    <?php } ?>
  </div>
  
  <div class="container">
    <h1>Dashboard visite</h1>

    <?php if ($message): ?>
      <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($closest_visit): ?>
      <div class="highlight-box">
        <div>
          <strong>Prossima visita:</strong><br>
          <b><?php echo $closest_visit['email']; ?></b> il
          <b><?php echo $closest_visit['date']; ?></b> alle
          <b><?php echo $closest_visit['start_time']; ?></b><br>
        </div>
        <a class="edit_this_visit" href="edit_visit.php?code=<?php echo $closest_visit['code']; ?>">
          <button>Modifica</button>
        </a>
      </div>
    <?php endif; ?>

    <div class="tabs">
      <div class="tab active" onclick="switchTab('today')">Visite</div>
      <div class="tab" onclick="switchTab('pending')">In Attesa</div>
      <div class="tab" onclick="switchTab('create')">Crea</div>
    </div>

    <div id="today" class="tab-content active">
      <h2>Visite</h2>
      <table>
        <tr>
          <th>Codice</th>
          <th>Email</th>
          <th>Data</th>
          <th>Ora Inizio</th>
          <th>Ora fine</th>
          <th>Stato</th>
          <th>Descrizione</th>
          <th>Azioni</th>
        </tr>
        <?php while ($row = $result_visits_list->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['code']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['start_time']; ?></td>
            <td><?php echo $row['end_time']; ?></td>
            <td><?php echo $row['visit_status']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
              <!-- Pulsante Edit -->
              <form method="get" action="edit_visit.php" style="display:inline;">
                <input type="hidden" name="code" value="<?php echo $row['code']; ?>">
                <button type="submit">Modifica</button>
              </form>
              <!-- Pulsante Delete -->
              <form method="post" style="display:inline;">
                <input type="hidden" name="code" value="<?php echo $row['code']; ?>">
                <button type="submit" name="delete_visit"
                  onclick="return confirm('Sei sicuro di voler eliminare questa visita?')">Elimina</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <div id="pending" class="tab-content">
      <h2>Visite Da Confermare</h2>
      <table>
        <tr>
        <th>Codice</th>
          <th>Email</th>
          <th>Data</th>
          <th>Ora Inizio</th>
          <th>Ora fine</th>
          <th>Stato</th>
          <th>Descrizione</th>
          <th>Azioni</th>
        </tr>
        <?php while ($row = $result_pending->fetch_assoc()): ?>
          <tr>
            <td><?php echo $row['code']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['start_time']; ?></td>
            <td><?php echo $row['end_time']; ?></td>
            <td><?php echo $row['visit_status']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
              <!-- Pulsante Edit -->
              <form method="get" action="edit_visit.php" style="display:inline;">
                <input type="hidden" name="code" value="<?php echo $row['code']; ?>">
                <button type="submit">Modifica</button>
              </form>
              <!-- Pulsante Delete -->
              <form method="post" style="display:inline;">
                <input type="hidden" name="code" value="<?php echo $row['code']; ?>">
                <button type="submit" name="delete_visit"
                  onclick="return confirm('Sei sicuro di voler eliminare questa visita?')">Elimina</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>

    <div id="create" class="tab-content">
      <h2>Crea Visita</h2>
      <form method="post" class="form-container">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required placeholder="Email visitatore">

        <label for="date">Data</label>
        <input type="date" name="date" id="date" required>

        <label for="start_time">Ora Inizio</label>
        <input type="time" name="start_time" id="start_time" required>

        <label for="end_time">Ora Fine</label>
        <input type="time" name="end_time" id="end_time">

        <label for="visitor_count">Numero Visitatori</label>
        <input type="number" name="visitor_count" id="visitor_count" required min="1">

        <label for="visit_status">Stato</label>
        <select name="visit_status" id="visit_status" required>
          <option value="Pending">In Attesa</option>
          <option value="Accepted">Accettata</option>
          <option value="In_Progress">In Progresso</option>
          <option value="Finished">Finita</option>
          <option value="Cancelled">Cancellata</option>
        </select>

        <label for="description">Descrizione</label>
        <textarea name="description" id="description" placeholder="Descrizione visita"></textarea>

        <button type="submit" name="create_visit">Crea Visita</button>
      </form>
    </div>
  </div>

  <script>
    function switchTab(tabId) {
      document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
      document.querySelector(`.tab[onclick*="${tabId}"]`).classList.add('active');
      document.getElementById(tabId).classList.add('active');
    }

    // Apri automaticamente la scheda corretta in base al parametro "tab" nell'URL
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'today'; // Default alla scheda "today"
    switchTab(tab);
  </script>

</body>

</html>