<?php
session_start();
include 'db_connection.php';

if ((!isset($_SESSION['authenticated']) || !isset($_SESSION['user_email'])) && $role !== 'Secretary') {
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
$conn->close();

if ($role !== 'Secretary') {
  header("Location: index.php");
  exit();
}

$visit = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
  $code = $_POST["visit_code"];
  $sql = "SELECT * FROM visit WHERE code = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $code);
  $stmt->execute();
  $result = $stmt->get_result();
  $visit = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
  $code = $_POST["code"];
  $date = $_POST["date"];
  $start = $_POST["start_time"];
  $end = $_POST["end_time"];
  $status = $_POST["visit_status"];
  $desc = $_POST["description"];

  $sql = "UPDATE visit SET date = ?, start_time = ?, end_time = ?, visit_status = ?, description = ? WHERE code = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssss", $date, $start, $end, $status, $desc, $code);
  if ($stmt->execute()) {
    $message = "Visita modificata.";
  } else {
    $message = "Errore.";
  }

  // Reload updated data
  $sql = "SELECT * FROM visit WHERE code = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $code);
  $stmt->execute();
  $result = $stmt->get_result();
  $visit = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Modifica Visite</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="img/logo.svg" type="image/x-icon" />
  <link rel="stylesheet" href="./css/edit_visit/style.css">
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

  <h1>Trova e Modifica Visite</h1>
  
    <div class="form-container">
      <form method="post">
        <label for="visit_code">Codice Visita</label>
        <input type="text" name="visit_code" id="visit_code" required placeholder="Codice visita (e.g., A2M1DK...)">
        <button type="submit" name="search">Cerca</button>
      </form>

      <?php if ($visit): ?>
        <hr>
        <h2>Modifica Dettagli visita</h2>
        <form method="post">
          <input type="hidden" name="code" value="<?php echo $visit['code']; ?>">

          <label>Email</label>
          <input type="text" value="<?php echo $visit['email']; ?>" disabled>

          <label for="date">Data</label>
          <input type="date" name="date" value="<?php echo $visit['date']; ?>" required>

          <label for="start_time">Ora Inizio</label>
          <input type="time" name="start_time" value="<?php echo $visit['start_time']; ?>" required>

          <label for="end_time">Ora Fine</label>
          <input type="time" name="end_time" value="<?php echo $visit['end_time']; ?>">

          <label for="visit_status">Stato</label>
          <select name="visit_status" required>
            <?php
            $statuses = ['Pending', 'Accepted', 'In_Progress', 'Finished', 'Cancelled'];
            foreach ($statuses as $s) {
              $selected = $visit['visit_status'] === $s ? "selected" : "";
              echo "<option value='$s' $selected>$s</option>";
            }
            ?>
          </select>

          <label for="description">Descrizione</label>
          <textarea name="description"><?php echo $visit['description']; ?></textarea>

          <button type="submit" name="update">Salva Modifiche</button>
        </form>
      <?php endif; ?>

      <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>