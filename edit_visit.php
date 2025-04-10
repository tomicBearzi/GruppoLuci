<?php
session_start();
include 'db_connection.php';

// Verifica se l'utente è autenticato e ha il ruolo 'Totem'
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
$conn->close();

if ($role !== 'Secretary') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "tom_mat_db_4";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

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
    $message = "✅ Visit updated successfully.";
  } else {
    $message = "❌ Error updating visit.";
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
  <title>Edit Visit</title>
  <style>
    body { font-family: sans-serif; padding: 30px; background: #f4f6f9; }
    h1 { margin-bottom: 20px; }
    input, select, textarea {
      padding: 8px;
      width: 100%;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    label { font-weight: bold; }
    button {
      padding: 10px 20px;
      background-color: #0066cc;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .form-container {
      background: white;
      padding: 20px;
      border-radius: 10px;
      max-width: 600px;
      margin: auto;
    }
    .message { margin-top: 10px; color: green; font-weight: bold; }
  </style>
</head>
<body>

<div class="form-container">
  <h1>🔎 Find & Edit Visit</h1>

  <form method="post">
    <label for="visit_code">Visit Code</label>
    <input type="text" name="visit_code" id="visit_code" required placeholder="Enter visit code (e.g., A2M1DK...)">
    <button type="submit" name="search">Search</button>
  </form>

  <?php if ($visit): ?>
    <hr>
    <h2>📝 Edit Visit Details</h2>
    <form method="post">
      <input type="hidden" name="code" value="<?php echo $visit['code']; ?>">

      <label>Email</label>
      <input type="text" value="<?php echo $visit['email']; ?>" disabled>

      <label for="date">Date</label>
      <input type="date" name="date" value="<?php echo $visit['date']; ?>" required>

      <label for="start_time">Start Time</label>
      <input type="time" name="start_time" value="<?php echo $visit['start_time']; ?>" required>

      <label for="end_time">End Time</label>
      <input type="time" name="end_time" value="<?php echo $visit['end_time']; ?>">

      <label for="visit_status">Status</label>
      <select name="visit_status" required>
        <?php
          $statuses = ['Pending','Accepted','In_Progress','Finished','Cancelled'];
          foreach ($statuses as $s) {
            $selected = $visit['visit_status'] === $s ? "selected" : "";
            echo "<option value='$s' $selected>$s</option>";
          }
        ?>
      </select>

      <label for="description">Description</label>
      <textarea name="description"><?php echo $visit['description']; ?></textarea>

      <button type="submit" name="update">💾 Save Changes</button>
    </form>
  <?php endif; ?>

  <?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
  <?php endif; ?>
</div>

</body>
</html>
